<?php

namespace App\Services;

use App\Models\Car;

class CarService
{
    public function getCarsByCustomer($customerId)
    {
        return Car::with(['brand', 'carModel'])
            ->where('customer_id', $customerId)
            ->orderBy('created_at', 'desc')
            ->get();
    }
    public function getCarsByCarId($carId)
    {
        return Car::with(['brand', 'carModel'])
            ->where('id', $carId)
            ->first();
    }
    public function create(array $data): Car
    {
        return Car::create([
            'customer_id' => $data['customer_id'],
            'brand_id' => $data['brand_id'],
            'car_model_id' => $data['car_model_id'],
            'license_plate' => $data['license_plate'],
            'manufacture_year' => $data['manufacture_year'],
            'color' => $data['color'],
            'current_odo' => $data['current_odo'],
            'vin_number' => $data['vin_number'],
        ]);
    }
    public function createOrFind(array $data): Car
    {
        return Car::firstOrCreate(
            ['license_plate' => $data['license_plate']],
            [
                'customer_id' => $data['customer_id'],
                'brand_id' => $data['brand_id'],
                'car_model_id' => $data['car_model_id'],
                'manufacture_year' => $data['manufacture_year'],
                'color' => $data['color'],
                'current_odo' => $data['current_odo'],
                'vin_number' => $data['vin_number'],
            ]
        );
    }
}
