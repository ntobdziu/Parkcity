<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    public function index()
    {
        $zones = Zone::withCount("spots")->latest()->get();

        return view("zones.index", compact("zones"));
    }

    public function create()
    {
        return view("zones.create");
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $zone = Zone::create($data);

        ActivityLogger::log("Kreirana zona {$zone->name}");

        return redirect()->route("zones.index")->with("status", "Zona je kreirana.");
    }

    public function edit(Zone $zone)
    {
        return view("zones.edit", compact("zone"));
    }

    public function update(Request $request, Zone $zone)
    {
        $zone->update($this->validated($request));

        ActivityLogger::log("Izmenjena zona {$zone->name}");

        return redirect()->route("zones.index")->with("status", "Zona je ažurirana.");
    }

    public function destroy(Zone $zone)
    {
        $zone->delete();

        return redirect()->route("zones.index")->with("status", "Zona je obrisana.");
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            "name" => ["required", "string", "max:100"],
            "location" => ["required", "string", "max:255"],
            "tariff_per_hour" => ["required", "numeric", "min:0"],
            "max_duration_minutes" => ["nullable", "integer", "min:1"],
            "work_hours_from" => ["required"],
            "work_hours_to" => ["required"],
            "type" => ["required", "in:standardna,rezidentska,invalidska"],
        ]);
    }
}
