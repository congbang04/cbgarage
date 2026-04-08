<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


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
        $targetDate = $date ? Carbon::parse($date)->toDateString() : Carbon::today()->toDateString();
        $branchId = $this->getBranchId();
        return Booking::query()
            ->where('status', Booking::STATUS_PENDING)
            ->whereDate('start_time', $targetDate)
            ->when($branchId, function ($q) use ($branchId) {
                $q->whereHas('bay', function ($q2) use ($branchId) {
                    $q2->where('branch_id', $branchId);
                });
            })
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
     * Lấy tổng doanh thu theo ngày
     * @param $date Ngày lấy doanh thu (YYYY-MM-DD)
     * @return float Tổng doanh thu của ngày
     */
    public function getDailyRevenue($date = null): float
    {
        $targetDate = $date ? Carbon::parse($date) : Carbon::today();

        $query = Transaction::query()
            ->whereDate('created_at', $targetDate->toDateString())
            ->where('type', 'payment'); // Chỉ lấy giao dịch thanh toán

        if ($branchId = $this->getBranchId()) {
            $query->whereHas('order', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }

        return (float) $query->sum('amount');
    }

    /**
     * Lấy doanh thu theo khoảng ngày (trong 1 tuần)
     * @param $date Ngày để lắy tuần (YYYY-MM-DD)
     * @return array Mảng doanh thu theo từng ngày trong tuần
     */
    public function getWeeklyRevenue($date = null): array
    {
        $targetDate = $date ? Carbon::parse($date) : Carbon::today();

        // Lấy start của tuần (Thứ 2) và end (Chủ nhật)
        $startOfWeek = $targetDate->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $targetDate->copy()->endOfWeek(Carbon::SUNDAY);

        // Lấy tất cả transaction trong tuần này
        $query = Transaction::query()
            ->whereBetween('created_at', [
                $startOfWeek->startOfDay(),
                $endOfWeek->endOfDay()
            ])
            ->where('type', 'payment'); // Chỉ lấy giao dịch thanh toán

        // Nếu có branch_id (kiểm tra user có branch không)
        if ($branchId = $this->getBranchId()) {
            $query->whereHas('order', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }

        $transactions = $query->get();

        // Nhóm doanh thu theo ngày
        $revenueByDay = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $startOfWeek->copy()->addDays($i);
            $dayKey = $day->format('Y-m-d');
            $revenueByDay[$dayKey] = 0;
        }

        foreach ($transactions as $transaction) {
            $dayKey = $transaction->created_at->format('Y-m-d');
            if (isset($revenueByDay[$dayKey])) {
                $revenueByDay[$dayKey] += $transaction->amount;
            }
        }

        return $revenueByDay;
    }

    /**
     * Lấy phân bổ doanh thu theo dịch vụ trong tuần
     * @param $date Ngày để lấy tuần (YYYY-MM-DD)
     * @return array Mảng doanh thu theo từng dịch vụ
     */
    public function getServiceDistribution($date = null): array
    {
        $targetDate = $date ? Carbon::parse($date) : Carbon::today();

        // Lấy start và end của tuần
        $startOfWeek = $targetDate->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $targetDate->copy()->endOfWeek(Carbon::SUNDAY);

        $branchId = $this->getBranchId();

        // Lấy tất cả order details có item_type = Service trong tuần
        $query = \DB::table('order_details')
            ->select('services.id', 'services.name', \DB::raw('SUM(order_details.price * order_details.quantity) as total'))
            ->join('services', function ($join) {
                $join->on('order_details.item_id', '=', 'services.id')
                    ->where('order_details.item_type', '=', 'App\\Models\\Service');
            })
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->whereBetween('order_details.created_at', [
                $startOfWeek->startOfDay(),
                $endOfWeek->endOfDay()
            ]);

        if ($branchId) {
            $query->where('orders.branch_id', $branchId);
        }

        $services = $query->groupBy('services.id', 'services.name')
            ->orderByDesc('total')
            ->get();

        $result = [];
        foreach ($services as $service) {
            $result[] = [
                'id' => $service->id,
                'name' => $service->name,
                'total' => (float) $service->total,
            ];
        }

        return $result;
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
