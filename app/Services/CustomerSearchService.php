<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Customer;
use App\Services\CarService;

class CustomerSearchService
{
    protected  $carService;

    public function __construct(CarService $carService)
    {
        $this->carService = $carService;
    }
    public function customerSearch(string $keyword)
    {
        // 1. Tìm trong users
        $customer = Customer::where('phone', $keyword)
            ->first();

        if ($customer) {
            $cars = $this->carService->getCarsByCustomer($customer->id);
            return [
                'type' => 'customer',
                'data' => $customer,
                'cars' => $cars,
                'name' => $customer->name,
                'phone' => $customer->phone,
            ];
        }

        // 2. Tìm trong booking guest
        $booking = Booking::where('contact_phone', $keyword)
            ->latest()
            ->first();

        if ($booking) {
            return [
                'type' => 'booking',
                'data' => $booking,
                'booking_id' => $booking->id,
                'name' => $booking->contact_name,
                'phone' => $booking->contact_phone,
            ];
        }

        return null;
    }
}
