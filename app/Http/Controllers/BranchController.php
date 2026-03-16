<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Models\Branch;
use App\Services\BranchService;

class BranchController extends Controller
{
    protected $branchService;

    public function __construct(
        BranchService $branchService,
    ) {
        $this->branchService = $branchService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $branches = $this->branchService->getAll(6, request()->all());
        return view('admin.settings.branches', compact('branches'));
    }

    /**
     * Return partial list (AJAX endpoint)
     */
    public function branchList()
    {
        $branches = $this->branchService->getAll(6, request()->all());
        $branchService = $this->branchService;
        return view('admin.settings.branch-partials.card-list', compact('branches', 'branchService'));
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
    public function store(CreateBranchRequest $request)
    {
        $branch = $this->branchService->create($request->validated());

        return response()->json([
            'branch' => $branch,
            'success' => true,
            'message' => 'Thêm chi nhánh thành công'
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
    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        $this->branchService->update($branch, $request->validated());

        return response()->json([
            'branch' => $branch,
            'success' => true,
            'message' => 'Cập nhật chi nhánh thành công'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        $this->branchService->destroy($branch->id);

        return response()->json([
            'success' => true,
            'message' => 'Xóa chi nhánh thành công'
        ]);
    }
}
