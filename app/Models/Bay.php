<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bay extends Model
{
    protected $fillable = ['name', 'status', 'type', 'branch_id'];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
