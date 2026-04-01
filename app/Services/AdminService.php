<?php

namespace App\Services;

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

    /**
     * Dashboard
     */
    public function getDashboardStats($date = null): array
    {
        return [
            'in_workshop' => $this->countCarsInWorkshopToday($date),
            'waiting' => $this->countCarsWaitingToday($date),
        ];
    }
}
