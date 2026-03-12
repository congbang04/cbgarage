@extends('layouts.admin')
@section('title', 'Trang quản trị')
@section('vite')
    {{-- @vite(['resources/js/modules/booking.js', 'resources/js/modules/booking-lock.js', 'resources/js/modules/booking-tooltip.js']) --}}
@endsection
@section('styles')
    <link rel="stylesheet" href={{ asset('assets/css/design-system.css') }}>
    <link rel="stylesheet" href={{ asset('assets/css/admin.css') }}>
@endsection
@section('body-class', 'admin-wrapper')
@section('content')
    <!-- MAIN CONTENT -->
    <div class="main-content">

        <!-- Topbar -->
        <header class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light d-lg-none"><i class="fas fa-bars"></i></button>
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" class="form-control" placeholder="Tìm kiếm phiếu, biển số...">
                </div>
            </div>

            <div class="d-flex align-items-center gap-4">
                <div class="position-relative">
                    <i class="far fa-bell fs-5 text-secondary"></i>
                    <span
                        class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                </div>
                <div class="user-dropdown">
                    <div class="text-end d-none d-md-block">
                        <div class="fw-bold text-dark">Admin Quản lý</div>
                        <small class="text-muted">Chi nhánh Quận 7</small>
                    </div>
                    <img src="https://ui-avatars.com/api/?name=Admin+User&background=0D8ABC&color=fff" class="user-avatar"
                        alt="Admin">
                </div>
            </div>
        </header>

        <!-- Dashboard Content -->
        <div class="p-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0 fw-bold">Tổng quan hôm nay</h4>
                <div class="d-flex gap-2">
                    <input type="date" class="form-control form-control-sm" value="2023-10-25">
                    <button class="btn btn-primary btn-sm"><i class="fas fa-filter me-1"></i> Lọc</button>
                </div>
            </div>

            <!-- Stats Widgets -->
            <div class="row g-4 mb-4">
                <!-- Doanh thu -->
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="stat-card">
                        <div class="stat-content">
                            <h6>Doanh thu ngày</h6>
                            <h3>12.5tr</h3>
                            <div class="stat-trend up">
                                <i class="fas fa-arrow-up"></i> 15% so với hqua
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
                            <h3>8 xe</h3>
                            <div class="stat-trend text-muted">
                                4 xe đang chờ
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
                            <h3>5 lịch</h3>
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
                            <h3>3 mã</h3>
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
                            <!-- Placeholder cho ChartJS -->
                            <div class="chart-container">
                                <i class="fas fa-chart-area fs-1 mb-2"></i>
                                <span>Khu vực hiển thị biểu đồ Chart.js</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-header border-0 bg-white">
                            <h5 class="mb-0">Tỷ trọng dịch vụ</h5>
                        </div>
                        <div class="card-body">
                            <!-- Placeholder cho Pie Chart -->
                            <div class="chart-container">
                                <i class="fas fa-chart-pie fs-1 mb-2"></i>
                                <span>Biểu đồ tròn phân bổ</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Table -->
            <div class="card">
                <div class="card-header border-0 bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Xe đang xử lý gần nhất</h5>
                    <a href="reception/list.html" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Mã phiếu</th>
                                    <th>Khách hàng & Xe</th>
                                    <th>Dịch vụ chính</th>
                                    <th>Cố vấn / Thợ</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-bold text-primary">#ORD-8821</td>
                                    <td>
                                        <div class="fw-bold">Nguyễn Văn A</div>
                                        <div class="small text-muted">Vios - 51G-123.45</div>
                                    </td>
                                    <td>Thay dầu, Lọc nhớt</td>
                                    <td>
                                        <div class="small">CVDV: Tuấn Anh</div>
                                        <div class="small text-muted">Thợ: Hùng</div>
                                    </td>
                                    <td><span class="status-badge info">Đang sửa</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-light border"><i class="fas fa-eye"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-primary">#ORD-8822</td>
                                    <td>
                                        <div class="fw-bold">Trần Thị B</div>
                                        <div class="small text-muted">CX-5 - 30F-999.88</div>
                                    </td>
                                    <td>Sơn cản sau</td>
                                    <td>
                                        <div class="small">CVDV: Minh</div>
                                        <div class="small text-muted">Thợ: Chưa gán</div>
                                    </td>
                                    <td><span class="status-badge warning">Chờ duyệt giá</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-light border"><i class="fas fa-eye"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-primary">#ORD-8820</td>
                                    <td>
                                        <div class="fw-bold">Lê Văn C</div>
                                        <div class="small text-muted">Ranger - 59C-123.12</div>
                                    </td>
                                    <td>Bảo dưỡng cấp 1</td>
                                    <td>
                                        <div class="small">CVDV: Tuấn Anh</div>
                                        <div class="small text-muted">Thợ: Nam</div>
                                    </td>
                                    <td><span class="status-badge success">Chờ giao xe</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-light border"><i class="fas fa-print"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div> <!-- End Dashboard Content -->
    </div> <!-- End Main Content -->
@endsection
