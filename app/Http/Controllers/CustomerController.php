<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function home()
    {
        return view('customer.home');
    }
    public function profile()
    {
        return view('customer.profile');
    }
    public function carDetail()
    {
        return view('customer.car-detail');
    }
    public function update(UpdateCustomerRequest $request)
    {
        $customer = Customer::where('user_id', auth()->id())
            ->firstOrFail();
        $customer->update(
            $request->only(['name', 'phone', 'address'])
        );
        return response()->json([
            'success' => true,
            'customer' => $customer,
            'message' => 'Cập nhật thông tin thành công',
        ]);
    }
}
