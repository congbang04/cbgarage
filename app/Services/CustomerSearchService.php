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
        $customer = Customer::where('phone', $keyword)->first();

        $booking = Booking::where('contact_phone', $keyword)
            ->latest()
            ->first();

        // CASE 1: Có cả customer + booking
        if ($customer && $booking) {
            $cars = $this->carService->getCarsByCustomer($customer->id);

            return [
                'type' => 'customer_with_booking',
                'data' => $customer,
                'cars' => $cars,
                'booking_id' => $booking->id,
                'car_id' => $booking->car_id,
                'name' => $customer->name,
                'phone' => $customer->phone,
            ];
        }

        // CASE 2: Chỉ có customer
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

        // CASE 3: Chỉ có booking (guest chưa tạo order)
        if ($booking) {
            if ($booking->car_id) {
                $car = $this->carService->getCarsByCarId($booking->car_id);
            } else {
                $car = null;
            }
            return [
                'type' => 'booking',
                'data' => $booking,
                'car' => $car,
                'booking_id' => $booking->id,
                'car_id' => $booking->car_id,
                'name' => $booking->contact_name,
                'phone' => $booking->contact_phone,
            ];
        }

        return null;
    }
}
