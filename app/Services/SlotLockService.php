<?php

namespace App\Services;

use Illuminate\Support\Carbon;

class SlotLockService
{
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
