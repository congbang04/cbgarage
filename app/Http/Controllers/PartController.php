<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePartRequest;
use App\Http\Requests\UpdatePartRequest;
use App\Models\Part;
use App\Services\PartService;

class PartController extends Controller
{
    protected $partService;

    public function __construct(
        PartService $partService,
    ) {
        $this->partService = $partService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parts = $this->partService->getAll(10, request()->all());
        $units = $this->partService->getAllUnits();
        return view('admin.settings.parts', compact('parts', 'units'));
    }

    public function partsList()
    {
        $parts = $this->partService->getAll(10, request()->all());
        $units = $this->partService->getAllUnits();
        return view('admin.settings.part-partials.table-list', compact('parts', 'units'));
    }

    /**
     * Get all parts as JSON for AJAX endpoints (Technician Jobs)
     */
    public function partsJson()
    {
        $parts = Part::select('id', 'sku', 'name', 'price_sell as unit_price')
            ->orderBy('name')
            ->get();

        return response()->json($parts);
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
    public function store(CreatePartRequest $request)
    {
        $part = $this->partService->create($request->validated());

        return response()->json([
            'part' => $part,
            'success' => true,
            'message' => 'Thêm phụ tùng thành công'
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
    public function update(UpdatePartRequest $request, Part $part)
    {
        $this->partService->update($part, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật phụ tùng thành công'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->partService->destroy($id);

        return response()->json([
            'success' => true,
            'message' => 'Xóa phụ tùng thành công'
        ]);
    }
}
