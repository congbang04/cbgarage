<?php

namespace App\Services;

use App\Events\PartRequestUpdated;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PartRequest;
use App\Models\PartRequestDetail;
use Exception;
use Illuminate\Support\Facades\DB;

class TechnicianJobService
{
    /**
     * Search assigned orders by keyword, status, date
     */
    public function searchOrders(array $filters, $perPage = 10)
    {
        $technician = auth()->user()->technician;
        if (!$technician) {
            return collect();
        }

        $query = Order::with([
            'customer:id,name,phone',
            'car:id,brand_id,car_model_id,license_plate,manufacture_year,color',
            'car.brand:id,name',
            'car.carModel:id,name',
            'details:id,order_id,item_type,item_id,quantity,price,discount,status',
            'details.item',
        ])
            ->where('technician_id', $technician->id)
            ->where('branch_id', auth()->user()->branch_id);

        // Filter by keyword: customer name, phone, license plate
        if (!empty($filters['keyword'])) {
            $keyword = $filters['keyword'];

            $query->where(function ($q) use ($keyword) {
                $q->whereHas('customer', function ($q2) use ($keyword) {
                    $q2->where('name', 'like', "%{$keyword}%")
                        ->orWhere('phone', 'like', "%{$keyword}%");
                })
                    ->orWhereHas('car', function ($q2) use ($keyword) {
                        $q2->where('license_plate', 'like', "%{$keyword}%");
                    });
            });
        }

        // Filter by status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by date
        if (!empty($filters['date'])) {
            $query->whereDate('created_at', $filters['date']);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Get detailed order information for execution page
     */
    public function getOrderDetails(int $orderId)
    {
        $technician = auth()->user()->technician;
        if (!$technician) {
            throw new Exception('Không tìm thấy thông tin kỹ thuật viên', 403);
        }

        $order = Order::with([
            'partRequests' => function ($q) {
                $q->where('status', PartRequest::STATUS_PENDING);
            },
            'customer:id,name,phone,address',
            'car:id,brand_id,car_model_id,license_plate,manufacture_year,color,current_odo,vin_number',
            'car.brand:id,name',
            'car.carModel:id,name',
            'details:id,order_id,item_type,item_id,quantity,price,discount,status',
            'details.item',
            'partRequests:id,order_id,technician_id,status,created_at',
            'partRequests.parts:id,sku,name,price_sell'
        ])
            ->where('id', $orderId)
            ->where('technician_id', $technician->id)
            ->where('branch_id', auth()->user()->branch_id)
            ->firstOrFail();

        return $order;
    }

    /**
     * Update order status and technical notes
     */
    public function updateOrderStatus(int $orderId, array $data)
    {
        $technician = auth()->user()->technician;
        if (!$technician) {
            throw new Exception('Không tìm thấy thông tin kỹ thuật viên', 403);
        }

        $order = Order::where('id', $orderId)
            ->where('technician_id', $technician->id)
            ->where('branch_id', auth()->user()->branch_id)
            ->firstOrFail();

        // Validate status transition
        $validStatuses = [
            Order::STATUS_IN_PROGRESS,
            Order::STATUS_WAITING_PART,
            Order::STATUS_COMPLETED
        ];

        if (!in_array($data['status'], $validStatuses)) {
            throw new Exception('Trạng thái không hợp lệ', 400);
        }

        $order->update([
            'status' => $data['status'],
            'note' => $data['note'] ?? $order->note
        ]);

        return $order;
    }

    /**
     * Mark services/items in order as completed
     */
    public function completeOrderItem(int $orderId, int $detailId)
    {
        $technician = auth()->user()->technician;
        if (!$technician) {
            throw new Exception('Không tìm thấy thông tin kỹ thuật viên', 403);
        }

        $order = Order::where('id', $orderId)
            ->where('technician_id', $technician->id)
            ->where('branch_id', auth()->user()->branch_id)
            ->firstOrFail();

        $detail = OrderDetail::where('id', $detailId)
            ->where('order_id', $orderId)
            ->firstOrFail();

        // Store completion info (you might want to add is_completed column)
        // For now, this is a placeholder for future implementation
        return $detail;
    }

    /**
     * Request additional parts for the order
     */
    public function requestPart($orderId, array $data)
    {
        $technician = auth()->user()->technician;
        if (!$technician) {
            throw new Exception('Không tìm thấy thông tin kỹ thuật viên', 403);
        }
        $order = Order::where('id', $orderId)
            ->where('technician_id', $technician->id)
            ->where('branch_id', auth()->user()->branch_id)
            ->firstOrFail();

        return DB::transaction(function () use ($order, $data, $technician) {
            // Check if multiple parts provided (batch) or single part (legacy)
            $parts = isset($data['parts']) ? $data['parts'] : [['part_id' => $data['part_id'], 'quantity' => $data['quantity']]];

            // Create part request
            $partRequest = PartRequest::firstOrCreate([
                'order_id' => $order->id,
                'technician_id' => $technician->id,
                'branch_id' => auth()->user()->branch_id,
                'status' => PartRequest::STATUS_PENDING
            ]);

            $attachedParts = [];
            foreach ($parts as $partData) {
                $existing = PartRequestDetail::where('request_id', $partRequest->id)
                    ->where('part_id', $partData['part_id'])
                    ->first();

                if ($existing) {
                    $existing = PartRequestDetail::where('request_id', $partRequest->id)
                        ->where('part_id', $partData['part_id'])
                        ->increment('quantity', $partData['quantity']);
                    $attachedParts[] = $existing;
                } else {
                    $partRequestDetail = PartRequestDetail::create([
                        'request_id' => $partRequest->id,
                        'part_id' => $partData['part_id'],
                        'quantity' => $partData['quantity']
                    ]);
                    $attachedParts[] = $partRequestDetail;
                }
            }
            broadcast(new PartRequestUpdated($partRequest));
            return [
                'request' => $partRequest->load(['details']),
                'details' => $attachedParts
            ];
        });
    }

    /**
     * Get statistics for technician dashboard
     */
    public function getStats()
    {
        $technician = auth()->user()->technician;
        if (!$technician) {
            return [];
        }

        return [
            'total_assigned' => Order::where('technician_id', $technician->id)
                ->where('branch_id', auth()->user()->branch_id)
                ->count(),
            'in_progress' => Order::where('technician_id', $technician->id)
                ->where('branch_id', auth()->user()->branch_id)
                ->where('status', Order::STATUS_IN_PROGRESS)
                ->count(),
            'waiting_part' => Order::where('technician_id', $technician->id)
                ->where('branch_id', auth()->user()->branch_id)
                ->where('status', Order::STATUS_WAITING_PART)
                ->count(),
            'completed' => Order::where('technician_id', $technician->id)
                ->where('branch_id', auth()->user()->branch_id)
                ->where('status', Order::STATUS_COMPLETED)
                ->count(),
        ];
    }
}
