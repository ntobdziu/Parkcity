<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FineController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === "admin") {
            $fines = Fine::with(["vehicle.owner", "control"])->latest("issued_at")->paginate(20);
        } elseif ($user->role === "kontrolor") {
            $fines = Fine::with(["vehicle.owner", "control"])
                ->whereHas("control", fn ($q) => $q->where("controller_id", $user->id))
                ->latest("issued_at")->paginate(20);
        } else {
            $fines = Fine::with(["vehicle", "control"])
                ->whereHas("vehicle", fn ($q) => $q->where("user_id", $user->id))
                ->latest("issued_at")->paginate(20);
        }

        return view("fines.index", compact("fines"));
    }

    public function pay(Request $request, Fine $fine)
    {
        $data = $request->validate([
            "payment_method" => ["required", "in:gotovina,kartica,online"],
        ]);

        $fine->update([
            "status" => "placena",
            "payment_method" => $data["payment_method"],
            "paid_at" => now(),
        ]);

        ActivityLogger::log("Naplaćena kazna #{$fine->id} ({$data["payment_method"]})");

        return back()->with("status", "Kazna je evidentirana kao plaćena.");
    }
}
