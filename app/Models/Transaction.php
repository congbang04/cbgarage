<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public $timestamps = false;
    protected $fillable = ['amount', 'type', 'payment_method', 'transaction_code', 'order_id', 'created_at'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
