<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /staff
     */
    public function index()
    {
        $branches = Branch::where('is_actived', true)->get();
        return view('admin.settings.staff', compact('branches'));
    }

    /**
     * Return staff list table (AJAX endpoint)
     * GET /staff/list
     */
    public function staffList(Request $request)
    {
        // Get filter parameters
        $search = $request->input('search', '');
        $role = $request->input('role', '');
        $branch = $request->input('branch', '');
        $status = $request->input('status', '');

        $query = User::with('technician')->where('role', '!=', 'CUSTOMER')->where('branch_id', auth()->user()->branch_id);

        // Search by name, email, phone
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                    ->orWhereHas('technician', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by role
        if ($role) {
            $query->where('role', $role);
        }

        // Filter by branch
        if ($branch) {
            $query->where('branch_id', $branch);
        }

        // Filter by status (active/inactive)
        if ($status !== '') {
            $status_bool = $status == 'active' ? 1 : 0;
            $query->where('is_actived', $status_bool);
        }

        $staff = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.settings.staff-partials.table-list', compact('staff'));
    }

    /**
     * Store a newly created resource in storage.
     * POST /staff
     */
    public function store(StoreStaffRequest $request)
    {
        $data = $request->validated();

        // Generate default password
        $data['password'] = bcrypt('Rcvn123456');
        $data['is_actived'] = true;
        $data['is_deleted'] = false;

        $user = User::create($data);

        return response()->json([
            'user' => $user,
            'success' => true,
            'message' => 'Thêm nhân viên thành công'
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     * PUT /staff/{id}
     */
    public function update(UpdateStaffRequest $request, User $staff)
    {
        $data = $request->validated();
        $staff->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật nhân viên thành công',
            'data' => $staff
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /staff/{id}
     */
    public function destroy(User $staff)
    {
        $staff->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa nhân viên thành công'
        ]);
    }

    /**
     * Toggle staff status
     * POST /staff/{id}/toggle-status
     */
    public function toggleStatus(User $staff)
    {
        $staff->update([
            'is_actived' => !$staff->is_actived
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công',
            'data' => $staff
        ]);
    }
}
