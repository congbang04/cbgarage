<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Service;
use App\Services\SlotService;
use App\Services\WorkingTimeService;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SlotLockService
{
    protected $slotService;
    protected $workingTimeService;
    public function __construct(SlotService $slotService, WorkingTimeService $workingTimeService)
    {
        $this->slotService = $slotService;
        $this->workingTimeService = $workingTimeService;
    }
    public function lockSlot(array $data): Booking
    {
        $service = Service::findOrFail($data['service_id']);
        $startTime = Carbon::parse($data['start_time']);
        $endTime = $startTime->copy()->addMinutes($service->duration_minutes);

        return DB::transaction(function () use ($service, $startTime, $endTime) {
            // 1. Kiểm tra slot lần cuối và khóa lại để tránh tranh chấp
            $bookingsOnDate = Booking::getLockedBookingsOnDate($startTime);
            if (!$this->slotService->isSlotAvailable($startTime, $endTime, $bookingsOnDate)) {
                throw new Exception('Xin lỗi, khung giờ này vừa có người khác chọn.', 409); // 409 Conflict
            }
            // 3. Tìm kỹ thuật viên trống tại thời điểm xác nhận
            $availableTechnician = $this->findAvailableTechnician($startTime, $endTime);
            if (!$availableTechnician) {
                throw new Exception('Rất tiếc, không còn kỹ thuật viên trống cho khung giờ này.', 409);
            }
            // 2. Tạo bản ghi "nháp" với trạng thái PENDING
            $pendingBooking = Booking::create([
                'service_id' => $service->id,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'status' => 'PENDING',
                'lock_expires_at' => now()->addMinutes(2), // Hết hạn sau 2 phút
                'technician_id' => $availableTechnician->id,
                'customer_name' => null,
                'customer_phone' => null,
                'license_plate' => null,
                'issue_description' => null,
            ]);

            return $pendingBooking;
        });
    }
    public function getLockForSlot(Carbon $slotStart, Carbon $slotEnd, $existingLocks)
    {
        foreach ($existingLocks as $lock) {
            if (Carbon::parse($lock->expires_at)->isPast()) {
                continue;
            }
            $lockStart = Carbon::parse($lock->start_time);
            $lockEnd   = Carbon::parse($lock->end_time);
            if ($slotStart->lt($lockEnd) && $slotEnd->gt($lockStart)) {
                return $lock;
            }
        }
        return null;
    }
}
