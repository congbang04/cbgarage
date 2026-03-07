<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingLock extends Model
{
    protected $table = 'booking_locks';

    protected $fillable = [
        'branch_id',
        'bay_id',
        'customer_id',
        'start_time',
        'end_time',
        'expires_at'
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
    public static function getLocksOnDate($date)
    {
        return self::whereDate('start_time', $date)
            ->where('expires_at', '>', now())
            ->get();
    }
}
