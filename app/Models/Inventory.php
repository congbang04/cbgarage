<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = ['quantity', 'min_stock', 'branch_id', 'part_id'];

    public function part()
    {
        return $this->belongsTo(Part::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
