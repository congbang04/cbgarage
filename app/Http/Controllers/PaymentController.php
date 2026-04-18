<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Http\Requests\PaymentDetailRequest;
use App\Models\Order;
use App\Services\PaymentDetailService;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentDetailService;
    protected $transactionService;

    public function __construct(
        PaymentDetailService $paymentDetailService,
        TransactionService $transactionService
    ) {
        $this->paymentDetailService = $paymentDetailService;
        $this->transactionService = $transactionService;
    }

    /**
     * Display payments list page
     */
    public function index(Request $request)
    {
        $filters = $request->only(['date_from', 'date_to', 'payment_method', 'type', 'branch_id']);
        $stats = $this->transactionService->getTransactionStats($filters);

        return view('admin.payment.index', compact('stats'));
    }

    /**
     * Display transactions table via AJAX
     */
    public function table(Request $request)
    {
        $filters = $request->only(['date_from', 'date_to', 'payment_method', 'type', 'branch_id']);
        $perPage = $request->get('per_page', 20);

        $transactions = $this->transactionService->getAllTransactions($filters, $perPage);

        return view('admin.payment.table', compact('transactions'));
    }
    public function stats(Request $request)
    {
        $filters = $request->only(['date_from', 'date_to', 'payment_method', 'type', 'branch_id']);

        $stats = $this->transactionService->getTransactionStats($filters);

        return view('admin.payment.partials.stats-cards', compact('stats'));
    }

    /**
     * Process payment (Cash payment)
     */
    public function pay(PaymentRequest $request)
    {
        try {
            $order = Order::findOrFail($request->order_id);
            $result = $this->paymentDetailService->pay($order, 'cash');
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Process refund
     */
    public function refund(PaymentRequest $request)
    {
        try {
            $order = Order::findOrFail($request->order_id);
            $result = $this->paymentDetailService->refund($order, $request->only('reason', 'notes'));
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Display payment detail page for a specific order
     */
    public function detail(String $id)
    {
        try {
            $data = $this->paymentDetailService->getDetailPageData($id);

            if (!$data) {
                return redirect()->route('payment.index')->with('error', 'Đơn hàng không tồn tại!');
            }

            return view('admin.payment.detail', $data);
        } catch (\Exception $e) {
            return redirect()->route('payment.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Process payment with custom payment method
     */
    public function processPayment(PaymentDetailRequest $request)
    {
        try {
            $order = Order::findOrFail($request->order_id);
            $paymentMethod = $request->get('payment_method', 'cash');
            $result = $this->paymentDetailService->pay($order, $paymentMethod);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get order payment details
     */
    public function getOrderDetails(Request $request)
    {
        try {
            $order = Order::findOrFail($request->order_id);
            $orderData = $this->paymentDetailService->getOrderDetail($order->id);
            $paymentSummary = $this->paymentDetailService->getPaymentSummary($order);

            return response()->json([
                'success' => true,
                'order' => $orderData,
                'summary' => $paymentSummary,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get list of branches
     */
    public function getBranches()
    {
        $branches = \App\Models\Branch::where('is_actived', true)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json($branches);
    }
}
