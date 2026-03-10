<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingLock extends Model
{
    protected $table = 'booking_locks';

    protected $fillable = [
        'bay_id',
        'customer_id',
        'start_time',
        'end_time',
        'expires_at',
        'session_id',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function bay()
    {
        return $this->belongsTo(Bay::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'user_id');
    }

    public function isExpired()
    {
        return now()->greaterThan($this->expires_at);
    }
    public static function getLocksOnDate($branchId, $date)
    {
        return self::whereHas('bay', function ($q) use ($branchId) {
            $q->where('branch_id', $branchId);
        })
            ->where('expires_at', '>', now())
            ->where('start_time', '<', $date->copy()->endOfDay())
            ->where('end_time', '>', $date->copy()->startOfDay())
            ->get();
    }
}
