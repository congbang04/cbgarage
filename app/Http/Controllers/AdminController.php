<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerSearchRequest;
use App\Services\CustomerSearchService;

class AdminController extends Controller
{
    protected $customerSearchService;
    public function __construct(CustomerSearchService $customerSearchService)
    {
        $this->customerSearchService = $customerSearchService;
    }
    public function dashboard()
    {
        return view('admin.dashboard');
    }
    public function customerSearch(CustomerSearchRequest $request)
    {
        $phone = $request->phone;

        $data = $this->customerSearchService->customerSearch($phone);
        return response()->json($data);
    }
}
