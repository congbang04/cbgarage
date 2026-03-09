@extends('layouts.app')
@section('title', 'Trang chủ')
@section('styles')
    <link rel="stylesheet" href={{ asset('assets/css/design-system.css') }}>
    <link rel="stylesheet" href={{ asset('assets/css/customer.css') }}>
@endsection

@section('header')
    <div class="bg-white shadow-sm p-3 sticky-top">
        <div class="container d-flex align-items-center">
            <a href="{{ route('home') }}" class="text-dark me-3"><i class="fas fa-arrow-left"></i></a>
            <h5 class="mb-0 fw-bold">Đặt lịch sửa chữa</h5>
        </div>
    </div>
@endsection

@section('content')
    <main class="container py-4">

        <!-- Wizard Steps -->
        <div class="wizard-steps px-4">
            <div class="step-item">1</div>
            <div class="step-item">2</div>
            <div class="step-item">3</div>
            <div class="step-item">4</div>
        </div>

        <form id="booking-form" method="POST" action="{{ route('createBooking') }}">@csrf

            <!-- Step 1: Chọn chi nhánh -->
            <div id="branch-section" class="mb-4">
                <h6 class="fw-bold mb-3"><i class="fas fa-map-marker-alt text-primary me-2"></i>Chọn Chi
                    nhánh</h6>
                <div id="branches-list" class="row g-2">
                    @include('customer.booking-partials.branches')
                </div>
            </div>

            <!-- Step 2: Chọn Dịch vụ -->
            <div id="service-section" class="mb-4">
                <h6 class="fw-bold mb-3"><i class="fas fa-tools text-primary me-2"></i>Chọn Dịch vụ</h6>
                <div id="services-list" class="row g-2">
                    @include('customer.booking-partials.services')
                </div>
            </div>

            <!-- Step 3: Chọn Ngày Giờ -->
            <div id="time-section" class="mb-4">
                <h6 class="fw-bold mb-3"><i class="far fa-clock text-primary me-2"></i>Thời gian hẹn</h6>
                <div id="date-input" class="mb-3">
                    <label class="form-label small text-muted">Ngày đến</label>
                    <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}">
                </div>
                <label class="form-label small text-muted">Giờ đến (Dự kiến 60 phút)</label>
                <div id="slot-list" class="time-slot-grid">
                    {{-- ajax render slots list --}}
                </div>
            </div>

            <!-- Step 4: Thông tin khách -->
            <div class="mb-5">
                <h6 class="fw-bold mb-3"><i class="far fa-user text-primary me-2"></i>Thông tin liên hệ</h6>
                <div id="contact-information" class="card shadow-sm border-0">
                    @include('customer.booking-partials.contact-information')
                </div>
            </div>

            <input type="hidden" name="start_time" id="start-time">

            <!-- Sticky Summary -->
            <div class="booking-summary-sticky">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="small">
                        <div class="text-muted">Tổng tạm tính:</div>
                        <div class="fw-bold text-primary fs-5">450.000đ</div>
                    </div>
                    <div class="text-end small text-muted">
                        <div>1 Dịch vụ</div>
                        <div>09:00 - 25/10</div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold py-2 rounded-pill shadow">XÁC NHẬN ĐẶT
                    LỊCH</button>
            </div>

        </form>
    </main>
@endsection
