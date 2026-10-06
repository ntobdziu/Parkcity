<?php

namespace App\Support;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log(string $action): void
    {
        if (! Auth::check()) {
            return;
        }

        ActivityLog::create([
            "user_id" => Auth::id(),
            "action" => $action,
            "timestamp" => now(),
        ]);
    }
}
