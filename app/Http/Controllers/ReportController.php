<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use App\Models\ParkingSpot;
use App\Models\ParkingTicket;
use App\Models\Zone;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $ticketsByZone = ParkingTicket::select("zone_id", DB::raw("count(*) as broj"), DB::raw("sum(price) as prihod"))
            ->groupBy("zone_id")->with("zone")->get();

        $finesStats = Fine::select("status", DB::raw("count(*) as broj"), DB::raw("sum(amount) as iznos"))
            ->groupBy("status")->get();

        $occupancy = Zone::withCount([
            "spots as ukupno_mesta",
            "spots as zauzeta_mesta" => fn ($q) => $q->where("status", "zauzeto"),
            "spots as slobodna_mesta" => fn ($q) => $q->where("status", "slobodno"),
        ])->get();

        $totalRevenue = ParkingTicket::sum("price");

        return view("reports.index", compact("ticketsByZone", "finesStats", "occupancy", "totalRevenue"));
    }
}
