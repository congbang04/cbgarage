<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Order;
use App\Services\CarService;
use App\Services\ReceptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    protected $carService, $receptionService;

    public function __construct(CarService $carService, ReceptionService $receptionService)
    {
        $this->carService = $carService;
        $this->receptionService = $receptionService;
    }

    public function store(CreateCarRequest $request)
    {
        $data = [
            'customer_id' => auth()->user()->customer->id ?? null,
            'brand_id' => $request->brand_id,
            'car_model_id' => $request->car_model_id,
            'license_plate' => $request->license_plate,
            'manufacture_year' => $request->manufacture_year,
            'color' => $request->color,
            'current_odo' => $request->current_odo,
            'vin_number' => $request->vin_number,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('cars');
            $data['image'] = $path;
        }

        $car = $this->carService->create($data);

        return response()->json([
            'message' => 'Xe đã được thêm thành công',
            'data' => $car
        ]);
    }
    public function carDetail(Car $car)
    {
        if (
            $car->customer->user_id !== auth()->id() &&
            !Gate::allows('is-admin')
        ) {
            abort(403);
        }

        $brands = Brand::with('carModels')->get();
        $orders = $this->receptionService->getOrdersByCar($car->id);
        return view('customer.car-detail', compact('car', 'brands', 'orders'));
    }
    public function carOrders(Car $car)
    {
        if ($car->customer->user_id !== auth()->id()) {
            abort(403);
        }
        $orders = $this->receptionService->getOrdersByCar($car->id);
        return view('customer.car-partials.car-detail-table', compact('orders'));
    }
    public function quotation(Request $request)
    {
        $orderId = $request->query('orderId');
        $order = null;

        if ($orderId) {
            $order = Order::with([
                'customer',
                'car',
                'car.brand',
                'car.carModel',
                'details'
            ])->findOrFail($orderId);
            $carId = $order->car->id;
        }
        return view('customer.car-quotation', compact('order', 'orderId', 'carId'));
    }
    public function getCarQuotationItems(int $orderId)
    {
        $quotation = $this->receptionService->getQuotation($orderId);

        if (!$quotation) {
            return response()->json(['error' => 'Báo giá không tìm thấy'], 404);
        }

        $items = $quotation->details ?? [];
        $technicianId = $quotation->technician_id;

        return view('customer.car-partials.car-quotation-items', compact('items', 'technicianId'));
    }
    public function update(UpdateCarRequest $request, Car $car)
    {
        if ($car->customer->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            if (!empty($car->image) && Storage::disk()->exists($car->image)) {
                Storage::disk()->delete($car->image);
            }

            // Store new image
            $path = $request->file('image')->store('cars');
            $validated['image'] = $path;
        }

        $car->update($validated);

        return response()->json([
            'success' => true,
            'car' => $car,
            'message' => 'Cập nhật thông tin xe thành công',
        ]);
    }
    public function destroy(Car $car)
    {
        $car->delete();
        return response()->json(['message' => 'Xe đã được xóa thành công']);
    }
}
