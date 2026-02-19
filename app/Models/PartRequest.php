<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartRequest extends Model
{
    public $timestamps = false;
    protected $fillable = ['status', 'note', 'order_id', 'technician_id', 'created_at'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }

    public function issuedByUsers()
    {
        return $this->belongsToMany(User::class, 'goods_issues', 'part_request_id', 'user_id')
            ->withPivot('created_at');
    }

    public function parts()
    {
        return $this->belongsToMany(Part::class, 'part_request_details')
            ->withPivot('quantity');
    }
}
