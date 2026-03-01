<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Brand;
use App\Models\Car;
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
        $cars = Car::whereHas('customer', function ($q) {
            $q->where('user_id', auth()->id());
        })->with(['brand', 'carModel', 'customer'])->get();
        $brands = Brand::with('carModels')->get();

        return view('customer.profile', compact('cars', 'brands'));
    }
    public function carDetail(Car $car)
    {
        if ($car->customer->user_id !== auth()->id()) {
            abort(403);
        }
        return view('customer.car-detail', compact('car'));
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
