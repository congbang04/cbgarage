@extends('layouts.app')
@section('title', 'Đăng nhập')
@section('styles')
    <link rel="stylesheet" href={{ asset('assets/css/design-system.css') }}>
    <link rel="stylesheet" href={{ asset('assets/css/auth.css') }}>
@endsection
@section('body-class', 'auth-body')
@section('content')
    <div class="auth-wrapper">
        <div class="auth-card">
            <!-- Brand -->
            <div class="auth-brand">
                <i class="fa-solid fa-layer-group"></i>
                <span>CB GARAGE</span>
            </div>

            <!-- Header -->
            <h1 class="auth-title">Chào mừng trở lại</h1>
            <p class="auth-subtitle">Nhập thông tin để truy cập tài khoản của bạn</p>

            <!-- Login Form -->
            <form class="auth-form" action="../index.html" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" placeholder="name@company.com" required>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="password" class="form-label mb-0">Mật khẩu</label>
                        <a href="forgot-password.html" class="small text-primary-main fw-semibold">Quên mật khẩu?</a>
                    </div>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" placeholder="••••••••" required>
                        <span class="input-group-text bg-surface cursor-pointer">
                            <i class="fa-regular fa-eye-slash text-muted"></i>
                        </span>
                    </div>
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember">
                    <label class="form-check-label text-muted small" for="remember">Ghi nhớ đăng nhập</label>
                </div>

                <button type="submit" class="btn btn-primary btn-auth-action">
                    Đăng nhập
                </button>
            </form>

            <!-- Social Divider -->
            <div class="auth-divider">
                <span>Hoặc đăng nhập với</span>
            </div>

            <!-- Social Buttons -->
            <div class="social-buttons">
                <button type="button" class="btn btn-social">
                    <i class="fa-brands fa-google text-danger"></i> Google
                </button>
                <button type="button" class="btn btn-social">
                    <i class="fa-brands fa-facebook text-primary"></i> Facebook
                </button>
            </div>

            <!-- Footer -->
            <div class="auth-footer">
                Chưa có tài khoản? <a href={{ route('auth.register') }}>Đăng ký ngay</a>
            </div>
        </div>
    </div>
@endsection
