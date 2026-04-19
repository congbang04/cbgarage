@extends('layouts.app')
@section('title', 'Thông tin cá nhân')
@section('vite')
    @vite(['resources/js/modules/profile.js', 'resources/js/modules/car.js', 'resources/js/modules/car-crop.js'])
@endsection
@section('styles')
    <link rel="stylesheet" href={{ asset('assets/css/design-system.css') }}>
    <link rel="stylesheet" href={{ asset('assets/css/customer.css') }}>
    <style>
        :root {
            --brand-red: #cc2929;
        }

        .btn-brand {
            background-color: var(--brand-red);
            color: white;
            border-radius: 50px;
            font-weight: 600;
            border: 2px solid var(--brand-red);
            transition: all 0.3s ease;
        }

        .btn-brand:hover {
            background-color: transparent;
            color: var(--brand-red);
            font-weight: 600;
            border: 2px solid var(--brand-red);
        }

        .btn-brand:focus,
        .btn-brand:active,
        .btn-brand.show {
            background-color: transparent;
            color: var(--brand-red);
            font-weight: 600;
            border: 2px solid var(--brand-red);
            box-shadow: none;
        }

        .btn-brand:focus:not(.show):not(:hover) {
            background-color: var(--brand-red);
            color: white;
            box-shadow: none;
        }
    </style>
@endsection
@section('header')
    <nav class="navbar navbar-light bg-white shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="{{ route('home') }}">
                <i class="fas fa-chevron-left me-1 fs-6"></i> Quay lại trang chủ
            </a>
            <div class="btn-group">
                <button type="button" class="btn btn-brand dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ optional(auth()->user())->customer->name ?? '-' }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('customer.profile') }}">Thông tin cá nhân</a></li>
                    <li><a class="dropdown-item" href="{{ route('customer-bookings.index') }}">Lịch đặt của tôi</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item text-danger" id="btn-logout" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); $('#logout-form').submit();">
                            Đăng xuất
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
@endsection
@section('content')
    <main class="container">
        <div class="row">
            <!-- Sidebar / User Info -->
            <div id="profileSidebar" class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm text-center p-4 h-100">
                    <div class="mb-3 position-relative d-block mx-auto" style="width: max-content;">
                        @if (!empty(auth()->user()->customer->image))
                            <img src="{{ Storage::disk()->url(auth()->user()->customer->image) }}"
                                class="rounded-circle shadow-sm" alt="Avatar"
                                style="width: 128px; height: 128px; object-fit: cover;">
                        @else
                            <div class="bg-secondary text-white rounded-circle shadow-sm d-flex align-items-center justify-content-center"
                                style="width: 128px; height: 128px;">
                                <i class="fas fa-user fa-3x"></i>
                            </div>
                        @endif

                        <span
                            class="position-absolute bg-primary text-white rounded-circle p-2 border border-2 border-white d-flex align-items-center justify-content-center"
                            style="cursor: pointer; bottom: 5px; right: 5px;">
                            <i class="fas fa-camera small"></i>
                        </span>
                    </div>
                    <h5 class="fw-bold mb-1"> {{ auth()->user()->customer->name }}</h5>
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill" data-bs-toggle="modal"
                        data-bs-target="#updateProfileModal"><i class="fas fa-edit me-1"></i>
                        Chỉnh sửa hồ sơ
                    </button>

                    <hr class="my-4">
                    <div class="text-start">
                        <div class="mb-2"><i class="fas fa-phone-alt text-muted me-2 width-20"></i>
                            {{ Auth::user()->customer->phone ?? 'Chưa cập nhật số điện thoại' }}</div>
                        <div class="mb-2"><i class="fas fa-envelope text-muted me-2 width-20"></i>
                            {{ Auth::user()->email ?? 'Chưa cập nhật email' }}</div>
                        <div class="mb-2"><i class="fas fa-map-marker-alt text-muted me-2 width-20"></i>
                            {{ Auth::user()->customer->address ?? 'Chưa cập nhật địa chỉ' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content: My Cars & Bookings -->
            <div id="car_list" class="col-md-8">

                <!-- Section: Xe của tôi -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Danh sách xe</h5>
                    <button type="button" class="btn btn-primary btn-sm rounded-pill" data-bs-toggle="modal"
                        data-bs-target="#addCarModal">
                        <i class="fas fa-plus me-1"></i>
                        Thêm xe
                    </button>
                </div>
                <hr>
                @foreach ($cars as $car)
                    <!-- Car 1 -->
                    <div class="card border-0 shadow-sm mb-3 ">
                        <div class="row g-0">
                            @if (!empty($car->image))
                                <div class="col-4 bg-light d-flex align-items-center justify-content-center">
                                    <img src="{{ Storage::disk()->url($car->image) }}" alt="car" class="car-img">
                                </div>
                            @else
                                <div class="col-4 bg-light d-flex align-items-center justify-content-center">
                                    <i class="fas fa-car fa-3x text-secondary opacity-50"></i>
                                </div>
                            @endif
                            <div class="col-8">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h5 class="card-title fw-bold mb-1">
                                                {{ $car->brand->name ?? 'Hãng xe không xác định' }}
                                                {{ $car->carModel->name ?? 'Dòng xe không xác định' }}</h5>
                                            <p class="card-text text-muted small mb-2">Biển số: {{ $car->license_plate }}
                                            </p>
                                            <p class="card-text text-muted small mb-2">VIN:
                                                {{ $car->vin_number ?? 'Chưa cập nhật' }}</p>
                                            <span class="badge bg-success-subtle text-success rounded-pill px-3">Hoạt
                                                động</span>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-link text-muted btn-action-dots"
                                                data-bs-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('customer.carDetail', $car->id) }}">
                                                        Xem chi tiết
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="booking.html">
                                                        Đặt lịch sửa xe này
                                                    </a>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <button type="button" class="dropdown-item text-danger btn-delete-car"
                                                        data-id="{{ $car->id }}">
                                                        Xóa xe
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="mt-3 text-end">
                                        <a href="{{ route('customer.carDetail', $car->id) }}"
                                            class="btn btn-outline-secondary btn-sm rounded-pill px-3">Lịch sử sửa chữa</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
    @include('customer.modal.update-customer-modal')
    @include('customer.modal.create-car-modal')
@endsection
