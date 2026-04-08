@extends('layouts.admin')

@section('title', 'Trang quản trị')

@section('vite')
    @vite(['resources/js/modules/admin-dashboard.js', 'resources/js/modules/admin-dashboard-chart.js'])
@endsection

@section('styles')
    <link rel="stylesheet" href={{ asset('assets/css/design-system.css') }}>
    <link rel="stylesheet" href={{ asset('assets/css/admin.css') }}>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endsection

@section('body-class', 'admin-wrapper')

@section('sidebar')
    @include('layouts.partials.admin-sidebar')
@endsection

@section('content')
    <!-- MAIN CONTENT -->
    <div class="main-content">
        @include('layouts.partials.admin-top-navbar', ['headerTitle' => 'Bảng điều khiển'])

        <!-- Dashboard Content -->
        <div class="p-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0 fw-bold">Tổng quan theo ngày</h4>
                <div class="d-flex gap-2">
                    <button id="btn-prev-day" class="btn-date-nav" title="Ngày hôm trước">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <input type="date" class="form-control form-control-sm" value="{{ now()->format('Y-m-d') }}">
                    <button id="btn-next-day" class="btn-date-nav" title="Ngày hôm sau">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            <!-- Stats Widgets -->
            <div class="row g-4 mb-4">
                <!-- Doanh thu -->
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="stat-card">
                        <div class="stat-content">
                            <h6>Doanh thu ngày</h6>
                            <h3 id="daily-revenue-amount">...</h3>
                            <div class="stat-trend up">
                                <i class="fas fa-arrow-up"></i> Cập nhật từ transaction
                            </div>
                        </div>
                        <div class="stat-icon-wrapper bg-success bg-opacity-10 text-success">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
                <!-- Xe tại xưởng -->
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="stat-card">
                        <div class="stat-content">
                            <h6>Xe tại xưởng</h6>
                            <h3 id="in-workshop-count">{{ $carStats['in_workshop'] }} xe</h3>
                            <div class="stat-trend text-muted" id="waiting-count">
                                {{ $carStats['waiting'] }} xe đang chờ
                            </div>
                        </div>
                        <div class="stat-icon-wrapper bg-primary bg-opacity-10 text-primary">
                            <i class="fas fa-car"></i>
                        </div>
                    </div>
                </div>
                <!-- Lịch hẹn mới -->
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="stat-card">
                        <div class="stat-content">
                            <h6>Lịch đặt mới</h6>
                            <h3 id="pending-bookings-count">{{ $carStats['pending_bookings'] }} lịch</h3>
                            <div class="stat-trend text-warning">
                                Cần xác nhận ngay
                            </div>
                        </div>
                        <div class="stat-icon-wrapper bg-warning bg-opacity-10 text-warning">
                            <i class="far fa-calendar-alt"></i>
                        </div>
                    </div>
                </div>
                <!-- Cảnh báo kho -->
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="stat-card">
                        <div class="stat-content">
                            <h6>Cảnh báo kho</h6>
                            <h3 id="low-stock-parts-count">{{ $carStats['low_stock_parts'] }} mã</h3>
                            <div class="stat-trend down">
                                Dưới định mức
                            </div>
                        </div>
                        <div class="stat-icon-wrapper bg-danger bg-opacity-10 text-danger">
                            <i class="fas fa-box-open"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <div class="card h-100">
                        <div class="card-header border-0 bg-white">
                            <h5 class="mb-0">Biểu đồ doanh thu tuần</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="revenue-chart" height="80"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-header border-0 bg-white">
                            <h5 class="mb-0">Tỷ trọng dịch vụ</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="service-chart" height="80"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- End Dashboard Content -->
    </div> <!-- End Main Content -->
@endsection
