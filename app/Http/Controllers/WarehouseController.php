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
        $inventories = $this->warehouseService->getInventoriesByCentralBranch();
        $stats = $this->warehouseService->getInventoryStats();
        $countPartRequest = $this->warehouseService->getPendingPartRequestsCount();

        return view('admin.warehouse.inventory', compact('inventories', 'stats', 'countPartRequest'));
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
            $inventories = $this->warehouseService->getInventoriesByCentralBranch($perPage);
        }

        return view('admin.warehouse.partial-inventory-list', compact('inventories'));
    }

    /**
     * Part Requests - List view
     */
    public function partRequests()
    {
        return view('admin.warehouse.part-requests');
    }
    public function cardPartRequests()
    {
        $requests = $this->partRequestService->getPendingRequests();

        return view('admin.warehouse.partials.card-part-request', compact('requests'));
    }

    /**
     * Show goods issue detail (Phiếu xuất kho)
     */
    public function showGoodsIssue($partRequestId)
    {
        $partRequest = $this->partRequestService->getPartRequestById($partRequestId);

        return view('admin.warehouse.goods-issue', compact('partRequest'));
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
     * Reject part request
     */
    public function rejectPartRequest(Request $request, $partRequestId)
    {
        $reason = $request->get('reason');
        $this->partRequestService->rejectRequest($partRequestId, $reason);

        return response()->json(['message' => 'Từ chối yêu cầu thành công']);
    }

    /**
     * Stock Transfer - List view
     */
    public function stockTransfer()
    {
        $transfers = $this->stockTransferService->getTransfersForBranch();
        $branches = $this->warehouseService->getBranches();

        return view('admin.warehouse.stock-transfer', compact('transfers', 'branches'));
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
