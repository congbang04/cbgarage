<?php

namespace App\Services;

use App\Models\Order;
use App\Services\CarService;
use App\Services\CustomerService;
use Exception;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\throwException;

class ReceptionService
{
    protected $customerService, $carService;
    public function __construct(CustomerService $customerService, CarService $carService)
    {
        $this->customerService = $customerService;
        $this->carService = $carService;
    }
    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {

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
                'status' => 'RECEIVED',
                'total_amount' => 0
            ]);

            return $order;
        });
    }
    public function getOrders($perPage = 10)
    {
        return Order::select([
            'id',
            'customer_id',
            'car_id',
            'created_at'
        ])
            ->with([
                'customer:id,name,phone',

                'car:id,brand_id,car_model_id,license_plate,manufacture_year,color,current_odo,vin_number',

                'car.brand:id,name',

                'car.carModel:id,name'
            ])
            ->latest()
            ->paginate($perPage);
    }
}
