<?php

namespace App\Services;

use App\Models\PartRequest;
use App\Models\PartRequestDetail;
use App\Models\Inventory;
use App\Models\Part;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PartRequestService
{
    /**
     * Get all pending part requests
     */
    public function getPendingRequests(): Collection
    {
        return PartRequest::query()
            ->where('status', 'pending')
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
            ->get();
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
            if ($partRequest->status !== 'pending') {
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

            // Update part request status
            $partRequest->update([
                'status' => 'completed',
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
            broadcast(new \App\Events\PartRequestUpdated($partRequest));

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
            'status' => 'rejected',
            'note' => $reason ?? $partRequest->note,
            'updated_at' => now(),
        ]);

        broadcast(new \App\Events\PartRequestUpdated($partRequest));

        return $partRequest;
    }

    /**
     * Deduct inventory quantity
     */
    protected function deductInventory(int $partId, int $quantity, int $branchId): void
    {
        if (!$branchId) {
            throw new \Exception('Lỗi không tìm thấy chi nhánh, chi nhánh không tồn tại');
        }

        $inventory = Inventory::where('part_id', $partId)
            ->where('branch_id', $branchId)
            ->lockForUpdate()
            ->first();

        if (!$inventory) {
            throw new \Exception("Phụ tùng {$partId} không tồn tại trong kho");
        }

        if ($inventory->quantity < $quantity) {
            throw new \Exception("Số lượng không đủ cho phụ tùng {$inventory->part->name}. Còn {$inventory->quantity}, yêu cầu {$quantity}");
        }

        $inventory->decrement('quantity', $quantity);
    }

    // /**
    //  * Create part request (called by technician)
    //  */
    // public function createPartRequest(array $data): PartRequest
    // {
    //     return DB::transaction(function () use ($data) {
    //         $partRequest = PartRequest::create([
    //             'order_id' => $data['order_id'],
    //             'technician_id' => $data['technician_id'] ?? auth()->id(),
    //             'branch_id' => auth()->user()->branch_id,
    //             'status' => 'pending',
    //             'note' => $data['note'] ?? null,
    //             'created_at' => now(),
    //         ]);

    //         // Store part request details
    //         foreach ($data['parts'] as $partData) {
    //             PartRequestDetail::create([
    //                 'request_id' => $partRequest->id,
    //                 'part_id' => $partData['part_id'],
    //                 'quantity' => $partData['quantity'],
    //             ]);
    //         }

    //         broadcast(new \App\Events\PartRequestUpdated($partRequest));

    //         return $partRequest;
    //     });
    // }
}
