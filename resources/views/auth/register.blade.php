@extends('layouts.app')
@section('title', 'Đăng ký')
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
            <h1 class="auth-title">Tạo tài khoản mới</h1>
            <p class="auth-subtitle">Bắt đầu dùng thử miễn phí 14 ngày của bạn</p>

            <!-- Register Form -->
            <form class="auth-form" action="{{ route('storeRegister') }}" method="POST">@csrf
                <div class="mb-3">
                    <label for="fullname" class="form-label">Họ và tên</label>
                    <input type="text" class="form-control" id="customer_name" name="customer_name"
                        value="{{ old('customer_name') }}" placeholder="Nguyễn Văn A" required
                        @error('customer_name') is-invalid @enderror>
                    @error('customer_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email công việc</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
                        placeholder="name@gmail.com" required @error('email') is-invalid @enderror>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="••••••••"
                        required @error('password') is-invalid @enderror>
                    @error('password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <div class="form-text text-muted small mt-1">
                        Ít nhất 5 ký tự, bao gồm chữ hoa và số.
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                        placeholder="••••••••" required>
                    <!-- Visual Strength Indicator -->
                    <div class="password-strength">
                        <div class="password-strength-bar bg-success" style="width: 75%"></div>
                    </div>
                    <div class="form-text text-muted small mt-1">
                        Nhập lại mật khẩu để xác nhận.
                    </div>
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="terms" required>
                    <label class="form-check-label text-muted small" for="terms">
                        Tôi đồng ý với <a href="#" class="text-primary-main">Điều khoản dịch vụ</a> và <a
                            href="#" class="text-primary-main">Chính sách bảo mật</a>.
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-auth-action">
                    Tạo tài khoản
                </button>
            </form>

            <!-- Social Divider -->
            <div class="auth-divider">
                <span>Hoặc đăng ký với</span>
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
                Đã có tài khoản? <a href={{ route('login') }}>Đăng nhập</a>
            </div>
        </div>
    </div>
@endsection
