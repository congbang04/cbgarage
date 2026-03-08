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
}
