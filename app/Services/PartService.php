<?php

namespace App\Services;

use App\Models\Part;

class PartService
{
    public function getAll($perPage = 10, $filters = [])
    {
        $query = Part::query();

        // Filter by SKU
        if (!empty($filters['sku'])) {
            $query->where('sku', 'like', '%' . $filters['sku'] . '%');
        }

        // Filter by Name
        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        // Filter by Unit
        if (!empty($filters['unit'])) {
            $query->where('unit', $filters['unit']);
        }

        // Filter by Price Range
        if (!empty($filters['price_from'])) {
            $query->where('price_sell', '>=', floatval($filters['price_from']));
        }

        if (!empty($filters['price_to'])) {
            $query->where('price_sell', '<=', floatval($filters['price_to']));
        }

        return $query->orderByDesc('created_at')->paginate($perPage);
    }

    public function find($id)
    {
        return Part::findOrFail($id);
    }

    public function create(array $data): Part
    {
        return Part::create($data);
    }

    public function destroy(int $id): bool
    {
        $part = $this->find($id);
        return $part->delete();
    }

    public function update(Part $part, array $data)
    {
        $part->update($data);
        return $part;
    }

    /**
     * Get all distinct units for filter dropdown
     */
    public function getAllUnits()
    {
        return Part::distinct()->pluck('unit')->sort()->values();
    }
}
