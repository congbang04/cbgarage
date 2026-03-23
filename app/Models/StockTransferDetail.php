<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransferDetail extends Model
{
    protected $fillable = ['stock_transfer_id', 'part_id', 'quantity'];
    public function stockTransfer()
    {
        return $this->belongsTo(StockTransfer::class, 'stock_transfer_id');
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }
}
