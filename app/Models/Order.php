<?php

namespace App\Models;

use App\Models\User;
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

    /**
     * Scope lọc dữ liệu theo quyền của User đang đăng nhập
     */
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
    public function scopeForUser($query, $carId)
    {
        if (!auth()->check()) {
            return $query;
        }

        $user = auth()->user();

        if ($user->role === User::ROLE_CUSTOMER) {
            return $query->where('car_id', $carId);
        }

        return $query;
    }

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
