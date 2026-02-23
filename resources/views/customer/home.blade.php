@extends('layouts.app')
@section('title', 'Trang chủ')
@section('styles')
    <link rel="stylesheet" href={{ asset('assets/css/design-system.css') }}>
    <link rel="stylesheet" href={{ asset('assets/css/customer.css') }}>
@section('header')
    @include('layouts.partials.navbar')
    @include('layouts.partials.header')
@section('content')
    <!-- Main Content -->
    <main class="container">

        <!-- Section: Xe của tôi -->
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0">Xe của tôi</h3>
                <a href="profile.html" class="text-primary text-decoration-none">Xem tất cả <i
                        class="fas fa-arrow-right small"></i></a>
            </div>

            <div class="row g-3">
                <!-- Car Card 1 -->
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="bg-light rounded p-3 me-3">
                                <i class="fas fa-car fa-2x text-secondary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-1">Toyota Vios</h5>
                                <div class="text-muted small mb-1">Biển số: <span
                                        class="fw-bold text-dark">51G-123.45</span></div>
                                <span class="badge bg-success-subtle text-success">Hoạt động</span>
                            </div>
                            <a href="car-detail.html" class="btn btn-outline-secondary btn-sm rounded-pill">Chi tiết</a>
                        </div>
                    </div>
                </div>
                <!-- Car Card 2 -->
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="bg-light rounded p-3 me-3">
                                <i class="fas fa-truck-pickup fa-2x text-secondary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-1">Ford Ranger</h5>
                                <div class="text-muted small mb-1">Biển số: <span
                                        class="fw-bold text-dark">59C-999.88</span></div>
                                <span class="badge bg-warning-subtle text-warning">Đến hạn bảo dưỡng</span>
                            </div>
                            <a href="car-detail.html" class="btn btn-outline-secondary btn-sm rounded-pill">Chi tiết</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section: Dịch vụ nổi bật -->
        <section>
            <h3 class="fw-bold mb-4">Dịch vụ nổi bật</h3>
            <div class="row g-3 row-cols-2 row-cols-md-4">
                <div class="col">
                    <div class="card service-card p-3 text-center h-100 border-0 shadow-sm">
                        <div class="service-icon"><i class="fas fa-oil-can"></i></div>
                        <h6 class="fw-bold">Thay dầu máy</h6>
                        <p class="text-muted small mb-0">Từ 450.000đ</p>
                    </div>
                </div>
                <div class="col">
                    <div class="card service-card p-3 text-center h-100 border-0 shadow-sm">
                        <div class="service-icon"><i class="fas fa-tools"></i></div>
                        <h6 class="fw-bold">Bảo dưỡng phanh</h6>
                        <p class="text-muted small mb-0">Từ 300.000đ</p>
                    </div>
                </div>
                <div class="col">
                    <div class="card service-card p-3 text-center h-100 border-0 shadow-sm">
                        <div class="service-icon"><i class="fas fa-spray-can"></i></div>
                        <h6 class="fw-bold">Đồng sơn</h6>
                        <p class="text-muted small mb-0">Liên hệ</p>
                    </div>
                </div>
                <div class="col">
                    <div class="card service-card p-3 text-center h-100 border-0 shadow-sm">
                        <div class="service-icon"><i class="fas fa-snowflake"></i></div>
                        <h6 class="fw-bold">Vệ sinh điều hòa</h6>
                        <p class="text-muted small mb-0">Từ 800.000đ</p>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Chat AI FAB -->
    <a href="chat.html" class="fab-btn text-decoration-none">
        <i class="fas fa-robot"></i>
    </a>

    <!-- Navigation Bottom (Mobile Only) -->
    <nav class="navbar fixed-bottom navbar-light bg-white border-top d-md-none">
        <div class="container-fluid justify-content-around">
            <a href="home.html" class="text-center text-primary text-decoration-none">
                <i class="fas fa-home fs-5"></i>
                <div class="small" style="font-size: 10px;">Trang chủ</div>
            </a>
            <a href="booking.html" class="text-center text-secondary text-decoration-none">
                <i class="fas fa-calendar-plus fs-5"></i>
                <div class="small" style="font-size: 10px;">Đặt lịch</div>
            </a>
            <a href="profile.html" class="text-center text-secondary text-decoration-none">
                <i class="fas fa-user fs-5"></i>
                <div class="small" style="font-size: 10px;">Tài khoản</div>
            </a>
        </div>
    </nav>
@endsection
