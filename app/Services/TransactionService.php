<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class TransactionService
{
    /**
     * Generate unique transaction code
     * Format: TNX + YmdHis + Random6digits
     */
    public function generateTransactionCode(): string
    {
        do {
            $code = 'TNX' . now()->format('YmdHis') . Str::random(6);
        } while (Transaction::where('transaction_code', $code)->exists());

        return $code;
    }

    /**
     * Create transaction
     * 
     * @param array $data
     *   - order_id (required)
     *   - amount (required)
     *   - type (required): payment|refund
     *   - payment_method (required): cash
     * @return Transaction
     */
    public function create(array $data): Transaction
    {
        $data['transaction_code'] = $this->generateTransactionCode();
        $data['created_at'] = now();

        return Transaction::create($data);
    }

    /**
     * Get all transactions for current admin
     * Filtered by branch if not super admin
     */
    public function getAllTransactions(array $filters = [], int $perPage = 20)
    {
        $query = Transaction::query()
            ->with([
                'order' => fn($q) => $q->select('id', 'status', 'total_amount', 'branch_id', 'customer_id')
                    ->with([
                        'customer:id,name,phone',
                        'branch:id,name'
                    ]),
            ])
            ->latest();
        // Filter by order branch (permission check)
        $user = Auth::user();
        if ($user->role !== 'BRANCH_ADMIN' || $user->branch_id != 1) {
            // Not super admin, filter by branch
            $query->whereHas('order', fn($q) => $q->where('branch_id', $user->branch_id));
        }

        // Filter by date range
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        // Filter by payment method
        if (!empty($filters['payment_method'])) {
            $query->where('payment_method', $filters['payment_method']);
        }

        // Filter by transaction type
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        // Super admin: filter by branch
        if (!empty($filters['branch_id']) && $user->role === 'BRANCH_ADMIN' && $user->branch_id == 1) {
            $query->whereHas('order', fn($q) => $q->where('branch_id', $filters['branch_id']));
        }

        return $query->paginate($perPage);
    }

    /**
     * Get transaction statistics
     */
    public function getTransactionStats(array $filters = []): array
    {
        $query = Transaction::query();

        $user = Auth::user();
        if ($user->role !== 'BRANCH_ADMIN' || $user->branch_id != 1) {
            $query->whereHas('order', fn($q) => $q->where('branch_id', $user->branch_id));
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $transactions = $query->get();

        $totalPayment = $transactions->where('type', 'payment')->sum('amount');
        $totalRefund = $transactions->where('type', 'refund')->sum('amount');
        $netRevenue = $totalPayment - $totalRefund;

        return [
            'total_payment' => $totalPayment,
            'total_refund' => $totalRefund,
            'net_revenue' => $netRevenue,
            'payment_count' => $transactions->where('type', 'payment')->count(),
            'refund_count' => $transactions->where('type', 'refund')->count(),
        ];
    }
}
