<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id", "vehicle_id", "zone_id", "channel",
        "start_time", "end_time", "price", "status", "qr_code",
    ];

    protected function casts(): array
    {
        return [
            "start_time" => "datetime",
            "end_time" => "datetime",
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function isCurrentlyActive(): bool
    {
        return $this->status === "aktivna"
            && $this->start_time <= now()
            && $this->end_time >= now();
    }
}
