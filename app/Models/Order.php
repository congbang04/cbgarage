<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const STATUS_RECEIVED = 'RECEIVED';
    const STATUS_QUOTED = 'QUOTED';
    const STATUS_IN_PROGRESS = 'IN_PROGRESS';
    const STATUS_WAITING_PART = 'WAITING_PART';
    const STATUS_COMPLETED = 'COMPLETED';
    const STATUS_PAID = 'PAID';
    const STATUS_CANCELLED = 'CANCELLED';

    public static function statuses(): array
    {
        return [
            self::STATUS_RECEIVED,
            self::STATUS_QUOTED,
            self::STATUS_IN_PROGRESS,
            self::STATUS_WAITING_PART,
            self::STATUS_COMPLETED,
            self::STATUS_PAID,
            self::STATUS_CANCELLED,
        ];
    }
    protected $fillable = [
        'fuel_range',
        'status',
        'total_amount',
        'description',
        'note',
        'user_id',
        'branch_id',
        'car_id',
        'customer_id',
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
    public function customer()
    {
        return $this->belongsTo(Customer::class);
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
