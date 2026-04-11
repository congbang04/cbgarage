@extends('layouts.app')
@section('title', 'Xác thực email')
@section('styles')
    <link rel="stylesheet" href={{ asset('assets/css/design-system.css') }}>
    <link rel="stylesheet" href={{ asset('assets/css/auth.css') }}>
@endsection
@section('body-class', 'auth-body')
@section('content')
    <div class="auth-wrapper">
        <div class="auth-card">

            <!-- Brand Logo -->
            <div class="auth-brand">
                <img src="{{ asset('storage/logo/logo-icon.png') }}" alt="CBAUTO" class="logo-icon-img">
                <span>CBAUTO</span>
            </div>

            <!-- Mail Icon -->
            <div class="auth-icon-wrapper">
                <i class="fas fa-envelope-open-text"></i>
            </div>

            <h1 class="auth-title">Xác thực địa chỉ Email</h1>

            <!-- Đoạn text chuẩn theo thông báo của Laravel -->
            <p class="auth-notice-text">
                Cảm ơn bạn đã đăng ký! Trước khi bắt đầu, vui lòng xác minh địa chỉ email của bạn bằng cách nhấp vào liên
                kết chúng tôi vừa gửi cho bạn qua email. Nếu bạn không nhận được email, chúng tôi sẽ vui lòng gửi cho bạn
                một email khác.
            </p>

            <!-- Status Alert: Thường được render bằng Blade khi session('status') == 'verification-link-sent' -->
            <!-- Bỏ class 'd-none' nếu muốn hiển thị test -->
            <div class="verification-alert d-none" id="status-alert">
                <i class="fas fa-check-circle"></i>
                <span>Một liên kết xác minh mới đã được gửi đến địa chỉ email bạn cung cấp trong quá trình đăng ký.</span>
            </div>

            <div class="auth-actions-group mt-4">
                <!-- Form 1: Gửi lại Email (POST method chuẩn Laravel) -->
                <form method="POST" action="{{ route('verification.send') }}" class="w-100">@csrf
                    <button type="submit" onclick="$('#status-alert').removeClass('d-none')"
                        class="btn btn-primary btn-auth-action w-100">
                        <i class="fas fa-paper-plane me-2"></i> Gửi lại email xác thực
                    </button>
                </form>

                <!-- Form 2: Đăng xuất (POST method chuẩn Laravel) -->
                <form method="POST" action="{{ route('logout') }}">@csrf
                    <button type="submit" class="btn-link-muted">
                        Đăng xuất
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
