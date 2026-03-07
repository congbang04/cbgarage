<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingLock;
use App\Models\Service;
use Illuminate\Support\Carbon;
use App\Services\WorkingTimeService;
use App\Services\BranchService;
use App\Services\SlotLockService;

class SlotService
{
    private const TIME_SLOT_MINUTES = 20;
    protected $workingTimeService, $branchService, $slotLockService;
    public function __construct(WorkingTimeService $workingTimeService, BranchService $branchService, SlotLockService $slotLockService)
    {
        $this->workingTimeService = $workingTimeService;
        $this->branchService = $branchService;
        $this->slotLockService = $slotLockService;
    }

    public function getAvailableSlots($branchId, Carbon $date, Service $service)
    {
        $workingHours = $this->workingTimeService->getWorkingHoursForDate($date);
        if (!$workingHours) {
            return [];
        }
        $bookingsOnDate = Booking::getBookingsOnDate($date);
        $locksOnDate = BookingLock::getLocksOnDate($date);
        $potentialSlots = $this->generateSlots($workingHours['start'], $workingHours['end']);
        $availableSlots = [];
        $now = Carbon::now();
        $lunch = $workingHours['lunch'];
        if ($lunch && isset($lunch['start'], $lunch['end'])) {
            $lunchStart = $lunch['start'] instanceof Carbon ? $lunch['start'] : Carbon::parse($lunch['start']);
            $lunchEnd = $lunch['end'] instanceof Carbon ? $lunch['end'] : Carbon::parse($lunch['end']);
        }
        foreach ($potentialSlots as $slot) {
            $slotEnd = $slot->copy()->addMinutes($service->duration_minutes);
            $status = 'available';
            $expiresAt = null;
            if ($slotEnd->gt($workingHours['end'])) {
                continue;
            }
            if ($lunchStart && $lunchEnd && $slot->gte($lunchStart) && $slot->lt($lunchEnd)) {
                $status = 'lunch';
            } elseif ($slot->lt($now) || $slotEnd->lt($now)) {
                $status = 'past';
            } elseif ($this->isSlotUnavailable($branchId, $slot, $slotEnd, $bookingsOnDate, $lunch)) {
                $status = 'unavailable';
            } elseif (!$this->isSlotAvailable($branchId, $slot, $slotEnd, $bookingsOnDate, $locksOnDate)) {
                $status = 'locked';
                if ($lock = $this->slotLockService->getLockForSlot($slot, $slotEnd, $locksOnDate)) {
                    $expiresAt = $lock->expires_at;
                }
            }
            $availableSlots[] = [
                'time' => $slot->format('H:i'),
                'status' => $status,
                'expires_at' => $expiresAt,
            ];
        }
        return $availableSlots;
    }
    private function generateSlots(Carbon $startTime, Carbon $endTime,): array
    {
        $slots = [];
        $currentTime = $startTime->copy();
        $slotMinutes = self::TIME_SLOT_MINUTES;
        $currentTime->second(0);
        $remainder = $currentTime->minute % $slotMinutes;
        if ($remainder > 0) {
            $minutesToAdd = $slotMinutes - $remainder;
            $currentTime->addMinutes($minutesToAdd);
        }
        while ($currentTime->lt($endTime)) {
            $slots[] = $currentTime->copy();
            $currentTime->addMinutes(self::TIME_SLOT_MINUTES);
        }
        return $slots;
    }
    public function isSlotAvailable($branchId, Carbon $newBookingStart, Carbon $newBookingEnd, $existingBookings, $existingLocks): bool
    {
        $maxBays = $this->branchService->countByBranch($branchId);
        $concurrentBookings = 0;
        foreach ($existingBookings as $booking) {
            $bookingStart = Carbon::parse($booking->start_time);
            $bookingEnd   = Carbon::parse($booking->end_time);
            if ($newBookingStart->lt($bookingEnd) && $newBookingEnd->gt($bookingStart)) {
                $concurrentBookings++;
            }
        }
        foreach ($existingLocks as $lock) {
            if (Carbon::parse($lock->expires_at)->isPast()) {
                continue;
            }
            $lockStart = Carbon::parse($lock->start_time);
            $lockEnd   = Carbon::parse($lock->end_time);
            if ($newBookingStart->lt($lockEnd) && $newBookingEnd->gt($lockStart)) {
                $concurrentBookings++;
            }
        }
        if ($concurrentBookings >= $maxBays) {
            return false;
        }
        return true;
    }
    public function isSlotUnavailable($branchId, Carbon $newBookingStart, Carbon $newBookingEnd, $existingBookings, ?array $lunch = null): bool
    {
        $maxBays = $this->branchService->countByBranch($branchId);
        $concurrentBookings = 0;
        $lunchStart = null;
        $lunchEnd = null;
        if ($lunch && isset($lunch['start'], $lunch['end'])) {
            $lunchStart = $lunch['start'] instanceof Carbon ? $lunch['start'] : Carbon::parse($lunch['start']);
            $lunchEnd = $lunch['end'] instanceof Carbon ? $lunch['end'] : Carbon::parse($lunch['end']);
        }
        if ($lunchStart && $newBookingStart->lt($lunchEnd) &&  $newBookingEnd->gt($lunchStart)) {
            return true; // Slot này trùng giờ nghỉ, không khả dụng
        }
        foreach ($existingBookings as $booking) {

            if ($booking->status !== 'CONFIRMED') {
                continue;
            }
            $bookingStart = Carbon::parse($booking->start_time);
            $bookingEnd = Carbon::parse($booking->end_time);
            if ($newBookingStart->lt($bookingEnd) && $newBookingEnd->gt($bookingStart)) {
                $concurrentBookings++;
            }
        }
        if ($concurrentBookings >= $maxBays) {
            return true;
        }
        return false;
    }
}
