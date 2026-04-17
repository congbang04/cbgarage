<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Labor extends Model
{
    protected $fillable = ['sku', 'name', 'price', 'description', 'is_active'];

    public function orderDetails()
    {
        return $this->morphMany(OrderDetail::class, 'item');
    }
}
