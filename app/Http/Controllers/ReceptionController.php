<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReceptionRequest;
use App\Models\Brand;
use App\Services\ReceptionService;
use Illuminate\Http\Request;

class ReceptionController extends Controller
{
    protected $receptionService;
    public function __construct(ReceptionService $receptionService)
    {
        $this->receptionService = $receptionService;
    }
    public function list()
    {
        return view('admin.reception.list');
    }
    public function tableList()
    {
        $orders = $this->receptionService->getOrders();
        return view('admin.reception.partial-table-list', compact('orders'));
    }
    public function create()
    {
        $brands = Brand::with('carModels')->get();

        return view('admin.reception.create', compact('brands'));
    }
    public function quotation()
    {
        return view('admin.reception.quotation');
    }
    public function storeReception(ReceptionRequest $request)
    {
        $data = $request->validated();

        $order = $this->receptionService->store($data);

        return response()->json([
            'message' => 'Tiếp nhận xe thành công',
            'order_id' => $order->id
        ]);
    }
}
