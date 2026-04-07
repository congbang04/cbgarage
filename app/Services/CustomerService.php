<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Pagination\Paginator;

class CustomerService
{
    /**
     * Create or find customer by phone
     */
    public function createOrFind(array $data)
    {
        return Customer::firstOrCreate(
            [
                'phone' => $data['phone']
            ],
            [
                'name' => $data['name'],
                'address' => $data['address'] ?? null,
            ]
        );
    }

    /**
     * Get all customers with filters and pagination
     */
    public function getCustomers(array $filters = [], int $perPage = 15)
    {
        $query = Customer::with(['cars', 'bookings']);

        // Search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")

                    ->orWhereHas('user', function ($user) use ($search) {
                        $user->where('email', 'like', "%{$search}%");
                    });
            });
        }

        // Sort
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';

        if (in_array($sortBy, ['name', 'phone', 'email', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query->paginate($perPage);
    }

    /**
     * Create new customer
     */
    public function store(array $data): Customer
    {
        return Customer::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?? null,
            'address' => $data['address'] ?? null,
        ]);
    }

    /**
     * Update customer
     */
    public function update(Customer $customer, array $data): bool
    {
        return $customer->update($data);
    }

    /**
     * Delete customer (hard delete)
     */
    public function delete(Customer $customer): void
    {
        $customer->delete();
    }

    /**
     * Get customer with relations
     */
    public function getWithRelations(Customer $customer): Customer
    {
        return $customer->load(['user', 'cars', 'bookings']);
    }

    /**
     * Check if customer has pending or confirmed bookings
     */
    public function hasPendingBookings(Customer $customer): bool
    {
        return $customer->bookings()
            ->whereIn('status', ['PENDING', 'CONFIRMED'])
            ->exists();
    }
}
