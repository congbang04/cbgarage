<?php

namespace App\Services;

use App\Models\Booking;

class AdminBookingService
{
    public function searchBookings(array $filters, int $perPage = 10)
    {
        $query = Booking::select([
            'id',
            'contact_name',
            'contact_phone',
            'customer_id',
            'service_id',
            'start_time',
            'end_time',
            'bay_id',
            'car_id',
            'status',
            'created_at'
        ])
            ->with([
                'customer:id,name,phone',
                'car:id,license_plate',
                'service:id,name',
                'bay:id,name'
            ])
            ->forCurrentAdmin();

        if (!empty($filters['keyword'])) {
            $keyword = trim($filters['keyword']);

            if (is_numeric($keyword)) {
                $query->where('id', $keyword);
            } else {
                $query->where(function ($q) use ($keyword) {
                    $q->orWhere('contact_name', 'like', "%{$keyword}%")
                        ->orWhere('contact_phone', 'like', "%{$keyword}%")

                        ->orWhereHas('car', function ($car) use ($keyword) {
                            $car->where('license_plate', 'like', "%{$keyword}%");
                        })

                        ->orWhereHas('customer', function ($customer) use ($keyword) {
                            $customer->where(function ($sub) use ($keyword) {
                                $sub->where('name', 'like', "%{$keyword}%")
                                    ->orWhere('phone', 'like', "%{$keyword}%");
                            });
                        });
                });
            }
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }


        if (!empty($filters['date'])) {
            $query->whereDate('start_time', $filters['date']);
        }

        return $query->orderByDesc('created_at')->paginate($perPage);
    }
}
