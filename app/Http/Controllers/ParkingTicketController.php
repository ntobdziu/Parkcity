<?php

namespace App\Http\Controllers;

use App\Models\ParkingTicket;
use App\Models\Vehicle;
use App\Models\Zone;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ParkingTicketController extends Controller
{
    public function index()
    {
        ParkingTicket::where('status', 'aktivna')
            ->where('end_time', '<', now())
            ->update(['status' => 'istekla']);

        $tickets = ParkingTicket::with(['vehicle', 'zone'])
            ->where('user_id', Auth::id())
            ->latest('start_time')
            ->paginate(15);

        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        $vehicles = Vehicle::where("user_id", Auth::id())->get();
        $zones = Zone::orderBy("name")->get();

        return view("tickets.create", compact("vehicles", "zones"));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "vehicle_id" => ["required", "exists:vehicles,id"],
            "zone_id" => ["required", "exists:zones,id"],
            "channel" => ["required", "in:app,sms,automat"],
            "duration_minutes" => ["required", "integer", "min:15", "max:1440"],
        ]);

        $vehicle = Vehicle::findOrFail($data["vehicle_id"]);
        abort_unless($vehicle->user_id === Auth::id(), 403);

        $zone = Zone::findOrFail($data["zone_id"]);

        if ($zone->max_duration_minutes && $data["duration_minutes"] > $zone->max_duration_minutes) {
            return back()->withErrors([
                "duration_minutes" => "Maksimalno dozvoljeno trajanje u ovoj zoni je {$zone->max_duration_minutes} minuta.",
            ])->withInput();
        }

        $start = now();
        $end = $start->copy()->addMinutes((int) $data["duration_minutes"]);
        $price = round(((float) $zone->tariff_per_hour) * ($data["duration_minutes"] / 60), 2);

        $ticket = ParkingTicket::create([
            "user_id" => Auth::id(),
            "vehicle_id" => $vehicle->id,
            "zone_id" => $zone->id,
            "channel" => $data["channel"],
            "start_time" => $start,
            "end_time" => $end,
            "price" => $price,
            "status" => "aktivna",
            "qr_code" => strtoupper(Str::random(10)),
        ]);

        ActivityLogger::log("Kupljena parking karta za vozilo {$vehicle->plate} (zona {$zone->name})");

        return redirect()->route("tickets.show", $ticket)->with("status", "Parking karta je kupljena.");
    }

    public function show(ParkingTicket $ticket)
    {
        abort_unless(
            $ticket->user_id === Auth::id() || in_array(Auth::user()->role, ["admin", "kontrolor", "operater"], true),
            403
        );

        return view("tickets.show", compact("ticket"));
    }

    public function extend(Request $request, ParkingTicket $ticket)
    {
        abort_unless($ticket->user_id === Auth::id(), 403);

        $data = $request->validate([
            "extra_minutes" => ["required", "integer", "min:15", "max:1440"],
        ]);

        $zone = $ticket->zone;
        $ticket->end_time = $ticket->end_time->addMinutes((int) $data["extra_minutes"]);
        $ticket->price += round(((float) $zone->tariff_per_hour) * ($data["extra_minutes"] / 60), 2);
        $ticket->save();

        ActivityLogger::log("Produžena parking karta #{$ticket->id}");

        return back()->with("status", "Trajanje parkinga je produženo.");
    }

    public function cancel(ParkingTicket $ticket)
    {
        abort_unless($ticket->user_id === Auth::id(), 403);

        $ticket->update(["status" => "otkazana"]);
        ActivityLogger::log("Otkazana parking karta #{$ticket->id}");

        return back()->with("status", "Parking karta je otkazana.");
    }
}
