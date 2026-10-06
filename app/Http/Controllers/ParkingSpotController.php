<?php

namespace App\Http\Controllers;

use App\Models\ParkingSpot;
use App\Models\Zone;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class ParkingSpotController extends Controller
{
    public function index()
    {
        $spots = ParkingSpot::with("zone")->latest()->paginate(20);

        return view("spots.index", compact("spots"));
    }

    public function create()
    {
        $zones = Zone::orderBy("name")->get();

        return view("spots.create", compact("zones"));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "zone_id" => ["required", "exists:zones,id"],
            "code" => ["required", "string", "max:20"],
            "special_type" => ["nullable", "in:invalidsko,elektro,dostava"],
        ]);
        $data["status"] = "slobodno";

        ParkingSpot::create($data);
        ActivityLogger::log("Kreirano parking mesto {$data["code"]}");

        return redirect()->route("spots.index")->with("status", "Parking mesto je dodato.");
    }

    public function edit(ParkingSpot $spot)
    {
        $zones = Zone::orderBy("name")->get();

        return view("spots.edit", compact("spot", "zones"));
    }

    public function update(Request $request, ParkingSpot $spot)
    {
        $data = $request->validate([
            "zone_id" => ["required", "exists:zones,id"],
            "code" => ["required", "string", "max:20"],
            "status" => ["required", "in:slobodno,zauzeto,rezervisano,nedostupno"],
            "special_type" => ["nullable", "in:invalidsko,elektro,dostava"],
        ]);

        $spot->update($data);
        ActivityLogger::log("Ažurirano parking mesto {$spot->code} (status: {$spot->status})");

        return redirect()->route("spots.index")->with("status", "Parking mesto je ažurirano.");
    }

    public function destroy(ParkingSpot $spot)
    {
        $spot->delete();

        return redirect()->route("spots.index")->with("status", "Parking mesto je obrisano.");
    }
}
