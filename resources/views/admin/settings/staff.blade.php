@extends('layouts.admin')
@section('title', 'Quản lý Nhân viên')
@section('vite')
    @vite(['resources/js/modules/staff.js'])
@endsection
@section('styles')
    <link rel="stylesheet" href={{ asset('assets/css/design-system.css') }}>
    <link rel="stylesheet" href={{ asset('assets/css/admin.css') }}>
@endsection
@section('body-class', 'admin-wrapper')
@section('sidebar')
    @include('layouts.partials.admin-setting-sidebar')
@endsection
@section('content')
    <div class="main-content">
        <header class="top-navbar justify-content-between">
            <h4 class="mb-0 fw-bold">Quản lý Nhân viên</h4>
            <button type="button" class="btn btn-primary add-staff" data-bs-toggle="modal" data-bs-target="#create-staff">
                <i class="fas fa-user-plus"></i>
                Thêm nhân viên
            </button>
        </header>

        <div class="p-4">
            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" class="form-control" id="staff-search"
                                    placeholder="Tìm email, tên, SĐT...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="staff-role-filter">
                                <option value="">Tất cả vai trò</option>
                                <option value="technician">Kỹ thuật viên</option>
                                <option value="advisor">Cố vấn dịch vụ</option>
                                <option value="warehouse">Thủ kho</option>
                                <option value="cashier">Thu ngân</option>
                                <option value="admin">Quản trị viên</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="staff-branch-filter">
                                <option value="">Tất cả chi nhánh</option>
                                @forelse($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="staff-status-filter">
                                <option value="">Tất cả trạng thái</option>
                                <option value="active">Hoạt động</option>
                                <option value="inactive">Ngừng hoạt động</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div id="staff-table-container">

            </div>
        </div>
    </div>

    <!-- Create/Edit Staff Modal -->
    @include('admin.settings.staff-partials.create-modal', ['branches' => $branches])
@endsection
