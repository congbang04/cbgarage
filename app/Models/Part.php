<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $fillable = ['sku', 'name', 'price_sell', 'unit'];

    public function orderDetails()
    {
        return $this->morphMany(OrderDetail::class, 'item');
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function details()
    {
        return $this->hasMany(PartRequestDetail::class);
    }
}
