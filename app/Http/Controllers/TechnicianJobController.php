<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateTechnicianJobRequest;
use App\Services\TechnicianJobService;
use Illuminate\Http\Request;

class TechnicianJobController extends Controller
{
    protected TechnicianJobService $jobService;

    public function __construct(TechnicianJobService $jobService)
    {
        $this->jobService = $jobService;
    }

    /**
     * Display technician's job list page
     * Authorization: FormRequest validates technician role
     */
    public function jobs()
    {
        $stats = $this->jobService->getStats();
        return view('admin.technician.jobs', compact('stats'));
    }

    /**
     * AJAX endpoint: Get assigned orders table
     * Authorization: Service checks technician scope
     */
    public function tableList(Request $request)
    {
        $filters = $request->only(['keyword', 'status', 'date']);

        if (!empty(array_filter($filters))) {
            $orders = $this->jobService->searchOrders($filters);
        } else {
            $orders = $this->jobService->getAssignedOrders();
        }
        return view('admin.technician.partials.jobs-table-list', compact('orders'));
    }

    /**
     * Display execution page for a specific job
     * Authorization: Service verifies order belongs to technician
     */
    public function execution(int $orderId)
    {
        $order = $this->jobService->getOrderDetails($orderId);
        return view('admin.technician.execution', compact('order'));
    }

    /**
     * AJAX endpoint: Update order status and notes
     * Authorization: FormRequest validates technician role
     */
    public function updateStatus(UpdateTechnicianJobRequest $request, int $orderId)
    {
        try {
            $order = $this->jobService->updateOrderStatus($orderId, $request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công',
                'data' => $order,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * AJAX endpoint: Complete work item (service)
     * Authorization: Service verifies technician ownership
     */
    public function completeItem(Request $request, int $orderId)
    {
        try {
            $request->validate([
                'detail_id' => 'required|integer|exists:order_details,id',
            ]);

            $detail = $this->jobService->completeOrderItem($orderId, $request->detail_id);
            return response()->json([
                'success' => true,
                'message' => 'Đánh dấu hoàn thành công việc',
                'data' => $detail,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * AJAX endpoint: Request additional part
     * Authorization: Service verifies technician ownership
     */
    public function requestPart(Request $request, int $orderId)
    {
        try {
            // Support both single part and batch parts
            $data = $request->validate([
                'part_id' => 'sometimes|required_without:parts|integer|exists:parts,id',
                'quantity' => 'sometimes|required_without:parts|integer|min:1',
                'parts' => 'sometimes|required_without_all:part_id,quantity|array|min:1',
                'parts.*.part_id' => 'required|integer|exists:parts,id',
                'parts.*.quantity' => 'required|integer|min:1',
            ]);

            $result = $this->jobService->requestPart($orderId, $data);
            return response()->json([
                'success' => true,
                'message' => 'Yêu cầu vật tư thành công',
                'data' => $result,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
