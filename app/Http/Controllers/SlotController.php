<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Services\SlotService;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
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
}
