<?php

namespace App\Models;

use App\Models\Inventory;
use App\Models\Labor;
use App\Models\OrderDetail;
use App\Models\PartRequestDetail;
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
    public function suggestedLabors()
    {
        return $this->belongsToMany(Labor::class, 'part_labor', 'part_id', 'labor_id');
    }
}
