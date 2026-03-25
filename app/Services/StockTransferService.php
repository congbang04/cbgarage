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
                $q->where('from_branch_id', $userBranch);
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
     * Get stock transfers for current branch with optional filters
     */
    public function getTransferHistoriesForBranch()
    {
        $userBranch = auth()->user()->branch_id;

        return StockTransfer::query()
            ->where('to_branch_id', $userBranch)
            ->with([
                'fromBranch',
                'toBranch',
                'creator',
                'creator.branch',
                'details',
                'details.part'
            ])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();;
    }
    /**
     * Get all stock transfers for current branch with optional filters
     */
    public function getAllTransfersForBranch(array $filters = [])
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
        // Tìm kiếm theo tên, SKU
        if (!empty($filters['keyword'])) {
            $keyword = $filters['keyword'];
            $query->where('id', 'like', "%{$keyword}%");
        }
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

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }
    /**
     * Get part request by ID with all details
     */
    public function getStockTransferById(int $stockTransferId)
    {
        $userBranch = auth()->user()->branch_id;
        return StockTransfer::query()
            ->where('id', $stockTransferId)
            ->where(function ($query) use ($userBranch) {
                $query->where('from_branch_id', $userBranch)
                    ->orWhere('to_branch_id', $userBranch);
            })
            ->with([
                'fromBranch',
                'toBranch',
                'creator',
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
                'note' => $data['note'],
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
     * Complete stock transfer (Xác nhận nhận hàng tại kho đích)
     */
    public function completeTransfer(int $transferId): StockTransfer
    {
        return DB::transaction(function () use ($transferId) {
            $transfer = StockTransfer::with('details')->findOrFail($transferId);

            if ($transfer->status !== StockTransfer::STATUS_IN_TRANSIT) {
                throw new Exception('Chỉ có thể xác nhận nhận hàng khi phiếu đang trong quá trình vận chuyển.');
            }


            foreach ($transfer->details as $detail) {
                $qty = $detail->quantity;
                $partId = $detail->part_id;

                $destInventory = Inventory::firstOrCreate(
                    [
                        'part_id' => $partId,
                        'branch_id' => $transfer->to_branch_id,
                    ],
                    [
                        'quantity' => 0,
                        'min_stock' => 5,
                    ]
                );

                $destInventory->increment('quantity', $qty);
            }

            $transfer->update([
                'status' =>  StockTransfer::STATUS_COMPLETED,
                'updated_at' => now(),
            ]);

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
    /**
     * Issue goods for Stock Transfer
     */
    public function issueGoods(int $stockTransferId, array $data)
    {
        return DB::transaction(function () use ($stockTransferId, $data) {
            $transfer = StockTransfer::with('details')->findOrFail($stockTransferId);

            // Kiểm tra quyền
            if ($transfer->from_branch_id !== auth()->user()->branch_id) {
                abort(403, 'Bạn không có quyền xuất kho cho phiếu này.');
            }
            if ($transfer->status !== 'PENDING') {
                abort(400, 'Phiếu yêu cầu không ở trạng thái chờ duyệt.');
            }

            $actualQuantities = $data['actual_quantities'] ?? [];

            // 1. Trừ số lượng trong kho của chi nhánh xuất (Kho Nguồn)
            foreach ($transfer->details as $detail) {
                $actualQty = $actualQuantities[$detail->id] ?? 0;

                if ($actualQty <= 0) {
                    continue;
                }

                $this->deductInventory($detail->part_id, $actualQty, $transfer->from_branch_id);

                // (Tùy chọn) Lưu số lượng thực xuất vào bảng stock_transfer_details nếu database có cột này
                // $detail->update(['actual_quantity' => $actualQty]);
            }

            // 2. Chuyển trạng thái phiếu sang "Đang vận chuyển"
            $transfer->update([
                'status' => 'IN_TRANSIT',
                'updated_at' => now(),
            ]);

            // 3. (Tùy chọn) Kích hoạt event Realtime để frontend load lại bảng
            // broadcast(new StockTransferUpdated($transfer));

            return $transfer;
        });
    }

    /**
     * Deduct inventory quantity (Trừ kho giống hệt bên PartRequest)
     */
    protected function deductInventory(int $partId, int $quantity, int $branchId): void
    {
        $inventory = Inventory::where('part_id', $partId)
            ->where('branch_id', $branchId)
            ->lockForUpdate()
            ->first();

        if (!$inventory) {
            throw new Exception("Phụ tùng ID {$partId} không tồn tại trong kho.");
        }

        if ($inventory->quantity < $quantity) {
            throw new Exception("Kho không đủ phụ tùng. Còn {$inventory->quantity}, yêu cầu xuất {$quantity}.");
        }

        $inventory->decrement('quantity', $quantity);
    }
}
