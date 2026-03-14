<?php

namespace App\Services;

use App\Models\Customer;

class CustomerService
{
    public function createOrFind(array $data)
    {
        return Customer::firstOrCreate(
            [
                'phone' => $data['phone']
            ],
            [
                'name' => $data['name'],
                'address' => $data['address'],
            ]
        );
    }
}
