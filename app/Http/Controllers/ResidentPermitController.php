<?php

namespace App\Http\Controllers;

use App\Models\ResidentPermit;
use App\Models\Zone;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResidentPermitController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (in_array($user->role, ["operater", "admin"], true)) {
            $permits = ResidentPermit::with(["user", "zone"])->latest()->paginate(20);

            return view("permits.index", compact("permits"));
        }

        $permits = ResidentPermit::with("zone")->where("user_id", $user->id)->latest()->get();

        return view("permits.mine", compact("permits"));
    }

    public function create()
    {
        $zones = Zone::orderBy("name")->get();

        return view("permits.create", compact("zones"));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "zone_id" => ["required", "exists:zones,id"],
            "plate" => ["required", "string", "max:20"],
            "valid_from" => ["required", "date"],
            "valid_until" => ["required", "date", "after:valid_from"],
        ]);

        $data["user_id"] = Auth::id();
        $data["status"] = "na_cekanju";

        ResidentPermit::create($data);
        ActivityLogger::log("Zahtev za rezidentsku dozvolu (zona #{$data["zone_id"]})");

        return redirect()->route("permits.index")->with("status", "Zahtev za rezidentsku dozvolu je poslat operateru na odobrenje.");
    }

    public function approve(ResidentPermit $permit)
    {
        abort_unless(in_array(Auth::user()->role, ["operater", "admin"], true), 403);

        $permit->update(["status" => "aktivna"]);
        ActivityLogger::log("Odobrena rezidentska dozvola #{$permit->id}");

        return back()->with("status", "Dozvola je odobrena.");
    }

    public function reject(ResidentPermit $permit)
    {
        abort_unless(in_array(Auth::user()->role, ["operater", "admin"], true), 403);

        $permit->update(["status" => "odbijena"]);
        ActivityLogger::log("Odbijena rezidentska dozvola #{$permit->id}");

        return back()->with("status", "Dozvola je odbijena.");
    }
}
