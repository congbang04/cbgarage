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
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="fas fa-plus"></i> Nhập hàng mới
                </button>
                <a href="{{ route('warehouse.stock-transfer') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Luân chuyển hàng(Nhập hàng)
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
    <div class="modal fade" id="importModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nhập hàng mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="importForm">
                        <div class="mb-3">
                            <label class="form-label">Chọn phụ tùng</label>
                            <select class="form-select" name="part_id" required>
                                <option value="">-- Chọn --</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số lượng</label>
                            <input type="number" class="form-control" name="quantity" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số lượng tối thiểu</label>
                            <input type="number" class="form-control" name="min_stock" min="0" value="5">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" id="importSubmitBtn">Thêm</button>
                </div>
            </div>
        </div>
    </div>

@endsection
