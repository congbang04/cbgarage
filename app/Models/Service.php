<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable =
    [
        'name',
        'icon',
        'price',
        'estimated_duration',
        'description'
    ];

    public function orderDetails()
    {
        return $this->morphMany(OrderDetail::class, 'item');
    }

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_services');
    }
}
