<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DayOff extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'reason'];

    protected $casts = [
        'date' => 'date',
    ];
}
