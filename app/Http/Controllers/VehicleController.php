<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::where("user_id", Auth::id())->latest()->get();

        return view("vehicles.index", compact("vehicles"));
    }

    public function create()
    {
        return view("vehicles.create");
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "plate" => ["required", "string", "max:20", "unique:vehicles,plate"],
            "brand" => ["required", "string", "max:50"],
            "model" => ["required", "string", "max:50"],
            "type" => ["required", "in:putnicko,dostavno,motocikl"],
            "year" => ["nullable", "integer", "min:1950", "max:" . (date("Y") + 1)],
        ]);

        $data["user_id"] = Auth::id();
        Vehicle::create($data);

        ActivityLogger::log("Registracija vozila {$data["plate"]}");

        return redirect()->route("vehicles.index")->with("status", "Vozilo je uspešno dodato.");
    }

    public function edit(Vehicle $vehicle)
    {
        $this->authorizeOwner($vehicle);

        return view("vehicles.edit", compact("vehicle"));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $this->authorizeOwner($vehicle);

        $data = $request->validate([
            "plate" => ["required", "string", "max:20", "unique:vehicles,plate," . $vehicle->id],
            "brand" => ["required", "string", "max:50"],
            "model" => ["required", "string", "max:50"],
            "type" => ["required", "in:putnicko,dostavno,motocikl"],
            "year" => ["nullable", "integer", "min:1950", "max:" . (date("Y") + 1)],
        ]);

        $vehicle->update($data);

        return redirect()->route("vehicles.index")->with("status", "Podaci o vozilu su ažurirani.");
    }

    public function destroy(Vehicle $vehicle)
    {
        $this->authorizeOwner($vehicle);
        $vehicle->delete();

        return redirect()->route("vehicles.index")->with("status", "Vozilo je uklonjeno.");
    }

    private function authorizeOwner(Vehicle $vehicle): void
    {
        abort_unless($vehicle->user_id === Auth::id(), 403);
    }
}
