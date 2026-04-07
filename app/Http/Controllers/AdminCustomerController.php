<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Services\AdminService;
use App\Services\CustomerService;
use Exception;
use Illuminate\Http\Request;

class AdminCustomerController extends Controller
{
    protected CustomerService $customerService;
    protected AdminService $adminService;

    public function __construct(CustomerService $customerService, AdminService $adminService)
    {
        $this->customerService = $customerService;
        $this->adminService = $adminService;
    }

    /**
     * Display a listing of the resource.
     * GET /admin/customers
     */
    public function index()
    {
        return view('admin.customers.index');
    }

    /**
     * Return customer list table (AJAX endpoint)
     * GET /admin/customers/list
     */
    public function customerList(Request $request)
    {
        $filters = [
            'search' => $request->input('search', ''),
            'sort_by' => $request->input('sort_by', 'created_at'),
            'sort_order' => $request->input('sort_order', 'desc'),
        ];
        $customers = $this->customerService->getCustomers($filters, 15);

        return view('admin.customers.partials.table', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     * GET /admin/customers/create
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /admin/customers
     */
    public function store(StoreCustomerRequest $request)
    {
        try {
            $data = $request->validated();
            $customer = $this->customerService->store($data);

            return response()->json([
                'success' => true,
                'message' => 'Thêm khách hàng thành công',
                'data' => $customer
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi thêm khách hàng'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     * GET /admin/customers/{customer}
     */
    public function show(Customer $customer)
    {
        $customer = $this->customerService->getWithRelations($customer);
        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     * GET /admin/customers/{customer}/edit
     */
    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     * PUT /admin/customers/{customer}
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        try {
            $data = $request->validated();
            $this->customerService->update($customer, $data);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cập nhật khách hàng thành công',
                    'data' => $customer->refresh()
                ]);
            }

            return redirect()
                ->route('admin.customers.show', $customer->id)
                ->with('success', 'Cập nhật khách hàng thành công');
        } catch (Exception $e) {

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đã xảy ra lỗi khi cập nhật khách hàng'
                ], 500);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Đã xảy ra lỗi khi cập nhật khách hàng');
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /admin/customers/{customer}
     */
    public function destroy(Customer $customer)
    {
        try {
            // Check if customer has pending or confirmed bookings
            if ($this->customerService->hasPendingBookings($customer)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa khách hàng vì có lịch đặt chưa hoàn thành'
                ], 400);
            }

            $this->customerService->delete($customer);

            return response()->json([
                'success' => true,
                'message' => 'Xóa khách hàng thành công'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi xóa khách hàng'
            ], 500);
        }
    }
}
