<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBookingLockRequest;
use App\Services\BookingLockService;
use App\Services\BranchService;
use App\Services\CarService;
use App\Services\ServiceService;

class BookingController extends Controller
{
    protected $branchService, $serviceService, $carService, $bookingLockService;

    public function __construct(BranchService $branchService, ServiceService $serviceService, CarService $carService, BookingLockService $bookingLockService)
    {
        $this->branchService = $branchService;
        $this->serviceService = $serviceService;
        $this->carService = $carService;
        $this->bookingLockService = $bookingLockService;
    }

    public function index()
    {
        $branches = $this->branchService->getActiveBranches();
        $services = $this->serviceService->getAll();
        $cars = $this->carService->getCarsByCustomer(auth()->id());
        return view('customer.booking', compact('branches', 'services', 'cars'));
    }
    public function createLock(CreateBookingLockRequest $request)
    {
        $branchId = $request->branch_id;
        $serviceId = $request->service_id;
        $customerId = auth()->id() ?? null;
        $startTime = $request->start_time;

        $lock = $this->bookingLockService->createLock($branchId, $serviceId, $customerId, $startTime);
        return response()->json($lock, 201);
    }
}
