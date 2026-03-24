<?php

namespace App\Http\Controllers;

use App\Services\WarehouseService;
use App\Services\PartRequestService;
use App\Services\StockTransferService;
use App\Http\Requests\PartRequestRequest;
use App\Http\Requests\StockTransferRequest;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    protected WarehouseService $warehouseService;
    protected PartRequestService $partRequestService;
    protected StockTransferService $stockTransferService;

    public function __construct(
        WarehouseService $warehouseService,
        PartRequestService $partRequestService,
        StockTransferService $stockTransferService
    ) {
        $this->warehouseService = $warehouseService;
        $this->partRequestService = $partRequestService;
        $this->stockTransferService = $stockTransferService;
    }

    /**
     * Inventory Management - List view
     */
    public function inventory(Request $request)
    {
        $inventories = $this->warehouseService->getInventoriesByBranch();
        $stats = $this->warehouseService->getInventoryStats();

        return view('admin.warehouse.inventory', compact('inventories', 'stats'));
    }
    public function getPendingCount()
    {
        return response()->json([
            'count' => $this->warehouseService->getPendingPartRequestsCount()
        ]);
    }

    /**
     * Get inventory list with filters & search (AJAX endpoint for table reload)
     */
    public function inventoryList(Request $request)
    {
        $filters = $request->only(['keyword', 'status', 'price_from', 'price_to']);
        $perPage = $request->get('per_page', 15);
        // Nếu không có filter, lấy tất cả theo pagination
        if (!empty(array_filter($filters))) {
            $inventories = $this->warehouseService->searchInventories($filters, $perPage);
        } else {
            $inventories = $this->warehouseService->getInventoriesByBranch($perPage);
        }

        return view('admin.warehouse.partials.inventory-list', compact('inventories'));
    }

    /**
     * Part Requests - List view
     */
    public function partRequests()
    {
        return view('admin.warehouse.part-requests');
    }
    /**
     * Get part requests card view (with optional filters)
     */
    public function cardPartRequests(Request $request)
    {
        $filters = $request->only(['status', 'date_from', 'date_to']);
        $requests = $this->partRequestService->getPendingRequests($filters);

        return view('admin.warehouse.partials.card-part-request', compact('requests'));
    }
    public function cardTransferRequests(Request $request)
    {
        $filters = $request->only(['status', 'date_from', 'date_to']);
        $requests = $this->stockTransferService->getTransfersForBranch($filters);

        return view('admin.warehouse.partials.card-transfer-request', compact('requests'));
    }
    /**
     * Show goods issue detail (Phiếu xuất kho)
     */
    public function showGoodsIssue($partRequestId)
    {
        $partRequest = $this->partRequestService->getPartRequestById($partRequestId);

        return view('admin.warehouse.goods-issue', compact('partRequest'));
    }
    public function showTransferGoodsIssue($stockTransferId)
    {
        $stockTransfer = $this->stockTransferService->getStockTransferById($stockTransferId);
        return view('admin.warehouse.goods-issue-transfer', compact('stockTransfer'));
    }

    /**
     * Approve part request and create goods issue
     */
    public function approvePartRequest(PartRequestRequest $request, $partRequestId)
    {
        $goods = $this->partRequestService->issueGoods(
            $partRequestId,
            $request->validated()
        );
        return response()->json([
            'message' => 'Xuất kho thành công',
            'goods_issue_id' => $goods['id']
        ], 201);
    }
    /**
     * Approve transfer request and create goods issue (Xuất kho điều chuyển)
     */
    public function approveTransferRequest(Request $request, $stockTransferId)
    {
        $request->validate([
            'actual_quantities' => 'required|array',
            'actual_quantities.*' => 'required|numeric|min:0',
        ]);

        $transfer = $this->stockTransferService->issueGoods(
            $stockTransferId,
            $request->all()
        );

        return response()->json([
            'message' => 'Xuất kho điều chuyển thành công. Hàng đang được vận chuyển!',
            'transfer_id' => $transfer['id']
        ], 200);
    }
    /**
     * Reject part request
     */
    public function rejectPartRequest(Request $request, $partRequestId)
    {
        $reason = $request->get('reason');
        $this->partRequestService->rejectRequest($partRequestId, $reason);

        return response()->json(['message' => 'Từ chối yêu cầu thành công']);
    }

    public function cancelTransferRequest(Request $request, $partRequestId)
    {
        $reason = $request->get('reason');
        $this->stockTransferService->cancelTransfer($partRequestId, $reason);

        return response()->json(['message' => 'Từ chối yêu cầu thành công']);
    }

    /**
     * Stock Transfer - List view
     */
    public function stockTransfer()
    {
        $branches = $this->warehouseService->getBranches();

        return view('admin.warehouse.stock-transfer', compact('branches'));
    }
    public function stockTransferHistory()
    {
        $transfers = $this->stockTransferService->getTransferHistoriesForBranch();

        return view('admin.warehouse.partials.stock-transfer-history', compact('transfers'));
    }

    /**
     * Create stock transfer request
     */
    public function createStockTransfer(StockTransferRequest $request)
    {
        $transfer = $this->stockTransferService->createTransfer($request->validated());

        return response()->json([
            'message' => 'Tạo phiếu chuyển kho thành công',
            'transfer_id' => $transfer->id
        ], 201);
    }

    /**
     * Get parts for stock transfer modal
     */
    public function getSampleParts()
    {
        $parts = $this->warehouseService->getPartsForTransfer();

        return response()->json($parts);
    }
}
