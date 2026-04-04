@extends('layouts.admin')

@section('title', 'Chi tiết thanh toán đơn hàng #' . $order->id)

@section('vite')
    @vite(['resources/js/modules/payment/payment-detail.js'])
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/payment-detail.css') }}">

@endsection

@section('body-class', 'admin-wrapper')

@section('sidebar')
    @include('layouts.partials.admin-sidebar')
@endsection

@section('content')
    <div class="main-content">
        @include('layouts.partials.admin-top-navbar', ['headerTitle' => 'Chi tiết thanh toán'])

        <div class="p-4">
            <!-- Order Header Info -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="info-box">
                        <div class="small opacity-75">Mã đơn hàng</div>
                        <h3 class="mb-2">#ORD-{{ $order->id }}</h3>
                        <div class="small">
                            <i class="fas fa-building"></i> {{ $order->branch?->name ?? 'N/A' }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-box success">
                        <div class="small opacity-75">Tổng tiền dịch vụ</div>
                        <h3 class="mb-2">{{ number_format($order->total_amount) }} đ</h3>
                        @php
                            $badgeClass = match ($order->status) {
                                'COMPLETED' => '<span class="status-badge info fw-bold">Chờ thanh toán</span>',

                                'PAID' => '<span class="status-badge bg-success text-light">Đã thanh toán</span>',

                                default => '<span class="status-badge bg-dark text-light">Không xác định</span>',
                            };
                        @endphp
                        {!! $badgeClass !!}
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="detail-section">
                <div class="detail-header">
                    <h5><i class="fas fa-user me-2"></i>Thông tin khách hàng</h5>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tên khách hàng</span>
                    <span class="detail-value">{{ $order->customer?->name ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Số điện thoại</span>
                    <span class="detail-value">{{ $order->customer?->phone ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Xe (Biển số)</span>
                    <span class="detail-value">{{ $order->car?->license_plate ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Ngày tạo đơn</span>
                    <span class="detail-value">{{ $order->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</span>
                </div>
            </div>

            <!-- Service Details -->
            <div class="detail-section">
                <div class="detail-header">
                    <h5><i class="fas fa-list me-2"></i>Chi tiết dịch vụ</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Dịch vụ</th>
                                <th class="text-end">Số lượng</th>
                                <th class="text-end">Đơn giá</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($order->details as $detail)
                                <tr>
                                    <td>{{ $detail->item?->name ?? 'N/A' }}</td>
                                    <td class="text-end">{{ $detail->quantity }}</td>
                                    <td class="text-end">{{ number_format($detail->price, 0, ',', '.') }} đ</td>
                                    <td class="text-end fw-bold">{{ number_format($detail->price, 0, ',', '.') }} đ</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Chưa có chi tiết dịch vụ</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="detail-section">
                <div class="detail-header">
                    <h5><i class="fas fa-chart-pie me-2"></i>Tóm tắt thanh toán</h5>
                </div>
                <div class="detail-row align-items-center">
                    <span class="detail-label">Tổng tiền dịch vụ</span>
                    <span class="detail-value fw-bold">
                        {{ number_format($paymentSummary['order_amount'], 0, ',', '.') }} đ
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Đã thanh toán</span>
                    <span class="detail-value text-success fw-bold">+
                        {{ number_format($paymentSummary['total_paid'], 0, ',', '.') }}
                        đ</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Hoàn tiền</span>
                    <span class="detail-value text-danger fw-bold">-
                        {{ number_format($paymentSummary['total_refunded'], 0, ',', '.') }}
                        đ</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label fw-bold">Còn lại phải thanh toán</span>
                    @if ($paymentSummary['remaining'] > 0)
                        <span
                            class="detail-value text-danger fw-bold">{{ number_format($paymentSummary['remaining'], 0, ',', '.') }}
                            đ</span>
                    @else
                        <span class="detail-value text-success fw-bold">Đã thanh toán xong</span>
                    @endif
                </div>
            </div>

            <!-- Transaction History - Chỉ hiện khi có transaction -->
            @if (count($order->transactions) > 0)
                <div class="detail-section">
                    <div class="detail-header">
                        <h5><i class="fas fa-history me-2"></i>Lịch sử giao dịch</h5>
                    </div>
                    @forelse ($order->transactions as $transaction)
                        <div class="transaction-item {{ $transaction->type }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-bold">
                                        {{ $transaction->type === 'payment' ? 'Thanh toán' : 'Hoàn tiền' }}
                                    </div>
                                    <div class="small text-muted mt-1">
                                        Mã: {{ $transaction->transaction_code }}
                                        <br>
                                        Hình thức:
                                        {{ $paymentMethods[$transaction->payment_method] ?? $transaction->payment_method }}
                                        <br>
                                        Thời gian:
                                        {{ $transaction->created_at ? \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y H:i') : 'N/A' }}
                                        @if ($transaction->reason)
                                            <br>
                                            <strong>Lý do:</strong> {{ $transaction->reason }}
                                        @endif
                                        @if ($transaction->notes)
                                            <br>
                                            <strong>Ghi chú:</strong> {{ $transaction->notes }}
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    @if ($transaction->type === 'payment')
                                        <span class="amount-positive">+{{ number_format($transaction->amount) }} đ</span>
                                    @else
                                        <span class="amount-negative">-{{ number_format($transaction->amount) }} đ</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>
            @endif

            <!-- Payment Actions -->
            @if ($canPay || $canRefund)
                <div class="detail-section">
                    <div class="detail-header">
                        <h5><i class="fas fa-credit-card me-2"></i>Thực hiện thanh toán</h5>
                    </div>

                    @if ($canPay)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Chế độ thanh toán:</strong> Chọn hình thức thanh toán và nhấn nút để hoàn thành
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label fw-bold mb-3">Chọn hình thức thanh toán</label>
                            <div class="payment-methods-group" id="paymentMethods">
                                @foreach ($paymentMethods as $method => $label)
                                    <div class="payment-method-btn {{ $loop->first ? 'active' : '' }}"
                                        data-method="{{ $method }}">
                                        @if ($method === 'cash')
                                            <i class="fas fa-money-bill-wave fa-2x mb-2 d-block"></i>
                                        @elseif ($method === 'bank_transfer')
                                            <i class="fas fa-university fa-2x mb-2 d-block"></i>
                                        @else
                                            <i class="fas fa-credit-card fa-2x mb-2 d-block"></i>
                                        @endif
                                        {{ $label }}
                                    </div>
                                @endforeach
                            </div>
                            <input type="hidden" id="selectedPaymentMethod" value="cash">
                        </div>

                        <div class="action-buttons">
                            <button type="button" class="btn btn-success btn-lg btn-process-payment"
                                data-order-id="{{ $order->id }}">
                                <i class="fas fa-check-circle"></i> Xác nhận thanh toán
                                {{ number_format($order->total_amount) }} đ
                            </button>
                        </div>
                    @endif

                    @if ($canRefund)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Cảnh báo:</strong> Bạn có thể hoàn tiền cho đơn hàng này
                        </div>

                        <div class="action-buttons">
                            <button type="button" class="btn btn-warning btn-lg btn-refund-order" data-bs-toggle="modal"
                                data-bs-target="#refundModal" data-order-id="{{ $order->id }}">
                                <i class="fas fa-undo"></i> Hoàn tiền
                            </button>
                        </div>
                    @endif
                </div>
            @endif

            @if (!$canPay && !$canRefund)
                <div class="alert alert-secondary">
                    <i class="fas fa-info-circle"></i>
                    <strong>Không thể thực hiện thanh toán/hoàn tiền:</strong> Trạng thái hiện tại của đơn hàng không cho
                    phép. Chỉ có thể thanh toán khi trạng thái là "COMPLETED" hoặc hoàn tiền khi là "PAID".
                </div>
            @endif
        </div>
    </div>
    @include('admin.payment.partials.refund-modal')
@endsection
