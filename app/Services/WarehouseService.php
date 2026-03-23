<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Inventory;
use App\Models\Part;
use App\Models\PartRequest;
use App\Models\StockTransfer;
use Illuminate\Support\Collection;

class WarehouseService
{
    /**
     * Get all inventories for central warehouse with pagination
     */
    public function getInventoriesByBranch(int $perPage = 15)
    {
        return Inventory::query()
            ->where('branch_id', auth()->user()->branch_id)
            ->with(['part', 'branch'])
            ->orderBy('quantity', 'asc')
            ->paginate($perPage);
    }

    /**
     * Get inventory statistics
     */
    public function getInventoryStats(): array
    {
        $totalValue = Inventory::query()
            ->where('branch_id', auth()->user()->branch_id)
            ->with('part')
            ->get()
            ->sum(fn($inv) => $inv->quantity * ($inv->part->price_sell ?? 0));

        $lowStockCount = Inventory::query()
            ->where('branch_id', auth()->user()->branch_id)
            ->whereRaw('quantity < min_stock')
            ->count();

        return [
            'total_value' => $totalValue,
            'low_stock_count' => $lowStockCount,
        ];
    }

    /**
     * Search inventories with filters: keyword, status, price range, with pagination
     */
    public function searchInventories(array $filters = [], int $perPage = 15)
    {
        $query = Inventory::query()
            ->select('id', 'part_id', 'branch_id', 'quantity', 'min_stock', 'created_at', 'updated_at')
            ->where('branch_id', auth()->user()->branch_id)
            ->with(['part:id,sku,name,unit,price_sell', 'branch:id,name']);

        // Tìm kiếm theo tên, SKU
        if (!empty($filters['keyword'])) {
            $keyword = $filters['keyword'];
            $query->whereHas('part', function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('sku', 'like', "%{$keyword}%");
            });
        }

        // Lọc theo trạng thái tồn kho
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'low_stock') {
                $query->whereRaw('quantity <= min_stock');
            } elseif ($filters['status'] === 'in_stock') {
                $query->whereRaw('quantity > min_stock');
            } elseif ($filters['status'] === 'empty') {
                $query->where('quantity', 0);
            }
        }

        // Lọc theo giá bán (từ - đến)
        if (!empty($filters['price_from']) || !empty($filters['price_to'])) {
            $query->whereHas('part', function ($q) use ($filters) {
                if (!empty($filters['price_from'])) {
                    $q->where('price_sell', '>=', (int)$filters['price_from']);
                }
                if (!empty($filters['price_to'])) {
                    $q->where('price_sell', '<=', (int)$filters['price_to']);
                }
            });
        }

        return $query->orderBy('quantity', 'asc')->paginate($perPage);
    }

    /**
     * Get all branches (for transfer form)
     */
    public function getBranches(): Collection
    {
        return Branch::where('is_actived', true)
            ->orderBy('is_central_warehouse', 'desc')
            ->get();
    }

    /**
     * Get parts for stock transfer
     */
    public function getPartsForTransfer(): Collection
    {
        return Part::query()
            ->orderBy('name')
            ->get()
            ->map(function ($part) {
                return [
                    'id' => $part->id,
                    'sku' => $part->sku,
                    'name' => $part->name,
                    'unit' => $part->unit,
                    'price_sell' => $part->price_sell,
                ];
            });
    }

    /**
     * Get pending part requests count for notification badge
     */
    public function getPendingPartRequestsCount(): int
    {
        $user = auth()->user();
        if (!$user) {
            return 0;
        }
        $countPartRequest = PartRequest::where('status', PartRequest::STATUS_PENDING)
            ->where('branch_id', $user->branch_id)
            ->count();
        $countTransferRequest = StockTransfer::where('status', StockTransfer::STATUS_PENDING)
            ->where('from_branch_id', $user->branch_id)
            ->count();
        return $countPartRequest + $countTransferRequest;
    }
}
