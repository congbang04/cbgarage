<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'amount',
        'type',
        'payment_method',
        'transaction_code',
        'order_id',
        'reason',
        'notes'
    ];


    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
