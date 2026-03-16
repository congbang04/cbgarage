<?php

namespace App\Services;

use App\Models\Bay;
use App\Models\Branch;
use App\Models\User;

class BranchService
{
    public function getAll($perPage = 10, $filters = [])
    {
        $query = Branch::query();

        // Filter by name
        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        // Filter by address
        if (!empty($filters['address'])) {
            $query->where('address', 'like', '%' . $filters['address'] . '%');
        }

        // Filter by is_central_warehouse
        if (isset($filters['is_central_warehouse']) && $filters['is_central_warehouse'] !== '') {
            $query->where('is_central_warehouse', (bool) $filters['is_central_warehouse']);
        }

        // Filter by is_actived
        if (isset($filters['is_actived']) && $filters['is_actived'] !== '') {
            $query->where('is_actived', (bool) $filters['is_actived']);
        }

        return $query->orderByDesc('created_at')->paginate($perPage);
    }

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

    public function countStaffByBranch($branchId)
    {
        return \App\Models\User::where('branch_id', $branchId)->count();
    }

    public function find($id)
    {
        return Branch::findOrFail($id);
    }

    public function create(array $data): Branch
    {
        return Branch::create($data);
    }

    public function update(Branch $branch, array $data)
    {
        $branch->update($data);
        return $branch;
    }

    public function destroy(int $id): bool
    {
        $branch = $this->find($id);
        return $branch->delete();
    }
}
