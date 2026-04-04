@extends('layouts.admin')

@section('title', 'Quản lý thanh toán')

@section('vite')
    @vite(['resources/js/modules/payment/payment-index.js'])
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/payment-index.css') }}">

@endsection

@section('body-class', 'admin-wrapper')

@section('sidebar')
    @include('layouts.partials.admin-sidebar')
@endsection

@section('topbar_actions')
    <div class="d-none d-md-flex gap-2">
        <a href="{{ route('payment.index') }}" class="btn btn-primary">
            <i class="fas fa-refresh"></i> Làm mới
        </a>
    </div>
@endsection

@section('content')
    <div class="main-content">
        @include('layouts.partials.admin-top-navbar', [
            'headerTitle' => 'Quản lý thanh toán',
        ])

        <div class="p-4">
            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card p-3 border-start border-4 border-success">
                        <div class="text-muted small">Tổng thanh toán</div>
                        <h4 class="fw-bold mb-0 text-success">
                            {{ number_format($stats['total_payment'] ?? 0) }} đ
                        </h4>
                        <small class="text-muted">{{ $stats['payment_count'] ?? 0 }} giao dịch</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 border-start border-4 border-warning">
                        <div class="text-muted small">Tổng hoàn tiền</div>
                        <h4 class="fw-bold mb-0 text-warning">
                            {{ number_format($stats['total_refund'] ?? 0) }} đ
                        </h4>
                        <small class="text-muted">{{ $stats['refund_count'] ?? 0 }} giao dịch</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 border-start border-4 border-primary">
                        <div class="text-muted small">Doanh thu ròng</div>
                        <h4 class="fw-bold mb-0 text-primary">
                            {{ number_format($stats['net_revenue'] ?? 0) }} đ
                        </h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 border-start border-4 border-info">
                        <div class="text-muted small">Tỷ suất hoàn tiền</div>
                        <h4 class="fw-bold mb-0 text-info">
                            @php
                                $totalPayment = $stats['total_payment'] ?? 0;
                                $totalRefund = $stats['total_refund'] ?? 0;
                                $rate = $totalPayment > 0 ? round(($totalRefund / $totalPayment) * 100, 2) : 0;
                            @endphp
                            {{ $rate }}%
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-3 fw-bold">Bộ lọc</h6>
                    <form id="paymentFilterForm">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label small">Từ ngày</label>
                                <input type="date" class="form-control" id="filterDateFrom" name="date_from">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">Đến ngày</label>
                                <input type="date" class="form-control" id="filterDateTo" name="date_to">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">Loại giao dịch</label>
                                <select class="form-select" id="filterType" name="type">
                                    <option value="">-- Tất cả --</option>
                                    <option value="payment">Thanh toán</option>
                                    <option value="refund">Hoàn tiền</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">Hình thức thanh toán</label>
                                <select class="form-select" id="filterPaymentMethod" name="payment_method">
                                    <option value="">-- Tất cả --</option>
                                    <option value="cash">Tiền mặt</option>
                                </select>
                            </div>

                            @if (auth()->user()->role === 'BRANCH_ADMIN' && auth()->user()->branch_id == 1)
                                <div class="col-md-3">
                                    <label class="form-label small">Chi nhánh</label>
                                    <select class="form-select" id="filterBranch" name="branch_id">
                                        <option value="">-- Tất cả --</option>
                                    </select>
                                </div>
                            @endif

                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i> Tìm kiếm
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="card">
                <div class="table-responsive" id="paymentTableContainer">
                    {{-- Table will be loaded via AJAX --}}
                    <div class="p-4 text-center">
                        <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
