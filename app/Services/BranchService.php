<?php

namespace App\Services;

use App\Models\Bay;
use App\Models\Branch;

class BranchService
{
    public function getActiveBranches()
    {
        return Branch::where('is_actived', true)
            ->orderBy('name')
            ->get();
    }
    public function countByBranch($branchId)
    {
        return Bay::where('branch_id', $branchId)->count();
    }
    public function find($id)
    {
        return Branch::findOrFail($id);
    }
}
