<?php

namespace App\Http\Controllers;

use App\Services\CustomerBookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CustomerBookingController extends Controller
{
    protected $customerBookingService;

    public function __construct(CustomerBookingService $customerBookingService)
    {
        $this->customerBookingService = $customerBookingService;
    }

    /**
     * Hiển thị view chính danh sách booking của customer
     */
    public function index()
    {
        return view('customer-booking.index');
    }

    /**
     * Trả về partial view (HTML table) để render bằng AJAX
     */
    public function tableList(Request $request)
    {
        $bookings = $this->customerBookingService->getCustomerBookings(
            startDate: $request->query('start_time') ? Carbon::parse($request->query('start_time')) : null,
            status: $request->query('status'),
        );

        return view('customer-booking.partials.table', compact('bookings'));
    }

    /**
     * Hủy lịch đặt
     */
    public function cancel($id)
    {
        try {
            $this->customerBookingService->cancel($id);
            return response()->json(['message' => 'Đã hủy lịch đặt thành công!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }
}
