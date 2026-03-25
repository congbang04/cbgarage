@extends('layouts.admin')
@section('title', 'Quản lý tồn kho')
@section('vite')
    @vite(['resources/js/modules/warehouse/inventory.js'])
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
@endsection
@section('body-class', 'admin-wrapper')
@section('sidebar')
    @include('layouts.partials.admin-sidebar')
@endsection
@section('content')
    <div class="main-content">
        <header class="top-navbar">
            <h4 class="mb-0 fw-bold">Quản lý tồn kho</h4>
            <div class="d-flex gap-2">
                <button class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#receiveTransferModal">
                    <i class="fas fa-truck-loading"></i> Nhận hàng luân chuyển
                </button>
                <a href="{{ route('warehouse.stock-transfer') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Yêu cầu luân chuyển
                </a>
                <a href="{{ route('warehouse.part-requests') }}" class="btn btn-outline-primary position-relative">
                    <i class="fas fa-bell"></i> Yêu cầu xuất
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                        id="pendingRequestBadge">0</span>
                </a>
            </div>
        </header>

        <div class="p-4">
            <!-- Stats -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card p-3 border-start border-4 border-primary">
                        <div class="text-muted small">Tổng giá trị kho</div>
                        <h4 class="fw-bold mb-0" id="totalValue">{{ number_format($stats['total_value'] ?? 0) }} đ</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 border-start border-4 border-danger">
                        <div class="text-muted small">Sắp hết hàng</div>
                        <h4 class="fw-bold mb-0 text-danger" id="lowStockCount">{{ $stats['low_stock_count'] ?? 0 }} mã
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="card">
                <div class="card-header bg-white py-3">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" class="form-control" id="searchInput"
                                    placeholder="Tìm tên, mã SKU...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="statusFilter">
                                <option value="">-- Tất cả trạng thái --</option>
                                <option value="in_stock">Còn hàng</option>
                                <option value="low_stock">Sắp hết</option>
                                <option value="empty">Hết hàng</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control" id="priceFromFilter" placeholder="Giá từ...">
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control" id="priceToFilter" placeholder="Giá đến...">
                        </div>
                    </div>
                </div>
                <div class="table-responsive" id="inventoryTableContainer">
                    {{-- Ajax render --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Import Modal -->
    <div class="modal fade" id="receiveTransferModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Danh sách hàng đang luân chuyển tới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>Mã phiếu</th>
                                    <th>Từ kho</th>
                                    <th>Chi tiết vật tư</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="incomingTransfersBody">
                                <tr>
                                    <td colspan="4" class="text-center">Đang tải dữ liệu...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
