<?php

namespace App\Services;

use App\Models\Bay;
use App\Models\Booking;

class BayService
{
    public function findBayFirstAvailable($branchId, $startTime, $endTime)
    {
        $bays = Bay::where('branch_id', $branchId)->get();

        foreach ($bays as $bay) {

            $exists = Booking::where('bay_id', $bay->id)
                ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED])
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->whereBetween('start_time', [$startTime, $endTime])
                        ->orWhereBetween('end_time', [$startTime, $endTime])
                        ->orWhere(function ($q) use ($startTime, $endTime) {
                            $q->where('start_time', '<=', $startTime)
                                ->where('end_time', '>=', $endTime);
                        });
                })
                ->exists();

            if (!$exists) {
                return $bay;
            }
        }

        return null;
    }
}
