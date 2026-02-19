<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    protected $fillable = ['user_id', 'phone', 'specialization', 'is_actived'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function partRequests()
    {
        return $this->hasMany(PartRequest::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
