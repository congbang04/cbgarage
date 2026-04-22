<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBayRequest;
use App\Http\Requests\UpdateBayRequest;
use App\Models\Bay;
use App\Services\BayService;
use App\Services\BranchService;

class BayController extends Controller
{
    protected $bayService;
    protected $branchService;

    public function __construct(
        BayService $bayService,
        BranchService $branchService,
    ) {
        $this->bayService = $bayService;
        $this->branchService = $branchService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bays = $this->bayService->getAll(10, request()->all());
        $branches = $this->branchService->getActiveBranches();
        return view('admin.settings.bays', compact('bays', 'branches'));
    }

    /**
     * Return partial list (AJAX endpoint)
     */
    public function bayList()
    {
        $bays = $this->bayService->getAll(10, request()->all());
        $bayService = $this->bayService;
        return view('admin.settings.bay-partials.card-list', compact('bays', 'bayService'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBayRequest $request)
    {
        $bay = $this->bayService->create($request->validated());

        return response()->json([
            'bay' => $bay,
            'success' => true,
            'message' => 'Thêm cầu nâng thành công'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBayRequest $request, Bay $bay)
    {
        $this->bayService->update($bay, $request->validated());

        return response()->json([
            'bay' => $bay,
            'success' => true,
            'message' => 'Cập nhật cầu nâng thành công'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bay $bay)
    {
        $this->bayService->destroy($bay->id);

        return response()->json([
            'success' => true,
            'message' => 'Xóa cầu nâng thành công'
        ]);
    }
}
