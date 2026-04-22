<?php

namespace App\Services;

use App\Models\Bay;
use App\Models\Booking;

class BayService
{
    public function getAll($perPage = 10, $filters = [])
    {
        $query = Bay::query();

        // Filter by name
        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        // Filter by status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by branch
        if (!empty($filters['branch_id'])) {
            $query->where('branch_id', $filters['branch_id']);
        }

        return $query->with('branch')->orderByDesc('created_at')->paginate($perPage);
    }

    public function getBaysByBranch($branchId, $filters = [])
    {
        $query = Bay::where('branch_id', $branchId);

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('name')->get();
    }

    public function find($id)
    {
        return Bay::with('branch')->findOrFail($id);
    }

    public function create(array $data): Bay
    {
        return Bay::create($data);
    }

    public function update(Bay $bay, array $data)
    {
        $bay->update($data);
        return $bay;
    }

    public function destroy(int $id): bool
    {
        $bay = $this->find($id);
        return $bay->delete();
    }

    public function getAvailableBays($branchId = null)
    {
        $query = Bay::where('status', 'available');

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        return $query->orderBy('name')->get();
    }

    public function findBayFirstAvailable($branchId, $startTime, $endTime)
    {
        $bays = Bay::where('branch_id', $branchId)->get();

        foreach ($bays as $bay) {

            $exists = Booking::where('bay_id', $bay->id)
                ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED])
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->whereBetween('start_time', [$startTime, $endTime])
                        ->orWhereBetween('end_time', [$startTime, $endTime])
                        ->orWhere(function ($q) use ($startTime, $endTime) {
                            $q->where('start_time', '<=', $startTime)
                                ->where('end_time', '>=', $endTime);
                        });
                })
                ->exists();

            if (!$exists) {
                return $bay;
            }
        }

        return null;
    }
}
