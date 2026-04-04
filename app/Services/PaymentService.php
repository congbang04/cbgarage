<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    // Payment methods
    const METHOD_CASH = 'cash';
    const METHOD_BANK_TRANSFER = 'bank_transfer';
    const METHOD_CARD = 'card';

    public static function paymentMethods(): array
    {
        return [
            self::METHOD_CASH => 'Tiền mặt',
            self::METHOD_BANK_TRANSFER => 'Chuyển khoản',
            self::METHOD_CARD => 'Thẻ',
        ];
    }

    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Pay for an order
     * 
     * @param Order $order
     * @param string $paymentMethod (default: cash)
     * @return array
     * @throws \Exception
     */
    public function pay(Order $order, string $paymentMethod = self::METHOD_CASH): array
    {
        // Validate payment method
        if (!in_array($paymentMethod, array_keys(self::paymentMethods()))) {
            throw new \Exception("Hình thức thanh toán không hợp lệ!");
        }

        // Validate order status
        if ($order->status !== Order::STATUS_COMPLETED) {
            throw new \Exception("Chỉ có thể thanh toán cho đơn hàng đã hoàn thành. Trạng thái hiện tại: {$order->status}");
        }

        // Check if order already has payment transaction
        if ($order->status === Order::STATUS_PAID) {
            throw new \Exception("Đơn hàng này đã được thanh toán!");
        }

        // Validate amount
        if ($order->total_amount <= 0) {
            throw new \Exception("Số tiền thanh toán không hợp lệ!");
        }

        try {
            return DB::transaction(function () use ($order, $paymentMethod) {
                // Create payment transaction
                $transaction = $this->transactionService->create([
                    'order_id' => $order->id,
                    'amount' => $order->total_amount,
                    'type' => 'payment',
                    'payment_method' => $paymentMethod,
                ]);

                $transaction->touch();

                // Update order status
                $order->update([
                    'status' => Order::STATUS_PAID,
                ]);

                return [
                    'success' => true,
                    'message' => 'Thanh toán thành công!',
                    'transaction' => $transaction,
                    'order' => $order->refresh(),
                ];
            });
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Refund an order (reverse payment)
     * 
     * @param Order $order
     * @param array $data (optional reason, notes)
     * @return array
     * @throws \Exception
     */
    public function refund(Order $order, array $data = []): array
    {
        // Validate order status
        if ($order->status !== Order::STATUS_PAID) {
            throw new \Exception("Chỉ có thể hoàn tiền cho đơn hàng đã thanh toán. Trạng thái hiện tại: {$order->status}");
        }

        // Check if order has payment transaction
        $paymentTransaction = $this->getPaymentTransaction($order);
        if (!$paymentTransaction) {
            throw new \Exception("Không tìm thấy giao dịch thanh toán cho đơn hàng này!");
        }

        try {
            return DB::transaction(function () use ($order, $paymentTransaction, $data) {
                // Create refund transaction
                $refundTransaction = $this->transactionService->create([
                    'order_id' => $order->id,
                    'amount' => $paymentTransaction->amount,
                    'type' => 'refund',
                    'payment_method' => $paymentTransaction->payment_method,
                    'reason' => $data['reason'] ?? null,      // ✨ NEW
                    'notes' => $data['notes'] ?? null,          // ✨ NEW
                ]);

                $refundTransaction->touch();

                // Update order status back to COMPLETED
                $order->update([
                    'status' => Order::STATUS_COMPLETED,
                ]);

                return [
                    'success' => true,
                    'message' => 'Hoàn tiền thành công!',
                    'transaction' => $refundTransaction,
                    'order' => $order->refresh(),
                ];
            });
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get payment transaction for an order
     */
    protected function getPaymentTransaction(Order $order): ?Transaction
    {
        return Transaction::where('order_id', $order->id)
            ->where('type', 'payment')
            ->first();
    }

    /**
     * Get order with transaction history
     */
    public function getOrderWithTransactions(Order $order)
    {
        return Order::with([
            'transactions' => fn($q) => $q->orderBy('created_at', 'desc'),
            'customer',
            'branch',
        ])->find($order->id);
    }

    /**
     * Get payment summary for an order
     */
    public function getPaymentSummary(Order $order): array
    {
        $transactions = Transaction::where('order_id', $order->id)->get();

        $totalPayment = $transactions->where('type', 'payment')->sum('amount');
        $totalRefund = $transactions->where('type', 'refund')->sum('amount');
        $remaining = $order->total_amount - $totalPayment + $totalRefund;

        return [
            'order_amount' => $order->total_amount,
            'total_paid' => $totalPayment,
            'total_refunded' => $totalRefund,
            'remaining' => max(0, $remaining),
            'payment_status' => $remaining <= 0 ? 'paid' : 'unpaid',
        ];
    }
}
