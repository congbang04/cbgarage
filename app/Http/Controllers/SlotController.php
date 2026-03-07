<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Services\SlotService;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\CreateBookingRequest;
use App\Models\WorkingHour;

class SlotController extends Controller
{
    protected $slotService;
    public function __construct(SlotService $slotService)
    {
        $this->slotService = $slotService;
    }
    public function index(Request $request)
    {
        dd($request->all());
        $validator = Validator::make($request->all(), [
            'date' => 'required|date_format:Y-m-d',
            'service_id' => 'required|exists:services,id',
            'branch_id' => 'required|exists:branches,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $date = Carbon::parse($request->date)->startOfDay();
        $service = Service::findOrFail($request->service_id);
        $branchId = $request->branch_id;
        $availableSlots = [];

        $dayOfWeek = $date->dayOfWeek;
        $workingHour = WorkingHour::where('day_of_week', $dayOfWeek)->first();
        $dayActive = $workingHour ? $workingHour->active : false;
        $availableSlots = $this->slotService->getAvailableSlots($branchId, $date, $service);
        return view('customer.booking-partials.slots', [
            'available_slots' => $availableSlots,
            'dayOfWeek' => $dayOfWeek,
            'dayActive' => $dayActive,
        ]);
    }
    public function confirmedBooking(CreateBookingRequest $request)
    {
        $data = [
            'customer_name' => $request->input('customer_name'),
            'customer_phone' => $request->input('customer_phone'),
            'license_plate' => $request->input('license_plate'),
            'issue_description' => $request->input('issue_description'),
            'service_id' => $request->input('service_id'),
            'start_time' => $request->input('start_time'),
            'booking_id' => $request->input('booking_id')
        ];
        $date = $request->input('date');

        try {
            // Gọi service để xác nhận đặt lịch
            $confirmedBooking = $this->slotService->confirmBooking($data);

            // broadcast(new SlotStatusUpdated($date))->toOthers();

            // Nếu không có lỗi, trả về response thành công
            return response()->json([
                'message' => 'Đặt lịch thành công!',
                'booking' => $confirmedBooking,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage() // Lấy thông báo lỗi từ Exception
            ],  (int) $e->getCode() ?: 500); // Lấy mã lỗi, mặc định là 400
        }
    }
    public function storeLock(Request $request)
    {
        // Validate dữ liệu đầu vào cơ bản
        $validatedData = $request->validate([
            'service_id' => 'required|exists:services,id',
            'start_time' => 'required|date',
        ]);
        $date = $request->date;

        try {
            $pendingBooking = $this->slotService->lockSlot($validatedData);

            // broadcast(new SlotStatusUpdated($date))->toOthers();

            return response()->json([
                'message' => 'Slot đã được giữ tạm thời trong 10 phút.',
                'booking_id' => $pendingBooking->id, // Trả về ID để front-end sử dụng
                'expires_at' => $pendingBooking->lock_expires_at->toDateTimeString(),

            ], 201); // 201 Created

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], (int)$e->getCode() ?: 400);
        }
    }
}
