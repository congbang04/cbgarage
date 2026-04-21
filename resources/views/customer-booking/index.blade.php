@extends('layouts.app')
@section('title', 'CBAUTO - Đặt lịch')
@section('vite')
    @vite(['resources/js/modules/customer-booking.js'])
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
    <div class="bg-white shadow-sm p-3 sticky-top">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="container d-flex align-items-center">
                    <a class="navbar-brand fw-bold text-primary mt-1" href="{{ route('home') }}">
                        <i class="fas fa-chevron-left me-2 fs-6"></i>
                    </a>
                    <h5 class="mb-0 fw-bold text-primary">Quản lý lịch đặt của tôi</h5>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-brand dropdown-toggle" data-bs-toggle="dropdown"
                        aria-expanded="false">
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
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid py-4">
        {{-- Filter Form --}}
        <div class="row mb-4">
            <div class="col-12">
                <card class="card">
                    <div class="card-body">
                        <form id="filter-form" class="row g-3">
                            {{-- Filter by Date --}}
                            <div class="col-md-5">
                                <label for="start_time" class="form-label">Ngày đặt</label>
                                <input type="date" class="form-control filter-change" id="start_time" name="start_time"
                                    value="">
                            </div>

                            {{-- Filter by Status --}}
                            <div class="col-md-5">
                                <label for="status" class="form-label">Trạng thái</label>
                                <select class="form-select filter-change" id="status" name="status">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="PENDING">Đã đặt lịch</option>
                                    <option value="CONFIRMED">Đã tiếp nhận</option>
                                    <option value="CANCELED">Đã hủy</option>
                                </select>
                            </div>

                            {{-- Reset Button Only --}}
                            <div class="col-2 d-flex align-items-end">
                                <button type="reset" class="btn btn-secondary">
                                    <i class="fas fa-redo me-2"></i>Xóa bộ lọc
                                </button>
                            </div>
                        </form>
                    </div>
                </card>
            </div>
        </div>

        {{-- Table Container --}}
        <div class="row">
            <div class="col-12">
                <div id="booking-table">
                    {{-- AJAX will load table here --}}
                    <div class="text-center py-5">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Đang tải...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
