<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReceptionRequest;
use App\Models\Brand;
use App\Models\Order;
use App\Services\ReceptionService;
use Illuminate\Http\Request;

class ReceptionController extends Controller
{
    protected $receptionService;
    public function __construct(ReceptionService $receptionService)
    {
        $this->receptionService = $receptionService;
    }
    public function list()
    {
        return view('admin.reception.list');
    }
    public function tableList(Request $request)
    {
        $filters = $request->only(['keyword', 'status', 'date', 'branch_id']);

        $orders = $this->receptionService->searchOrders($filters);


        // Luôn trả về cùng một partial view để jQuery nạp vào DOM
        return view('admin.reception.partial-table-list', compact('orders'));
    }
    public function create()
    {
        $brands = Brand::with('carModels')->get();

        return view('admin.reception.create', compact('brands'));
    }
    public function quotation(Request $request)
    {
        $orderId = $request->query('orderId');
        $order = null;

        if ($orderId) {
            $order = Order::with(['customer', 'car', 'car.brand', 'car.carModel', 'details', 'branch'])->findOrFail($orderId);
        }

        return view('admin.reception.quotation', compact('order', 'orderId'));
    }
    public function storeReception(ReceptionRequest $request)
    {
        $data = $request->validated();

        $order = $this->receptionService->store($data);

        return response()->json([
            'message' => 'Tiếp nhận xe thành công',
            'order_id' => $order->id
        ]);
    }

    /**
     * Get all services for quotation
     * GET /reception/services
     */
    public function getServices()
    {
        $services = $this->receptionService->getServices();
        return response()->json($services);
    }

    /**
     * Get all parts for quotation
     * GET /reception/parts
     */
    public function getParts()
    {
        $parts = $this->receptionService->getParts();
        return response()->json($parts);
    }

    /**
     * Get all technicians for assignment (filtered by user's branch)
     * GET /reception/technicians
     */
    public function getTechnicians()
    {
        $technicians = $this->receptionService->getTechnicians(auth()->user()->branch_id);
        return response()->json($technicians);
    }

    /**
     * Save quotation
     * POST /reception/quotation/{orderId}
     */
    public function saveQuotation(Request $request, int $orderId)
    {
        $order = Order::findOrFail($orderId);

        $validated = $request->validate([
            'details' => 'required|array|min:1',
            'details.*.item_type' => 'required|in:App\Models\Service,App\Models\Part',
            'details.*.item_id' => 'required|integer',
            'details.*.quantity' => 'required|numeric|min:1',
            'details.*.price' => 'required|numeric|min:0',
            'details.*.discount' => 'nullable|numeric|min:0|max:100',
            'technician_id' => 'nullable|integer|exists:technicians,id',
        ]);
        $quotation = $this->receptionService->saveQuotation(
            $order,
            $validated['details'],
            $validated['technician_id'] ?? null
        );

        return response()->json([
            'message' => 'Lưu báo giá thành công',
            'quotation' => $quotation->toArray()
        ], 201);
    }

    /**
     * Get quotation items as HTML partial
     * GET /reception/quotation/{orderId}/items
     */
    public function getQuotationItems(int $orderId)
    {
        $quotation = $this->receptionService->getQuotation($orderId);

        if (!$quotation) {
            return response()->json(['error' => 'Báo giá không tìm thấy'], 404);
        }

        $items = $quotation->details ?? [];
        $technicianId = $quotation->technician_id;


        return view('admin.reception.partials.quotation-items', compact('items', 'technicianId'));
    }

    public function cancel($id)
    {
        try {
            $order = $this->receptionService->cancelOrder($id);

            return response()->json([
                'success' => true,
                'message' => 'Hủy đơn thành công',
                'data' => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
