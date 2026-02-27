<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = ['customer_id', 'brand_id', 'car_model_id', 'license_plate', 'current_odo', 'vin_number'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function carModel()
    {
        return $this->belongsTo(CarModel::class);
    }
}
