<?php

namespace App\Services;

use App\Models\Order;
use Exception;

class PaymentDetailService
{
    protected $paymentService;
    protected $transactionService;

    public function __construct(
        PaymentService $paymentService,
        TransactionService $transactionService
    ) {
        $this->paymentService = $paymentService;
        $this->transactionService = $transactionService;
    }

    /**
     * Get order details for payment page
     * Lấy thông tin order kèm transactions và payment summary
     */
    public function getOrderDetail(int $orderId): ?Order
    {
        return Order::with([
            'customer:id,name,phone,address',
            'car:id,brand_id,car_model_id,license_plate,manufacture_year,color',
            'car.brand:id,name',
            'car.carModel:id,name',
            'branch:id,name',
            'details.item',
            'transactions'
        ])->find($orderId);
    }

    /**
     * Get payment summary for an order
     * Tính toán tổng tiền, đã thanh toán, hoàn tiền, còn lại
     */
    public function getPaymentSummary(Order $order): array
    {
        return $this->paymentService->getPaymentSummary($order);
    }

    /**
     * Check if order can be paid
     * Kiểm tra order có thể thanh toán được không
     * Điều kiện: status = COMPLETED
     */
    public function canPay(Order $order): bool
    {
        return $order->status === Order::STATUS_COMPLETED;
    }

    /**
     * Check if order can be refunded
     * Kiểm tra order có thể hoàn tiền được không
     * Điều kiện: status = PAID, có transaction type = payment
     */
    public function canRefund(Order $order): bool
    {
        // Status must be PAID
        if ($order->status !== Order::STATUS_PAID) {
            return false;
        }

        // Must have payment transaction
        $hasPayment = $order->transactions()
            ->where('type', 'payment')
            ->exists();

        return $hasPayment;
    }

    /**
     * Process payment for order
     * Xử lý thanh toán tiền mặt
     */
    public function pay(Order $order, string $paymentMethod = 'cash'): array
    {
        // Validate order can be paid
        if (!$this->canPay($order)) {
            throw new Exception('Đơn hàng không thể thanh toán. Kiểm tra trạng thái đơn hàng.');
        }

        // Process payment through PaymentService
        return $this->paymentService->pay($order, $paymentMethod);
    }

    /**
     * Process refund for order
     * Xử lý hoàn tiền
     */
    public function refund(Order $order, array $data = []): array
    {
        // Validate order can be refunded
        if (!$this->canRefund($order)) {
            throw new Exception('Đơn hàng không thể hoàn tiền. Kiểm tra trạng thái đơn hàng.');
        }

        // Process refund through PaymentService
        return $this->paymentService->refund($order, $data);
    }

    /**
     * Get view data for payment detail page
     */
    public function getDetailPageData(int $orderId): ?array
    {
        $order = $this->getOrderDetail($orderId);

        if (!$order) {
            return null;
        }

        return [
            'order' => $order,
            'paymentSummary' => $this->getPaymentSummary($order),
            'canPay' => $this->canPay($order),
            'canRefund' => $this->canRefund($order),
            'paymentMethods' => PaymentService::paymentMethods(),
        ];
    }
}
