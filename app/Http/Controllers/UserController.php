<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->latest();

        if ($request->filled("role")) {
            $query->where("role", $request->string("role"));
        }
        if ($request->filled("status")) {
            $query->where("status", $request->string("status"));
        }

        $users = $query->paginate(20)->withQueryString();

        return view("users.index", compact("users"));
    }

    public function approve(User $user)
    {
        $user->update(["status" => "active"]);
        ActivityLogger::log("Odobren nalog korisnika {$user->email}");

        return back()->with("status", "Nalog je odobren.");
    }

    public function deactivate(User $user)
    {
        $user->update(["status" => "inactive"]);
        ActivityLogger::log("Deaktiviran nalog korisnika {$user->email}");

        return back()->with("status", "Nalog je deaktiviran.");
    }

    public function updateRole(Request $request, User $user)
    {
        $data = $request->validate([
            "role" => ["required", "in:admin,operater,kontrolor,gradjanin"],
        ]);

        $user->update(["role" => $data["role"]]);
        ActivityLogger::log("Izmenjena rola korisnika {$user->email} na {$data["role"]}");

        return back()->with("status", "Rola korisnika je izmenjena.");
    }
}
