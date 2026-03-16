@extends('layouts.admin')
@section('title', 'Tiếp nhận xe')
@section('vite')
    @vite(['resources/js/modules/reception.js'])
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
    <div class="main-content">
        <header class="top-navbar">
            <div class="d-flex align-items-center">
                <a href="list.html" class="btn btn-light me-3"><i class="fas fa-arrow-left"></i></a>
                <h4 class="mb-0 fw-bold">Tiếp nhận xe mới</h4>
            </div>
        </header>
        <form id="create-reception" method="POST" action="">@csrf
            <div class="p-4">
                <div class="row g-4">
                    <!-- Cột Trái: Thông tin Khách & Xe -->
                    <div class="col-lg-8">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5><i class="far fa-user me-2"></i>Thông tin Khách hàng</h5>
                            </div>
                            <div class="card-body">
                                <div class="input-group mb-3">
                                    <input id="customer-phone-search" type="text" class="form-control"
                                        name="search_phone" placeholder="Nhập số điện thoại để tìm kiếm...">
                                    <button type="button" id="btn-customer-search" class="btn btn-primary">
                                        <i class="fas fa-search"></i>Tìm kiếm
                                    </button>
                                </div>
                                <div class="row g-3">

                                    <input type="hidden" name="booking_id" id="booking-id">

                                    <div class="col-md-6">
                                        <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" placeholder="Nhập họ tên">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="phone" placeholder="Nhập SĐT">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Địa chỉ</label>
                                        <input type="text" class="form-control" name="address"
                                            placeholder="Nhập địa chỉ">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div id="customer-cars" class="mt-3 d-none">
                                <div class="card-header">
                                    <h5><i class="fas fa-car me-2"></i>Xe của khách</h5>
                                </div>
                                <div class="list-group" id="car-list"></div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-car me-2"></i>Thông tin Xe</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Biển số xe <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control fw-bold text-uppercase" id="license_plate"
                                            name="license_plate" placeholder="VD: 51A12345">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Hãng xe</label>
                                        <select class="form-select" id="brand_id" name="brand_id">
                                            <option value="">-- Chọn hãng xe --</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}">
                                                    {{ $brand->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Dòng xe (Model)</label>
                                        <select class="form-select" id="car_model_id" name="car_model_id">
                                            <option value="">-- Chọn dòng xe --</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Số khung (VIN)</label>
                                        <input type="text" class="form-control" id="vin_number" name="vin_number"
                                            placeholder="Nhập 17 ký tự...">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="manufacture_year" class="form-label">Năm sản xuất <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="manufacture_year"
                                            name="manufacture_year" placeholder="Nhập năm sản xuất">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="color" class="form-label">Màu xe <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="color" name="color"
                                            placeholder="Nhập màu xe">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cột Phải: Tình trạng tiếp nhận -->
                    <div class="col-lg-4">
                        <div class="card h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="text-white mb-0"><i class="fas fa-clipboard-check me-2"></i>Tình trạng xe
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Số ODO hiện tại (Km)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control fw-bold" id="current_odo"
                                            name="current_odo" placeholder="Nhập số ODO hiện tại">
                                        <span class="input-group-text">Km</span>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label d-flex justify-content-between">
                                        <span>Mức nhiên liệu</span>
                                        <span id="fuel-value" name="fuel_range" class="fw-bold text-primary">50%</span>
                                    </label>
                                    <input id="fuel-range" name="fuel_range" type="range" class="form-range"
                                        min="0" max="100" step="10" value="50">

                                    <div class="d-flex justify-content-between small text-muted">
                                        <span>E</span>
                                        <span>F</span>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Yêu cầu của khách hàng</label>
                                    <textarea class="form-control" rows="4" name="description"
                                        placeholder="VD: Xe bị rung khi phanh, thay nhớt..."></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Ghi chú vết trầy xước (nếu có)</label>
                                    <textarea class="form-control" rows="2" name="note"></textarea>
                                </div>

                                <hr>

                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary btn-lg">
                                        <i class="fas fa-save me-2"></i>
                                        LƯU PHIẾU
                                    </button>
                                    <a href="list.html" class="btn btn-outline-secondary">Hủy bỏ</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>
        window.brands = @json($brands);
    </script>
@endsection
