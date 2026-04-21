@extends('layouts.admin')

@section('title', 'CBAUTO - Quản lý thanh toán')

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
            <div class="row g-4 mb-4" id="stats-cards">
                {{-- ajax render --}}
            </div>

            <!-- Filter Section -->
            <div class="card mb-4 shadow p-3 mb-5 bg-body-tertiary rounded">
                <div class="card-header bg-white py-3">
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
                                    <option value="bank_transfer">Chuyển khoản</option>
                                    <option value="card">Thẻ</option>
                                    <option value="e-wallet">Ví điện tử</option>
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
                                <button type="submit" class="btn btn-primary w-60 me-2">
                                    <i class="fas fa-search"></i> Tìm kiếm
                                </button>
                                <button type="reset" class="btn btn-secondary w-60">
                                    <i class="fas fa-search"></i> Đặt lại
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="card shadow p-3 mb-5 bg-body-tertiary rounded">
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
