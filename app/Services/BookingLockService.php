<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingLock;
use App\Services\BayService;
use App\Services\ServiceService;
use App\Services\SlotService;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class BookingLockService
{
    protected $bayService, $serviceService, $slotService;
    public function __construct(BayService $bayService, ServiceService $serviceService, SlotService $slotService)
    {
        $this->bayService = $bayService;
        $this->serviceService = $serviceService;
        $this->slotService = $slotService;
    }
    public function createLock($branchId, $serviceId, $customerId, $startTime)
    {
        return DB::transaction(
            function () use ($branchId, $serviceId, $customerId, $startTime) {

                $service = $this->serviceService->find($serviceId);
                $endTime = Carbon::parse($startTime)->addMinutes($service->estimated_duration);
                $bay = $this->bayService->findBayFirstAvailable($branchId, $startTime, $endTime);
                $bayId = $bay->id ?? null;
                if (!$bay) {
                    throw new Exception('Slot không còn chổ trống');
                }
                $date = Carbon::parse($startTime);
                $bookingsOnDate = Booking::getBookingsOnDate($branchId, $date);
                $locksOnDate = BookingLock::getLocksOnDate($branchId, $date);
                if (!$this->slotService->isSlotAvailable(
                    $branchId,
                    Carbon::parse($startTime),
                    Carbon::parse($endTime),
                    $bookingsOnDate,
                    $locksOnDate
                )) {
                    throw new Exception('Xin lỗi, khung giờ này vừa có người khác chọn.', 409);
                }
                $lock = BookingLock::create([
                    'bay_id'      => $bayId,
                    'customer_id' => $customerId,
                    'start_time'  => $startTime,
                    'end_time'    => $endTime,
                    'expires_at'  => Carbon::now()->addMinutes(5),
                ]);

                return $lock;
            }
        );
    }
    public function releaseLock($lockId)
    {
        $lock = BookingLock::find($lockId);
        if ($lock) {
            $lock->delete();
        }
    }
}
