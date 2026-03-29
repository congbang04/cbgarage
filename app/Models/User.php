<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    const ROLE_BRANCH_ADMIN = 'BRANCH_ADMIN';
    const ROLE_TECHNICIAN = 'TECHNICIAN';
    const ROLE_CUSTOMER = 'CUSTOMER';
    const ROLE_ADVISOR = 'ADVISOR';
    const ROLE_WAREHOUSE = 'WAREHOUSE';
    const ROLE_CASHIER = 'CASHIER';

    public static function statuses(): array
    {
        return [
            self::ROLE_BRANCH_ADMIN,
            self::ROLE_TECHNICIAN,
            self::ROLE_CUSTOMER,
            self::ROLE_ADVISOR,
            self::ROLE_WAREHOUSE,
            self::ROLE_CASHIER,
        ];
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['email', 'password', 'role', 'is_actived', 'branch_id', 'is_deleted'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function technician()
    {
        return $this->hasOne(Technician::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // public function stockTransfers()
    // {
    //     return $this->hasMany(StockTransfer::class, 'created_by');
    // }

    // public function issuedPartRequests()
    // {
    //     return $this->belongsToMany(PartRequest::class, 'goods_issues', 'user_id', 'part_request_id')
    //         ->withPivot('created_at');
    // }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
