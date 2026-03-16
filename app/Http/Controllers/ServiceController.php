<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;
use App\Services\ServiceService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    protected $serviceService;
    public function __construct(
        ServiceService $serviceService,
    ) {
        $this->serviceService = $serviceService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = $this->serviceService->getAll();
        return view('admin.settings.services', compact('services'));
    }
    public function servicesList()
    {
        $services = $this->serviceService->getAll();
        return view('admin.settings.service-partials.table-list', compact('services'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateServiceRequest $request)
    {
        $service = $this->serviceService->create($request->validated());

        return response()->json([
            'service' => $service,
            'success' => true,
            'message' => 'Thêm dịch vụ thành công'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        $this->serviceService->update($service, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật dịch vụ thành công'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->serviceService->destroy($id);

        return response()->json([
            'success' => true,
            'message' => 'Xóa dịch vụ thành công'
        ]);
    }
}
