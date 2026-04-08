<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerSearchRequest;
use App\Services\AdminService;
use App\Services\CustomerSearchService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $customerSearchService, $adminService;
    public function __construct(CustomerSearchService $customerSearchService, AdminService $adminService)
    {
        $this->customerSearchService = $customerSearchService;
        $this->adminService = $adminService;
    }
    public function dashboard()
    {
        $carStats = $this->adminService->getDashboardStats();

        return view('admin.dashboard', compact('carStats'));
    }
    public function customerSearch(CustomerSearchRequest $request)
    {
        $phone = $request->phone;

        $data = $this->customerSearchService->customerSearch($phone);
        return response()->json($data);
    }
    public function getStatsApi(Request $request)
    {
        // Lấy stats dựa trên ngày từ input AJAX
        $carStats = $this->adminService->getDashboardStats($request->date);

        return response()->json([
            'carStats' => $carStats
        ]);
    }

    /**
     * Lấy doanh thu ngày
     */
    public function getDailyRevenueApi(Request $request)
    {
        $dailyRevenue = $this->adminService->getDailyRevenue($request->date);

        return response()->json([
            'dailyRevenue' => $dailyRevenue
        ]);
    }

    /**
     * Lấy doanh thu tuần cho biểu đồ
     */
    public function getChartRevenueApi(Request $request)
    {
        $weeklyRevenue = $this->adminService->getWeeklyRevenue($request->date);

        return response()->json([
            'weeklyRevenue' => $weeklyRevenue
        ]);
    }

    /**
     * Lấy phân bổ dịch vụ - biểu đồ tròn
     */
    public function getChartServiceDistributionApi(Request $request)
    {
        $serviceDistribution = $this->adminService->getServiceDistribution($request->date);

        return response()->json([
            'services' => $serviceDistribution
        ]);
    }
}
