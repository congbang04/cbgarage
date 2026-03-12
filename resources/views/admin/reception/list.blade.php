@extends('layouts.admin')
@section('title', 'Tiếp nhận xe')
@section('vite')
    {{-- @vite(['resources/js/modules/booking.js', 'resources/js/modules/booking-lock.js', 'resources/js/modules/booking-tooltip.js']) --}}
@endsection
@section('styles')
    <link rel="stylesheet" href={{ asset('assets/css/design-system.css') }}>
    <link rel="stylesheet" href={{ asset('assets/css/admin.css') }}>
@endsection
@section('body-class', 'admin-wrapper')
@section('content')
    <div class="main-content">
        <header class="top-navbar">
            <h4 class="mb-0 fw-bold">Quản lý tiếp nhận</h4>
            <div class="user-dropdown">
                <img src="https://ui-avatars.com/api/?name=Admin&background=random" class="user-avatar" alt="Admin">
            </div>
        </header>

        <div class="p-4">
            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" class="form-control" placeholder="Tìm biển số, tên khách...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select">
                                <option value="">Tất cả trạng thái</option>
                                <option value="pending">Chờ báo giá</option>
                                <option value="fixing">Đang sửa</option>
                                <option value="done">Chờ giao xe</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control">
                        </div>
                        <div class="col-md-2 text-end">
                            <a href="{{ route('reception.create') }}" class="btn btn-primary w-100"><i
                                    class="fas fa-plus"></i> Tiếp
                                nhận</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Mã phiếu</th>
                                <th>Xe & Khách hàng</th>
                                <th>Cố vấn</th>
                                <th>Ngày nhận</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold text-primary">#ORD-8821</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded p-2 me-3"><i class="fas fa-car"></i></div>
                                        <div>
                                            <div class="fw-bold">Toyota Vios - 51G-123.45</div>
                                            <div class="small text-muted">Nguyễn Văn A - 0909.123.456</div>
                                        </div>
                                    </div>
                                </td>
                                <td>Tuấn Anh</td>
                                <td>25/10/2023 08:30</td>
                                <td><span class="status-badge warning">Chờ báo giá</span></td>
                                <td>
                                    <a href="{{ route('reception.quotation') }}" class="btn btn-sm btn-outline-primary"><i
                                            class="fas fa-file-invoice-dollar"></i> Báo giá</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-primary">#ORD-8820</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded p-2 me-3"><i class="fas fa-truck-pickup"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">Ford Ranger - 59C-999.88</div>
                                            <div class="small text-muted">Trần Thị B - 0988.777.666</div>
                                        </div>
                                    </div>
                                </td>
                                <td>Minh Hùng</td>
                                <td>25/10/2023 09:00</td>
                                <td><span class="status-badge info">Đang sửa</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i> Chi
                                        tiết</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white border-top-0 d-flex justify-content-end py-3">
                    <nav>
                        <ul class="pagination mb-0">
                            <li class="page-item disabled"><a class="page-link" href="#">Trước</a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">Sau</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection
