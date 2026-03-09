<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingLock;
use App\Services\ServiceService;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BookingService
{
    protected $serviceService;
    public function __construct(
        ServiceService $serviceService
    ) {
        $this->serviceService = $serviceService;
    }
    public function createBooking(array $data): Booking
    {
        return DB::transaction(function () use ($data) {
            $lock = BookingLock::find($data['lock_id']);
            $service = $this->serviceService->find($data['service_id']);

            $contactName = $data['contact_name'];
            $contactPhone = $data['contact_phone'];
            $startTime = Carbon::parse($data['start_time']);
            $endTime = Carbon::parse($data['start_time'])->addMinutes($service->estimated_duration);
            $description = $data['description'] ?? null;
            $bayId = $lock->bay_id;
            $customerId = $lock->customer_id;
            $carId = $data['car_id'];

            if (!$lock || Carbon::parse($lock->expires_at)->isPast()) {
                if ($lock) {
                    $lock->delete();
                }
                throw new Exception('Phiên đặt lịch của bạn đã hết hạn. Vui lòng thử lại.', 410);
            }

            $booking = Booking::create([
                'contact_name' => $contactName,
                'contact_phone' => $contactPhone,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'status' => 'PENDING',
                'description' => $description,
                'bay_id' => $bayId,
                'customer_id' => $customerId,
                'car_id' => $carId,
            ]);
            $lock->delete();

            return $booking;
        });
    }
}
