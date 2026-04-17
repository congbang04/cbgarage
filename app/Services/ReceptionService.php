<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Labor;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Part;
use App\Models\Service;
use App\Models\Technician;
use App\Services\CarService;
use App\Services\CustomerService;
use App\Services\ServiceService;
use Exception;
use Illuminate\Support\Facades\DB;

class ReceptionService
{
    protected $customerService, $carService, $serviceService, $partService;
    public function __construct(
        CustomerService $customerService,
        CarService $carService,
        ServiceService $serviceService
    ) {
        $this->customerService = $customerService;
        $this->carService = $carService;
        $this->serviceService = $serviceService;
    }
    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            $status = Order::STATUS_RECEIVED;
            // 1. tạo hoặc lấy customer
            $customer = $this->customerService->createOrFind([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'address' => $data['address'] ?? null,
            ]);
            if (!$customer) throw new Exception('Lỗi thông tin khách hàng.', 410);
            // 2. tạo hoặc lấy car
            $car = $this->carService->createOrFind([
                'customer_id' => $customer->id,
                'license_plate' => $data['license_plate'],
                'brand_id' => $data['brand_id'],
                'car_model_id' => $data['car_model_id'],
                'vin_number' => $data['vin_number'],
                'manufacture_year' => $data['manufacture_year'],
                'color' => $data['color'],
                'current_odo' => $data['current_odo'],
            ]);
            if (!$car) throw new Exception('Lỗi thông tin xe', 410);
            // 3. tạo order
            $order = Order::create([
                'customer_id' => $customer->id,
                'booking_id' => $data['booking_id'],
                'car_id' => $car->id,
                'user_id' => auth()->id(),
                'branch_id' => auth()->user()->branch_id,
                'fuel_range' => $data['fuel_range'],
                'description' => $data['description'],
                'note' => $data['note'],
                'status' => $status,
                'total_amount' => 0
            ]);
            if (!is_null($data['booking_id'])) {
                $booking = Booking::find($data['booking_id']);
                if ($booking) {
                    $booking->update([
                        'status' => Booking::STATUS_CONFIRMED,
                    ]);
                }
            }
            return $order;
        });
    }
    public function searchOrders(array $filters, int $perPage = 10)
    {
        $query = Order::select([
            'id',
            'customer_id',
            'technician_id',
            'car_id',
            'branch_id',
            'status',
            'created_at'
        ])
            ->with([
                'customer:id,name,phone',
                'car:id,brand_id,car_model_id,license_plate',
                'car.brand:id,name',
                'car.carModel:id,name',
                'technician:id,name',
                'branch:id,name'
            ]);


        if (!empty($filters['keyword'])) {
            $keyword = $filters['keyword'];
            $query->where(function ($q) use ($keyword) {
                $q->whereHas('car', function ($carQuery) use ($keyword) {
                    $carQuery->where('license_plate', 'like', "%{$keyword}%");
                })
                    ->orWhereHas('customer', function ($customerQuery) use ($keyword) {
                        $customerQuery->where('name', 'like', "%{$keyword}%");
                    })
                    ->orWhereHas('customer', function ($customerQuery) use ($keyword) {
                        $customerQuery->where('phone', 'like', "%{$keyword}%");
                    });
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['date'])) {
            $query->whereDate('created_at', $filters['date']);
        }

        if (!empty($filters['branch_id'])) {
            $query->where('branch_id', $filters['branch_id']);
        }

        return $query->orderByDesc('updated_at')->paginate($perPage);
    }
    public function getOrdersByCar($carId, $perPage = 10)
    {
        return Order::select([
            'id',
            'customer_id',
            'technician_id',
            'car_id',
            'status',
            'created_at'
        ])
            ->with([
                'customer:id,name,phone',
                'car:id,brand_id,car_model_id,license_plate,manufacture_year,color,current_odo,vin_number',
                'car.brand:id,name',
                'car.carModel:id,name',
                'technician:id,name'
            ])
            ->forUser($carId)
            ->orderByDesc('updated_at')
            ->paginate($perPage);
    }
    /**
     * Get all services for quotation
     */
    public function getServices()
    {
        return Service::select('id', 'name', 'price')->orderBy('name')->get();
    }

    /**
     * Get all parts for quotation
     */
    public function getParts()
    {
        return Part::select('id', 'sku', 'name', 'price_sell', 'unit')
            ->with('suggestedLabors')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get all technicians for assignment (filtered by branch)
     */
    public function getTechnicians(int $branchId)
    {
        return Technician::query()
            ->whereHas('user', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->orderBy('name')
            ->get(['id', 'name', 'user_id']);
    }
    public function getLabors()
    {
        return Labor::select('id', 'sku', 'name', 'price')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }
    /**
     * Save quotation with order details
     * Lưu báo giá kèm theo chi tiết hạng mục
     */
    public function saveQuotation(Order $order, array $details, ?int $technicianId = null): Order
    {
        return DB::transaction(function () use ($order, $details, $technicianId) {
            $totalAmount = 0;
            $quoted = Order::STATUS_QUOTED;
            // Clear existing details
            $order->details()->delete();

            // Create order details and calculate total
            foreach ($details as $detail) {
                $quantity = $detail['quantity'] ?? 1;
                $price = $detail['price'] ?? 0;

                $lineTotal = ($quantity * $price);
                $totalAmount += $lineTotal;

                OrderDetail::create([
                    'order_id' => $order->id,
                    'item_type' => $detail['item_type'],
                    'item_id' => $detail['item_id'],
                    'quantity' => $quantity,
                    'price' => $price,
                    'status' => Order::STATUS_RECEIVED,
                ]);
            }

            // Update order
            $order->update([
                'total_amount' => $totalAmount,
                'technician_id' => $technicianId,
                'status' => $quoted,
            ]);

            return $order->fresh('details');
        });
    }

    /**
     * Get quotation by order ID with all details
     */
    public function getQuotation(int $orderId): ?Order
    {
        return Order::with([
            'details.item',
            'customer:id,name,phone,address',
            'car:id,brand_id,car_model_id,license_plate,manufacture_year,color',
            'car.brand:id,name',
            'car.carModel:id,name',
            'technician:id,name',
            'branch:id,name,address,phone'
        ])->find($orderId);
    }
    public function cancelOrder($orderId)
    {
        $order = Order::forCurrentAdmin()->findOrFail($orderId);

        if (in_array($order->status, [
            Order::STATUS_COMPLETED,
            Order::STATUS_PAID
        ])) {
            throw new Exception("Không thể hủy đơn 'ĐÃ HOÀN THÀNH' hoặc 'ĐÃ THANH TOÁN'.");
        }

        if ($order->status === Order::STATUS_CANCELED) {
            throw new Exception("Đơn này đã được hủy trước đó.");
        }

        $order->update([
            'status' => Order::STATUS_CANCELED
        ]);

        if ($order->booking) {
            $order->booking->update([
                'status' => Booking::STATUS_CANCELED
            ]);
        }

        return $order;
    }
}
