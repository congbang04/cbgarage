<?php

namespace App\Services;

use App\Events\PartRequestUpdated;
use App\Models\Inventory;
use App\Models\OrderDetail;
use App\Models\PartRequest;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PartRequestService
{
    /**
     * Get all pending part requests
     */
    public function getPendingRequests()
    {
        return PartRequest::query()
            ->where('branch_id', auth()->user()->branch_id)
            ->with([
                'order',
                'order.car',
                'order.car.brand:id,name',
                'order.car.carModel:id,name',
                'order.customer',
                'technician',
                'technician.user',
                'details',
                'details.part'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(8);
    }

    /**
     * Get part request by ID with all details
     */
    public function getPartRequestById(int $partRequestId): ?PartRequest
    {
        return PartRequest::query()
            ->where('id', $partRequestId)
            ->where('branch_id', auth()->user()->branch_id)
            ->with([
                'order',
                'order.car',
                'order.car.brand:id,name',
                'order.car.carModel:id,name',
                'order.customer',
                'technician',
                'technician.user',
                'details',
                'details.part'
            ])
            ->first();
    }

    /**
     * Issue goods from warehouse (Approve part request)
     * Creates inventory transactions and updates stock
     */
    public function issueGoods(int $partRequestId, array $data): array
    {
        return DB::transaction(function () use ($partRequestId, $data) {
            $partRequest = $this->getPartRequestById($partRequestId);

            if (!$partRequest) {
                abort(404, 'Yêu cầu không tồn tại');
            }
            if ($partRequest->branch_id !== auth()->user()->branch_id) {
                abort(403, 'Bạn không có quyền xử lý yêu cầu này');
            }
            if ($partRequest->status !== PartRequest::STATUS_PENDING) {
                abort(400, 'Yêu cầu đã được xử lý rồi');
            }

            $actualQuantities = $data['actual_quantities'] ?? [];
            $notes = $data['notes'] ?? null;
            // Validate sufficient stock and deduct from inventory
            foreach ($partRequest->details as $detail) {
                $actualQty = $actualQuantities[$detail->id] ?? 0;

                if ($actualQty <= 0) {
                    continue; // Skip zero quantities
                }
                $this->deductInventory($detail->part_id, $actualQty, $partRequest->branch_id);
            }
            $this->createOrderDetailsFromPartRequest($partRequest, $actualQuantities);
            // Update part request status
            $partRequest->update([
                'status' => PartRequest::STATUS_COMPLETED,
                'updated_at' => now(),
            ]);

            // Log the goods issue in a summary format
            $goodsIssue = [
                'id' => $partRequest->id,
                'request_id' => $partRequest->id,
                'issued_by_user_id' => auth()->id(),
                'issued_at' => now(),
                'note' => $notes,
            ];

            // Broadcast event for real-time update
            broadcast(new PartRequestUpdated($partRequest));

            return $goodsIssue;
        });
    }

    /**
     * Reject part request
     */
    public function rejectRequest(int $partRequestId, ?string $reason = null): PartRequest
    {
        $partRequest = PartRequest::findOrFail($partRequestId);

        $partRequest->update([
            'status' => PartRequest::STATUS_REJECTED,
            'note' => $reason ?? $partRequest->note,
            'updated_at' => now(),
        ]);

        broadcast(new PartRequestUpdated($partRequest));

        return $partRequest;
    }

    /**
     * Deduct inventory quantity
     */
    protected function deductInventory(int $partId, int $quantity, int $branchId): void
    {
        if (!$branchId) {
            throw new Exception('Lỗi không tìm thấy chi nhánh, chi nhánh không tồn tại');
        }

        $inventory = Inventory::where('part_id', $partId)
            ->where('branch_id', $branchId)
            ->lockForUpdate()
            ->first();

        if (!$inventory) {
            throw new Exception("Phụ tùng {$partId} không tồn tại trong kho");
        }

        if ($inventory->quantity < $quantity) {
            throw new Exception("Số lượng không đủ cho phụ tùng {$inventory->part->name}. Còn {$inventory->quantity}, yêu cầu {$quantity}");
        }

        $inventory->decrement('quantity', $quantity);
    }

    /**
     * Create or Update OrderDetail (Quotation)
     */
    protected function createOrderDetailsFromPartRequest(PartRequest $partRequest, array $actualQuantities = []): void
    {
        logger('createOrderDetailsFromPartRequest RUN', [
            'request_id' => $partRequest->id,
            'time' => now()
        ]);
        $order = $partRequest->order;
        foreach ($partRequest->details as $detail) {
            $actualQty = $actualQuantities[$detail->id] ?? 0;

            if ($actualQty <= 0) {
                continue;
            }

            $part = $detail->part;

            // 🔑 check đã tồn tại chưa (tránh duplicate)
            $existingDetail = $order->details()
                ->where('item_type', \App\Models\Part::class)
                ->where('item_id', $part->id)
                ->first();

            if ($existingDetail) {
                // ✅ đã có → cộng thêm số lượng
                $existingDetail->increment('quantity', $actualQty);
            } else {
                // 🆕 chưa có → tạo mới
                OrderDetail::create([
                    'order_id' => $order->id,
                    'item_type' => \App\Models\Part::class,
                    'item_id' => $part->id,
                    'quantity' => $actualQty,
                    'price' => $part->price_sell, // ⚠️ lấy từ DB, không dùng frontend
                    'discount' => 0,
                    'status' => PartRequest::STATUS_PENDING,
                ]);
            }
        }
    }
}
