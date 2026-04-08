<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    const STATUS_PENDING = 'PENDING';
    const STATUS_CONFIRMED = 'CONFIRMED';
    const STATUS_CANCELED = 'CANCELED';

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_CONFIRMED,
            self::STATUS_CANCELED,
        ];
    }
    protected $fillable = [
        'contact_name',
        'contact_phone',
        'service_id',
        'start_time',
        'end_time',
        'status',
        'description',
        'bay_id',
        'customer_id',
        'car_id'
    ];
    public function service()
    {
        return $this->belongsTo(Service::class);
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
    public static function getBookingsOnDate($branchId, $date)
    {
        return self::whereHas('bay', function ($q) use ($branchId) {
            $q->where('branch_id', $branchId);
        })
            ->where('start_time', '<', $date->copy()->endOfDay())
            ->where('end_time', '>', $date->copy()->startOfDay())
            ->get();
    }
    public function scopeForCurrentAdmin($query)
    {
        // 1. Nếu chưa đăng nhập (tránh lỗi khi chạy command/job)
        if (!auth()->check()) {
            return $query;
        }

        $user = auth()->user();

        // 2. Nếu là Admin Tổng (super_admin) -> Trả về toàn bộ (không gắn where)
        if ($user->role === User::ROLE_BRANCH_ADMIN && $user->branch_id == 1) {
            return $query;
        }

        // 3. Nếu là các Role khác (Admin chi nhánh, Lễ tân...) -> Bắt buộc lọc theo nhánh
        if ($user->branch_id) {
            return $query->where('branch_id', $user->branch_id);
        }

        return $query;
    }
}
