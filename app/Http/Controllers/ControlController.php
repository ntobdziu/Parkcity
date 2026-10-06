<?php

namespace App\Http\Controllers;

use App\Models\Control;
use App\Models\Fine;
use App\Models\ResidentPermit;
use App\Models\Vehicle;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ControlController extends Controller
{
    const DEFAULT_FINE_AMOUNT = 3000;

    public function index()
    {
        $controls = Control::with(["vehicle", "fine"])
            ->where("controller_id", Auth::id())
            ->latest("timestamp")
            ->paginate(20);

        return view("controls.index", compact("controls"));
    }

    public function create()
    {
        return view("controls.create");
    }

    public function lookup(Request $request)
    {
        $data = $request->validate(["plate" => ["required", "string", "max:20"]]);

        $vehicle = Vehicle::with("owner")->where("plate", $data["plate"])->first();

        if (! $vehicle) {
            return back()->withErrors(["plate" => "Vozilo sa navedenom registracijom nije pronađeno u sistemu."])->withInput();
        }

        $activeTicket = $vehicle->activeTicket()->first();
        $activePermit = ResidentPermit::where("plate", $vehicle->plate)
            ->where("status", "aktivna")
            ->whereDate("valid_from", "<=", today())
            ->whereDate("valid_until", ">=", today())
            ->first();

        $lastTicket = $vehicle->parkingTickets()->latest("end_time")->first();

        if ($activeTicket || $activePermit) {
            $suggestedResult = "ispravno";
        } elseif ($lastTicket) {
            $suggestedResult = "isteklo";
        } else {
            $suggestedResult = "neplaceno";
        }

        return view("controls.confirm", compact("vehicle", "activeTicket", "activePermit", "suggestedResult"));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "vehicle_id" => ["required", "exists:vehicles,id"],
            "location" => ["required", "string", "max:255"],
            "result" => ["required", "in:ispravno,neplaceno,isteklo"],
        ]);

        $control = Control::create([
            "controller_id" => Auth::id(),
            "vehicle_id" => $data["vehicle_id"],
            "location" => $data["location"],
            "timestamp" => now(),
            "result" => $data["result"],
        ]);

        if ($data["result"] !== "ispravno") {
            Fine::create([
                "control_id" => $control->id,
                "vehicle_id" => $data["vehicle_id"],
                "amount" => self::DEFAULT_FINE_AMOUNT,
                "status" => "neplacena",
                "issued_at" => now(),
            ]);
        }

        ActivityLogger::log("Izvršena kontrola vozila (rezultat: {$data["result"]})");

        return redirect()->route("controls.index")->with("status", "Kontrola je evidentirana.");
    }
}
