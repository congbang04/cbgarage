<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'status',
        'total_amount',
        'user_id',
        'branch_id',
        'car_id',
        'booking_id',
        'technician_id'
    ];

    // Các quan hệ belongsTo (Cực kỳ quan trọng để truy xuất thông tin đơn hàng)
    public function user()
    { // Người tạo đơn
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function partRequests()
    {
        return $this->hasMany(PartRequest::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
