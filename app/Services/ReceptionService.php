<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\User;

class ReceptionService
{
    public function checkIn($data)
    {
        // 1 tìm booking
        $booking = Booking::find($data['booking_id']);

        // 2 tìm hoặc tạo car
        $car = $this->carService->findOrCreate($data);

        // 3 update odo
        $car->update([
            'odo' => $data['odo']
        ]);

        // 4 tạo order
        Order::create([
            'booking_id' => $booking?->id,
            'user_id' => $booking?->user_id,
            'customer_name' => $booking?->customer_name ?? $data['customer_name'],
            'customer_phone' => $booking?->customer_phone ?? $data['customer_phone'],
            'car_id' => $car->id,
            'advisor_id' => auth()->id(),
            'technician_id' => $data['technician_id'],
            'status' => 'check_in'
        ]);
    }
}
