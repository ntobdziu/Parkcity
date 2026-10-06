<?php

namespace App\Http\Controllers;

use App\Models\ParkingSpot;
use App\Models\Reservation;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index()
    {
        $this->releaseExpired();

        $reservations = Reservation::with("spot.zone")
            ->where("user_id", Auth::id())
            ->latest("start_time")
            ->get();

        return view("reservations.index", compact("reservations"));
    }

    public function create()
    {
        $spots = ParkingSpot::with("zone")->where("status", "slobodno")->get();

        return view("reservations.create", compact("spots"));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "spot_id" => ["required", "exists:parking_spots,id"],
            "start_time" => ["required", "date", "after_or_equal:now"],
            "end_time" => ["required", "date", "after:start_time"],
        ]);

        $spot = ParkingSpot::findOrFail($data["spot_id"]);

        if ($spot->status !== "slobodno") {
            return back()->withErrors(["spot_id" => "Izabrano parking mesto trenutno nije slobodno."])->withInput();
        }

        $reservation = Reservation::create([
            "user_id" => Auth::id(),
            "spot_id" => $spot->id,
            "start_time" => $data["start_time"],
            "end_time" => $data["end_time"],
            "status" => "aktivna",
        ]);

        $spot->update(["status" => "rezervisano"]);
        ActivityLogger::log("Rezervisano parking mesto {$spot->code}");

        return redirect()->route("reservations.index")->with("status", "Parking mesto je rezervisano.");
    }

    public function cancel(Reservation $reservation)
    {
        abort_unless($reservation->user_id === Auth::id(), 403);

        $reservation->update(["status" => "otkazana"]);

        if ($reservation->spot->status === "rezervisano") {
            $reservation->spot->update(["status" => "slobodno"]);
        }

        ActivityLogger::log("Otkazana rezervacija #{$reservation->id}");

        return back()->with("status", "Rezervacija je otkazana.");
    }

    private function releaseExpired(): void
    {
        $expired = Reservation::where("status", "aktivna")
            ->where("end_time", "<", now())
            ->get();

        foreach ($expired as $reservation) {
            $reservation->update(["status" => "zavrsena"]);

            if ($reservation->spot && $reservation->spot->status === "rezervisano") {
                $reservation->spot->update(["status" => "slobodno"]);
            }
        }
    }
}
