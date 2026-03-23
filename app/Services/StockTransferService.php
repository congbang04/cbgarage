<?php

namespace App\Services;

use App\Events\StockTransferUpdated;
use App\Models\Branch;
use App\Models\Inventory;
use App\Models\StockTransfer;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StockTransferService
{
    /**
     * Get stock transfers for current branch with optional filters
     */
    public function getTransfersForBranch(array $filters = [])
    {
        $userBranch = auth()->user()->branch_id;

        $query = StockTransfer::query()
            ->where(function ($q) use ($userBranch) {
                $q->where('from_branch_id', $userBranch)
                    ->orWhere('to_branch_id', $userBranch);
            })
            ->with([
                'fromBranch',
                'toBranch',
                'creator',
                'creator.branch',
                'details',
                'details.part'
            ]);

        // Filter by status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by date range
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->orderBy('created_at', 'desc')->paginate(8);
    }
    /**
     * Get part request by ID with all details
     */
    public function getStockTransferById(int $stockTransferId)
    {
        return StockTransfer::query()
            ->where('id', $stockTransferId)
            ->where('from_branch_id', auth()->user()->branch_id)
            ->with([
                'fromBranch',
                'toBranch',
                'creator',
                'creator.branch',
                'details',
                'details.part'
            ])
            ->first();
    }
    /**
     * Create stock transfer request
     */
    public function createTransfer(array $data): StockTransfer
    {
        return DB::transaction(function () use ($data) {
            $transfer = StockTransfer::create([
                'from_branch_id' => $data['from_branch_id'],
                'to_branch_id' => $data['to_branch_id'],
                'status' => StockTransfer::STATUS_PENDING,
                'created_by' => auth()->id(),
            ]);

            // Create pivot records for parts
            $pivotData = [];
            foreach ($data['items'] as $item) {
                $pivotData[$item['part_id']] = ['quantity' => $item['quantity']];
            }

            $transfer->parts()->sync($pivotData);

            // Validate stock availability
            $this->validateStockAvailability($transfer);

            broadcast(new StockTransferUpdated($transfer));

            return $transfer;
        });
    }

    /**
     * Validate stock is available for transfer
     */
    protected function validateStockAvailability(StockTransfer $transfer): void
    {
        foreach ($transfer->parts as $part) {
            $inventory = Inventory::where('part_id', $part->id)
                ->where('branch_id', $transfer->from_branch_id)
                ->first();

            $requiredQty = $part->pivot->quantity;

            if (!$inventory || $inventory->quantity < $requiredQty) {
                throw new Exception(
                    "Số lượng không đủ cho: {$part->name}. "
                        . "Yêu cầu: {$requiredQty}, Có: " . ($inventory->quantity ?? 0)
                );
            }
        }
    }

    /**
     * Complete stock transfer
     */
    public function completeTransfer(int $transferId): StockTransfer
    {
        return DB::transaction(function () use ($transferId) {
            $transfer = StockTransfer::findOrFail($transferId);

            if ($transfer->status === 'completed') {
                throw new Exception('Phiếu chuyển kho đã hoàn thành');
            }

            // Deduct from source
            foreach ($transfer->parts as $part) {
                $qty = $part->pivot->quantity;

                $sourceInventory = Inventory::where('part_id', $part->id)
                    ->where('branch_id', $transfer->from_branch_id)
                    ->firstOrFail();

                $sourceInventory->decrement('quantity', $qty);
            }

            // Add to destination
            foreach ($transfer->parts as $part) {
                $qty = $part->pivot->quantity;

                $destInventory = Inventory::firstOrCreate(
                    [
                        'part_id' => $part->id,
                        'branch_id' => $transfer->to_branch_id,
                    ],
                    [
                        'quantity' => 0,
                        'min_stock' => 5, // Default minimum stock
                    ]
                );

                $destInventory->increment('quantity', $qty);
            }

            $transfer->update(['status' => StockTransfer::STATUS_COMPLETED]);

            broadcast(new StockTransferUpdated($transfer));

            return $transfer;
        });
    }

    /**
     * Cancel stock transfer
     */
    public function cancelTransfer(int $transferId, ?string $reason = null): StockTransfer
    {
        $transfer = StockTransfer::findOrFail($transferId);

        if (in_array($transfer->status, [StockTransfer::STATUS_COMPLETED, StockTransfer::STATUS_CANCELED])) {
            throw new Exception('Không thể hủy phiếu chuyển kho này');
        }
        $transfer->update([
            'status' => StockTransfer::STATUS_CANCELED,
            'note' => $reason,
            'updated_at' => now(),
        ]);

        broadcast(new StockTransferUpdated($transfer));

        return $transfer;
    }
}
