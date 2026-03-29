@extends('layouts.app')
@section('title', 'Báo giá')
@section('vite')
    @vite(['resources/js/modules/car-quotation.js'])
@endsection
@section('styles')
    <link rel="stylesheet" href={{ asset('assets/css/design-system.css') }}>
    <link rel="stylesheet" href={{ asset('assets/css/admin.css') }}>
@endsection

@section('content')
    <div class="main-content" id="quotation-container">
        <header class="top-navbar justify-content-between">
            <div class="d-flex align-items-center">
                <a href="{{ route('customer.carDetail', $carId) }}" class="btn btn-light me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h4 class="mb-0 fw-bold" id="quotation-title">
                        Báo giá #QT-<span
                            id="order-id-display">{{ $order?->id ? str_pad($order->id, 4, '0', STR_PAD_LEFT) : 'XXXX' }}</span>
                    </h4>
                    @if ($order)
                        <small class="text-muted" id="quotation-subtitle">
                            {{ $order->car->brand->name ?? '' }} {{ $order->car->carModel->name ?? '' }}
                            - {{ $order->car->license_plate ?? '' }}
                            ({{ $order->customer->name ?? '' }})
                        </small>
                    @else
                        <small class="text-muted" id="quotation-subtitle">Chọn dịch vụ/phụ tùng để bắt đầu</small>
                    @endif
                </div>
            </div>
            <div>
                <span class="badge bg-warning text-dark fs-6 px-3"
                    id="quotation-status">{{ $order?->status ?? 'Chờ báo giá' }}</span>
            </div>
        </header>

        <div class="p-4">
            <div class="row g-4">
                <!-- Bảng chi tiết báo giá -->
                <div class="col-lg-9">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
                            <h5 class="mb-0">Chi tiết Hạng mục</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="width: 50px;">#</th>
                                            <th>Mã & Tên hạng mục</th>
                                            <th class="text-start ps-4">Loại</th>
                                            <th style="width: 100px;">ĐVT</th>
                                            <th style="width: 120px;">Số lượng</th>
                                            <th class="text-end">Đơn giá</th>
                                            <th class="text-end">Thành tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody id="quotation-items">
                                        <!-- Items added dynamically -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cột Phải: Tổng tiền & Action -->
                <div class="col-lg-3">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">Tổng hợp chi phí</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Tổng tiền hàng:</span>
                                <span class="total-goods">0</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Tổng tiền công:</span>
                                <span class="total-labor">0</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">VAT (8%):</span>
                                <span class="total-vat">0</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold">Thanh toán:</span>
                                <h4 class="fw-bold text-primary mb-0 total-payment">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
