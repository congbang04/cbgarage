<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = ['name', 'address', 'is_central_warehouse', 'phone', 'coordinates', 'is_actived'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function bays()
    {
        return $this->hasMany(Bay::class);
    }
    public function partRequest()
    {
        return $this->hasMany(PartRequest::class, 'request_id');
    }
}
