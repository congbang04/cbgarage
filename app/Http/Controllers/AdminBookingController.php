<?php

namespace App\Http\Controllers;

use App\Services\AdminBookingService;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    protected $adminBookingService;
    public function __construct(AdminBookingService $adminBookingService)
    {
        $this->adminBookingService = $adminBookingService;
    }
    public function index()
    {
        return view('admin.booking.booking');
    }
    public function tableList(Request $request)
    {
        $filters = $request->only(['keyword', 'status', 'date']);

        $bookings = $this->adminBookingService->searchBookings($filters);

        return view('admin.booking.partial-booking-table', compact('bookings'));
    }
}
