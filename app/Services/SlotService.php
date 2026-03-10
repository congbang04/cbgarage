<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingLock;
use App\Models\Service;
use App\Services\BranchService;
use App\Services\SlotLockService;
use App\Services\WorkingTimeService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class SlotService
{
    private const TIME_SLOT_MINUTES = 20;
    protected $workingTimeService, $branchService, $slotLockService;
    public function __construct(
        WorkingTimeService $workingTimeService,
        BranchService $branchService,
        SlotLockService $slotLockService,
    ) {
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
        $bookingsOnDate = Booking::getBookingsOnDate($branchId, $date);
        $locksOnDate = BookingLock::getLocksOnDate($branchId, $date);
        $lunch = $workingHours['lunch'];

        $currentSession = $this->bookingSessionId();
        logger('SESSION', [
            'laravel_session' => session()->getId(),
            'booking_session_id' => $currentSession
        ]);
        $potentialSlots = $this->generateSlots($workingHours['start'], $workingHours['end'], $lunch);
        $availableSlots = [];
        $now = Carbon::now();
        foreach ($potentialSlots as $slot) {
            $slotEnd = $slot->copy()->addMinutes($service->estimated_duration);
            $status = 'available';
            $expiresAt = null;
            $lock = $this->slotLockService->getLockForSlot($slot, $slotEnd, $locksOnDate);
            $myLock = $locksOnDate->first(function ($lock) use ($slot, $slotEnd, $currentSession) {
                return $lock->session_id === $currentSession &&
                    Carbon::parse($lock->start_time)->eq($slot); // Khớp chính xác giờ bắt đầu
            });
            $isFull = $this->isSlotUnavailable(
                $branchId,
                $slot,
                $slotEnd,
                $bookingsOnDate,
                $lunch,
            );
            if ($slotEnd->gt($workingHours['end'])) {
                continue;
            }

            if ($myLock) {
                $status = 'locked_by_you';
            } elseif ($isFull) {
                $status = 'unavailable';
            } elseif (!$this->isSlotAvailable(
                $branchId,
                $slot,
                $slotEnd,
                $bookingsOnDate,
                $locksOnDate
            )) {
                $status = 'locked';
                if ($lock) {
                    $expiresAt = $lock->expires_at;
                }
            }

            if ($slot->lt($now) || $slotEnd->lt($now)) {
                $status = 'past';
            }

            $availableSlots[] = [
                'time' => $slot->format('H:i'),
                'status' => $status,
                'expires_at' => $expiresAt,
            ];
        }
        return $availableSlots;
    }
    private function generateSlots(Carbon $startTime, Carbon $endTime, $lunch): array
    {
        $slots = [];
        $currentTime = $startTime->copy();
        $slotMinutes = self::TIME_SLOT_MINUTES;
        $lunchStart = $lunch['start'] ?? null;
        $lunchEnd = $lunch['end'] ?? null;
        $currentTime->second(0);
        $remainder = $currentTime->minute % $slotMinutes;
        if ($remainder > 0) {
            $minutesToAdd = $slotMinutes - $remainder;
            $currentTime->addMinutes($minutesToAdd);
        }
        while ($currentTime->lt($endTime)) {
            if ($lunchStart && $currentTime >= $lunchStart && $currentTime < $lunchEnd) {
                $currentTime = $lunchEnd->copy();
                continue;
            }

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
    public function isSlotUnavailable(
        $branchId,
        Carbon $newBookingStart,
        Carbon $newBookingEnd,
        $existingBookings,
        ?array $lunch = null,
    ): bool {
        if ($lunch && isset($lunch['start'], $lunch['end'])) {
            $lunchStart = Carbon::parse($lunch['start']);
            $lunchEnd   = Carbon::parse($lunch['end']);

            if ($newBookingStart->lt($lunchEnd) && $newBookingEnd->gt($lunchStart)) {
                return true;
            }
        }

        $maxBays = $this->branchService->countByBranch($branchId);
        $concurrent = 0;

        foreach ($existingBookings as $booking) {

            $start = Carbon::parse($booking->start_time);
            $end = Carbon::parse($booking->end_time);

            if ($newBookingStart->lt($end) && $newBookingEnd->gt($start)) {
                $concurrent++;
            }
        }
        return $concurrent >= $maxBays;
    }
    public function bookingSessionId()
    {
        if (!session()->has('booking_session_id')) {
            session(['booking_session_id' => (string) Str::uuid()]);
        }

        return session('booking_session_id');
    }
}
