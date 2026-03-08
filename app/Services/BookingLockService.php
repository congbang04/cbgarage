<?php

namespace App\Services;

use App\Models\BookingLock;
use App\Services\BayService;
use App\Services\ServiceService;
use Illuminate\Support\Carbon;


class BookingLockService
{
    protected $bayService, $serviceService;
    public function __construct(BayService $bayService, ServiceService $serviceService)
    {
        $this->bayService = $bayService;
        $this->serviceService = $serviceService;
    }
    public function createLock($branchId, $serviceId, $customerId, $startTime)
    {
        $service = $this->serviceService->find($serviceId);
        $endTime = Carbon::parse($startTime)->addMinutes($service->estimated_duration);
        $bay = $this->bayService->findBayFirstAvailable($branchId, $startTime, $endTime);
        $bayId = $bay->id ?? null;
        if (!$bay) {
            throw new \Exception('Slot không còn bay trống');
        }
        $lock = BookingLock::create([
            'branch_id'   => $branchId,
            'bay_id'      => $bayId,
            'customer_id' => $customerId,
            'start_time'  => $startTime,
            'end_time'    => $endTime,
            'expires_at'  => Carbon::now()->addMinutes(5),
        ]);

        return $lock;
    }
}
