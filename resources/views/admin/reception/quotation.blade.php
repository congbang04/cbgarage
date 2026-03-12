@extends('layouts.admin')
@section('title', 'Báo giá')
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
        <header class="top-navbar justify-content-between">
            <div class="d-flex align-items-center">
                <a href="list.html" class="btn btn-light me-3"><i class="fas fa-arrow-left"></i></a>
                <div>
                    <h4 class="mb-0 fw-bold">Lập báo giá #QT-8821</h4>
                    <small class="text-muted">Toyota Vios - 51G-123.45 (Nguyễn Văn A)</small>
                </div>
            </div>
            <div>
                <span class="badge bg-warning text-dark fs-6 px-3">Chờ báo giá</span>
            </div>
        </header>

        <div class="p-4">
            <div class="row g-4">
                <!-- Bảng chi tiết báo giá -->
                <div class="col-lg-9">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
                            <h5 class="mb-0">Chi tiết Hạng mục</h5>
                            <button class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Thêm Phụ tùng /
                                Dịch vụ</button>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="width: 50px;">#</th>
                                            <th>Mã & Tên hạng mục</th>
                                            <th>Loại</th>
                                            <th style="width: 100px;">ĐVT</th>
                                            <th style="width: 120px;">Số lượng</th>
                                            <th class="text-end">Đơn giá</th>
                                            <th class="text-end">Thành tiền</th>
                                            <th style="width: 50px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Dịch vụ -->
                                        <tr>
                                            <td>1</td>
                                            <td>
                                                <div class="fw-bold">Thay dầu máy</div>
                                                <div class="small text-muted">SVC-OIL-01</div>
                                            </td>
                                            <td><span class="badge bg-info-subtle text-info">Công</span></td>
                                            <td>Gói</td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm text-center"
                                                    value="1" readonly>
                                            </td>
                                            <td class="text-end">150.000</td>
                                            <td class="text-end fw-bold">150.000</td>
                                            <td><button class="btn btn-sm text-danger"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                        <!-- Phụ tùng -->
                                        <tr>
                                            <td>2</td>
                                            <td>
                                                <div class="fw-bold">Nhớt Castrol Magnatec 5W30</div>
                                                <div class="small text-muted">PT-OIL-CAS5W30</div>
                                            </td>
                                            <td><span class="badge bg-secondary-subtle text-secondary">Vật tư</span>
                                            </td>
                                            <td>Lít</td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm text-center"
                                                    value="4">
                                            </td>
                                            <td class="text-end">120.000</td>
                                            <td class="text-end fw-bold">480.000</td>
                                            <td><button class="btn btn-sm text-danger"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>
                                                <div class="fw-bold">Lọc nhớt Toyota Vios</div>
                                                <div class="small text-muted">PT-FIL-TOY01</div>
                                            </td>
                                            <td><span class="badge bg-secondary-subtle text-secondary">Vật tư</span>
                                            </td>
                                            <td>Cái</td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm text-center"
                                                    value="1">
                                            </td>
                                            <td class="text-end">80.000</td>
                                            <td class="text-end fw-bold">80.000</td>
                                            <td><button class="btn btn-sm text-danger"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
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
                                <span>560.000</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Tổng tiền công:</span>
                                <span>150.000</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">VAT (8%):</span>
                                <span>56.800</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold">Thanh toán:</span>
                                <h4 class="fw-bold text-primary mb-0">766.800</h4>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body d-grid gap-2">
                            <button class="btn btn-success"><i class="fab fa-whatsapp me-2"></i> Gửi khách
                                duyệt</button>
                            <button class="btn btn-outline-secondary"><i class="fas fa-print me-2"></i> In báo
                                giá</button>
                            <hr class="my-2">
                            <div class="mb-2">
                                <label class="form-label small fw-bold">Gán Kỹ thuật viên</label>
                                <select class="form-select form-select-sm">
                                    <option>Chọn thợ chính...</option>
                                    <option>Nguyễn Văn Hùng</option>
                                    <option>Trần Văn Nam</option>
                                </select>
                            </div>
                            <button class="btn btn-primary mt-2">Chuyển sang Sửa chữa</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
