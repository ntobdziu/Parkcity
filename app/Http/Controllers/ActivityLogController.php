<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::with("user")->latest("timestamp")->paginate(30);

        return view("logs.index", compact("logs"));
    }
}
