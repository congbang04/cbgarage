<?php

namespace App\Http\Controllers;

use App\Events\SlotStatusUpdated;
use App\Http\Requests\CreateBookingLockRequest;
use App\Http\Requests\CreateBookingRequest;
use App\Services\BookingLockService;
use App\Services\BookingService;
use App\Services\BranchService;
use App\Services\CarService;
use App\Services\ServiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BookingController extends Controller
{
    protected $branchService, $serviceService, $carService, $bookingLockService, $bookingService;

    public function __construct(
        BranchService $branchService,
        ServiceService $serviceService,
        CarService $carService,
        BookingLockService $bookingLockService,
        BookingService $bookingService
    ) {
        $this->branchService = $branchService;
        $this->serviceService = $serviceService;
        $this->carService = $carService;
        $this->bookingLockService = $bookingLockService;
        $this->bookingService = $bookingService;
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

        $date = Carbon::parse($startTime)->toDateString();
        broadcast(new SlotStatusUpdated($date))->toOthers();

        return response()->json($lock, 201);
    }
    public function releaseLock(Request $request)
    {
        $lockId = $request->lock_id;
        $this->bookingLockService->releaseLock($lockId);
        return response()->json(['message' => 'Đã hoàn tác giữ chỗ. Khung giờ này hiện đã trống.'], 200);
    }
    public function createBooking(CreateBookingRequest $request)
    {
        $data = [
            'lock_id' => $request->lock_id,
            'service_id' => $request->service_id,
            'contact_name' => $request->contact_name,
            'contact_phone' => $request->contact_phone,
            'start_time' => $request->start_time,
            'car_id' => $request->car_id,
            'description' => $request->description,
        ];
        try {
            $booking = $this->bookingService->createBooking($data);

            $date = Carbon::parse($data['start_time'])->toDateString();
            broadcast(new SlotStatusUpdated($date))->toOthers();

            return response()->json($booking, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }
}
