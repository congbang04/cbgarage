<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCarRequest;
use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function store(AddCarRequest $request)
    {
        Car::create([
            'customer_id' => auth()->id(),
            'brand_id' => $request->brand_id,
            'car_model_id' => $request->car_model_id,
            'license_plate' => $request->license_plate,
            'manufacture_year' => $request->manufacture_year,
            'color' => $request->color,
            'current_odo' => $request->current_odo,
            'vin_number' => $request->vin_number,
        ]);
        return response()->json(['message' => 'Xe đã được thêm thành công']);
    }
    public function carDetail(Car $car)
    {
        if ($car->customer->user_id !== auth()->id()) {
            abort(403);
        }
        return view('customer.car-detail', compact('car'));
    }
    public function destroy(Car $car)
    {
        $car->delete();
        return response()->json(['message' => 'Xe đã được xóa thành công']);
    }
}
