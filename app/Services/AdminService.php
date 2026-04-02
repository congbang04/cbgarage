<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Inventory;
use App\Models\Order;
use Illuminate\Support\Carbon;


class AdminService
{

    protected function getBranchId()
    {
        return auth()->check() ? auth()->user()->branch_id : null;
    }

    /**
     * Query base: theo chi nhánh + hôm nay
     */
    protected function baseQuery($date = null)
    {
        $query = Order::query();
        if ($branchId = $this->getBranchId()) {
            $query->where('branch_id', $branchId);
        }

        $targetDate = $date ? Carbon::parse($date) : Carbon::today();
        $query->whereDate('created_at', $targetDate);

        return $query;
    }

    /**
     * Xe trong xưởng hôm nay
     */
    public function countCarsInWorkshopToday($date = null): int
    {
        return $this->baseQuery($date)
            ->whereIn('status', [
                Order::STATUS_IN_PROGRESS,
                Order::STATUS_WAITING_PART,
            ])
            ->count();
    }

    /**
     * Xe đang chờ hôm nay
     */
    public function countCarsWaitingToday($date = null): int
    {
        return $this->baseQuery($date)
            ->whereIn('status', [
                Order::STATUS_RECEIVED,
                Order::STATUS_QUOTED,
            ])
            ->count();
    }
    public function countBookingPendingToday($date = null): int
    {
        $targetDate = $date ? Carbon::parse($date) : Carbon::today();
        $branchId = $this->getBranchId();

        return Booking::with('bay')
            ->whereHas('bay', function ($q) use ($branchId) {
                if ($branchId) {
                    $q->where('branch_id', $branchId);
                }
            })
            ->where('status', Booking::STATUS_PENDING)
            ->whereDate('start_time', $targetDate)
            ->count();
    }
    public function countLowStockParts(): int
    {
        $branchId = $this->getBranchId();

        return Inventory::query()
            ->when($branchId, function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->whereColumn('quantity', '<=', 'min_stock')
            ->count();
    }
    /**
     * Dashboard
     */
    public function getDashboardStats($date = null): array
    {
        return [
            'in_workshop' => $this->countCarsInWorkshopToday($date),
            'waiting' => $this->countCarsWaitingToday($date),
            'pending_bookings' => $this->countBookingPendingToday($date),
            'low_stock_parts' => $this->countLowStockParts(),
        ];
    }
}
