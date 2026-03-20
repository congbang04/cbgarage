<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartRequestDetail extends Model
{
    protected $fillable = [
        'request_id',
        'part_id',
        'quantity',
    ];

    public function partRequest()
    {
        return $this->belongsTo(PartRequest::class, 'request_id');
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }
}
