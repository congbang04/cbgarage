@extends('layouts.admin')
@section('title', 'CBAUTO - Chỉnh sửa khách hàng - ' . $customer->name)
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
@endsection
@section('body-class', 'admin-wrapper')
@section('sidebar')
    @include('layouts.partials.admin-setting-sidebar')
@endsection
@section('topbar_actions')
    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
@endsection
@section('content')
    <div class="main-content">
        @include('layouts.partials.admin-top-navbar', [
            'headerTitle' => 'Chỉnh sửa khách hàng',
        ])

        <div class="p-4">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="card">
                        <div class="card-header bg-warning text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-edit"></i> Chỉnh sửa khách hàng
                            </h5>
                        </div>
                        <div class="card-body">
                            <form id="form-edit-customer" method="POST"
                                action="{{ route('admin.customers.update', $customer->id) }}">
                                @csrf
                                @method('PUT')

                                <!-- Name -->
                                <div class="mb-3">
                                    <label class="form-label">
                                        Tên khách hàng <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" placeholder="Nhập tên khách hàng"
                                        value="{{ old('name', $customer->name) }}">
                                    @error('name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div class="mb-3">
                                    <label class="form-label">
                                        Số điện thoại <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        name="phone" placeholder="Nhập số điện thoại (10-15 chữ số)"
                                        value="{{ old('phone', $customer->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="mb-3">
                                    <label class="form-label">
                                        Email (tùy chọn)
                                    </label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" placeholder="Nhập email"
                                        value="{{ old('email', $customer->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Address -->
                                <div class="mb-3">
                                    <label class="form-label">
                                        Địa chỉ (tùy chọn)
                                    </label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="3"
                                        placeholder="Nhập địa chỉ">{{ old('address', $customer->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Submit -->
                                <div class="d-flex gap-2 mt-4">
                                    <button type="submit" class="btn btn-warning flex-grow-1">
                                        <i class="fas fa-save"></i> Cập nhật khách hàng
                                    </button>
                                    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Hủy
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
