<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Control;
use App\Models\Fine;
use App\Models\ParkingSpot;
use App\Models\ParkingTicket;
use App\Models\ResidentPermit;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Zone;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return match ($user->role) {
            "admin" => view("dashboard.admin", [
                "usersPending" => User::where("status", "pending")->count(),
                "usersTotal" => User::count(),
                "zones" => Zone::count(),
                "spots" => ParkingSpot::count(),
                "activeTickets" => ParkingTicket::where("status", "aktivna")->count(),
                "unpaidFines" => Fine::where("status", "neplacena")->count(),
                "recentLogs" => ActivityLog::with("user")->latest("timestamp")->limit(10)->get(),
            ]),
            "operater" => view("dashboard.operater", [
                "zones" => Zone::withCount("spots")->get(),
                "pendingPermits" => ResidentPermit::where("status", "na_cekanju")->count(),
                "occupied" => ParkingSpot::where("status", "zauzeto")->count(),
                "free" => ParkingSpot::where("status", "slobodno")->count(),
            ]),
            "kontrolor" => view("dashboard.kontrolor", [
                "myControlsToday" => Control::where("controller_id", $user->id)
                    ->whereDate("timestamp", today())->count(),
                "recentControls" => Control::with("vehicle")
                    ->where("controller_id", $user->id)->latest("timestamp")->limit(10)->get(),
            ]),
            default => view("dashboard.gradjanin", [
                "vehicles" => Vehicle::where("user_id", $user->id)->count(),
                "activeTickets" => ParkingTicket::where("user_id", $user->id)
                    ->where("status", "aktivna")->where("end_time", ">=", now())->get(),
                "fines" => Fine::whereHas("vehicle", fn ($q) => $q->where("user_id", $user->id))
                    ->where("status", "!=", "placena")->count(),
                "permits" => ResidentPermit::where("user_id", $user->id)->count(),
            ]),
        };
    }
}
