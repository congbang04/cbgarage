<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model
{
    const STATUS_PENDING = 'PENDING';
    const STATUS_COMPLETED = 'COMPLETED';
    const STATUS_IN_TRANSIT = 'IN_TRANSIT';
    const STATUS_CANCELED = 'CANCELED';


    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_COMPLETED,
            self::STATUS_IN_TRANSIT,
            self::STATUS_CANCELED,
        ];
    }
    protected $fillable = ['from_branch_id', 'to_branch_id', 'status', 'note', 'created_by'];

    public function fromBranch()
    {
        return $this->belongsTo(Branch::class, 'from_branch_id');
    }

    public function toBranch()
    {
        return $this->belongsTo(Branch::class, 'to_branch_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function parts()
    {
        return $this->belongsToMany(Part::class, 'stock_transfer_details', 'stock_transfer_id', 'part_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }
    public function details()
    {
        return $this->hasMany(StockTransferDetail::class, 'stock_transfer_id');
    }
}
