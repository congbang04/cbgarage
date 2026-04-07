@extends('layouts.admin')
@section('title', 'Báo giá')
@section('vite')
    @vite(['resources/js/modules/reception/quotation-app.js'])
@endsection
@section('styles')
    <link rel="stylesheet" href={{ asset('assets/css/design-system.css') }}>
    <link rel="stylesheet" href={{ asset('assets/css/admin.css') }}>
@endsection
@section('body-class', 'admin-wrapper')
@section('sidebar')
    @include('layouts.partials.admin-sidebar')
@endsection
@section('content')
    <div class="main-content" id="quotation-container">
        <header class="top-navbar justify-content-between">
            <div class="d-flex align-items-center">
                <a href="{{ route('reception.list') }}" class="btn btn-light me-3"><i class="fas fa-arrow-left"></i></a>
                <div>
                    <h4 class="mb-0 fw-bold" id="quotation-title">
                        Lập báo giá #QT-<span
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
            <div class="d-flex align-items-center">
                @if ($order->status === \App\Models\Order::STATUS_COMPLETED)
                    <button class="btn btn-success btn-pay me-3" data-order-id="{{ $order->id }}">
                        <i class="fas fa-money-bill-wave"></i> Thanh toán
                    </button>
                @endif
                @php
                    $badgeClass = match ($order->status) {
                        'RECEIVED' => '<span class="status-badge fs-6 px-3 fw-bold info">Đã tiếp nhận</span>',

                        'QUOTED' => '<span class="status-badge fs-6 px-3 fw-bold warning">Đã báo giá</span>',

                        'IN_PROGRESS'
                            => '<span class="status-badge fs-6 px-3 fw-bold bg-primary-subtle text-primary">Đang thực hiện</span>',

                        'WAITING_PART'
                            => '<span class="status-badge fs-6 px-3 fw-bold bg-secondary-subtle text-secondary">Chờ phụ tùng</span>',

                        'COMPLETED' => '<span class="status-badge fs-6 px-3 fw-bold success">Hoàn thành</span>',

                        'PAID'
                            => '<span class="status-badge fs-6 px-3 fw-bold bg-success text-light">Đã thanh toán</span>',

                        'CANCELLED' => '<span class="status-badge fs-6 px-3 fw-bold danger">Đã huỷ</span>',

                        default
                            => '<span class="status-badge fs-6 px-3 fw-bold bg-dark text-light">Không xác định</span>',
                    };
                @endphp
                <div>
                    <span id="quotation-status">
                        {!! $badgeClass !!}
                    </span>
                </div>
            </div>
        </header>

        <div class="p-4">
            <div class="row g-4">
                <!-- Bảng chi tiết báo giá -->
                <div class="col-lg-9">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
                            <h5 class="mb-0">Chi tiết Hạng mục</h5>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addItemModal">
                                <i class="fas fa-plus me-1"></i> Thêm Phụ tùng / Dịch vụ
                            </button>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-center" style="width: 50px;">#</th>
                                            <th class="text-start">Mã & Tên hạng mục</th>
                                            <th class="text-center">Loại</th>
                                            <th class="text-center">ĐVT</th>
                                            <th class="text-center">Số lượng</th>
                                            <th class="text-center">Đơn giá</th>
                                            <th class="text-end">Thành tiền</th>
                                            <th class="text-end"></th>
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
                    <form id="quotation-form">
                        <input type="hidden" name="order_id" value="{{ $order?->id }}">

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
                                <hr>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Thanh toán:</span>
                                    <h4 class="fw-bold text-primary mb-0 total-payment">0</h4>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body d-grid gap-2">
                                <button type="button" class="btn btn-success" id="btn-send-message">
                                    <i class="fab fa-whatsapp me-2"></i> Gửi khách duyệt
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="btn-print-quotation">
                                    <i class="fas fa-print me-2"></i> In báo giá
                                </button>
                                <hr class="my-2">
                                <div class="mb-2">
                                    <label class="form-label small fw-bold">Gán Kỹ thuật viên</label>
                                    <select name="technician_id" class="form-select form-select-sm">
                                        <option value="">Chọn thợ chính...</option>
                                    </select>
                                </div>
                                <button type="submit" id="btn-save-quotation" class="btn btn-primary mt-2">
                                    <i class="fas fa-save me-2"></i> Lưu báo giá
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add Item -->
    <div class="modal fade" id="addItemModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm Phụ tùng / Dịch vụ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tab-services" data-bs-toggle="tab"
                                data-bs-target="#services-tab" type="button" role="tab">
                                <i class="fas fa-hammer me-2"></i> Dịch vụ
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-parts" data-bs-toggle="tab" data-bs-target="#parts-tab"
                                type="button" role="tab">
                                <i class="fas fa-boxes me-2"></i> Phụ tùng
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Services Tab -->
                        <div class="tab-pane fade show active" id="services-tab" role="tabpanel">
                            <div class="mb-3">
                                <input type="text" class="form-control" id="search-services"
                                    placeholder="Tìm dịch vụ...">
                            </div>
                            <div id="services-list" class="list-group">
                                <!-- Services loaded dynamically -->
                            </div>
                        </div>

                        <!-- Parts Tab -->
                        <div class="tab-pane fade" id="parts-tab" role="tabpanel">
                            <div class="mb-3">
                                <input type="text" class="form-control" id="search-parts"
                                    placeholder="Tìm phụ tùng...">
                            </div>
                            <div id="parts-list" class="list-group">
                                <!-- Parts loaded dynamically -->
                            </div>
                        </div>
                    </div>

                    <!-- Quantity and Price Input -->
                    <div class="mt-4 pt-3 border-top" id="item-form-section" style="display: none;">
                        <h6 class="fw-bold mb-3">Chi tiết hạng mục</h6>
                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Số lượng</label>
                                <input type="number" class="form-control" id="add-item-quantity" value="1"
                                    min="1">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Đơn giá (₫)</label>
                                <input type="number" class="form-control" id="add-item-price" value="0"
                                    min="0">
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-primary flex-grow-1" id="btn-confirm-item">
                                <i class="fas fa-check me-2"></i> Thêm vào báo giá
                            </button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Hủy
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
