<?php

namespace App\Http\Controllers;

use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view("auth.login");
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            "email" => ["required", "email"],
            "password" => ["required"],
        ]);

        if (! Auth::attempt($credentials, $request->boolean("remember"))) {
            return back()->withErrors(["email" => "Pogrešan email ili lozinka."])->onlyInput("email");
        }

        $user = Auth::user();

        if ($user->status !== "active") {
            Auth::logout();

            $message = $user->status === "pending"
                ? "Vaš nalog čeka odobrenje administratora."
                : "Vaš nalog je deaktiviran. Obratite se administratoru.";

            return back()->withErrors(["email" => $message])->onlyInput("email");
        }

        $request->session()->regenerate();
        ActivityLogger::log("Prijava na sistem");

        return redirect()->intended(route("dashboard"));
    }

    public function showRegister()
    {
        return view("auth.register");
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            "name" => ["required", "string", "max:255"],
            "email" => ["required", "string", "email", "max:255", "unique:users,email"],
            "phone" => ["nullable", "string", "max:20"],
            "role" => ["required", "in:gradjanin,operater,kontrolor"],
            "password" => ["required", "confirmed", Password::min(8)],
        ]);

        $status = $data["role"] === "gradjanin" ? "active" : "pending";

        $user = \App\Models\User::create([
            "name" => $data["name"],
            "email" => $data["email"],
            "phone" => $data["phone"] ?? null,
            "role" => $data["role"],
            "status" => $status,
            "password" => Hash::make($data["password"]),
        ]);

        if ($status === "pending") {
            return redirect()->route("login")->with(
                "status",
                "Nalog je kreiran i čeka odobrenje administratora. Bićete obavešteni kada nalog bude aktiviran."
            );
        }

        Auth::login($user);
        ActivityLogger::log("Registracija novog naloga (građanin)");

        return redirect()->route("dashboard");
    }

    public function logout(Request $request)
    {
        ActivityLogger::log("Odjava sa sistema");
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route("login");
    }
}
