<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'contact_name',
        'contact_phone',
        'start_time',
        'end_time',
        'lock_expires_at',
        'status',
        'description',
        'bay_id',
        'customer_id',
        'car_id'
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class, 'booking_services')
            ->withPivot('price');
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function bay()
    {
        return $this->belongsTo(Bay::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
    public static function getBookingsOnDate($date)
    {
        return self::whereDate('start_time', $date)
            ->where('status', 'CONFIRMED')
            ->get();
    }
}
