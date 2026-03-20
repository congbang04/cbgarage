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
            <h4 class="mb-0 fw-bold">Quản lý tiếp nhận</h4>
            <div class="user-dropdown">
                <img src="https://ui-avatars.com/api/?name=Admin&background=random" class="user-avatar" alt="Admin">
            </div>
        </header>

        <div class="p-4">
            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" class="form-control" name="keyword"
                                    placeholder="Tìm biển số, tên khách...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="status">
                                <option value="">Tất cả trạng thái</option>
                                <option value="RECEIVED">Đã tiếp nhận</option>
                                <option value="QUOTED">Đã báo giá</option>
                                <option value="IN_PROGRESS">Đang thực hiện</option>
                                <option value="WAITING_PART">Chờ phụ tùng</option>
                                <option value="COMPLETED">Hoàn thành</option>
                                <option value="PAID">Đã thanh toán</option>
                                <option value="CANCELLED">Đã huỷ</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" name="date">
                        </div>
                        <div class="col-md-2 text-end">
                            <a href="{{ route('reception.create') }}" class="btn btn-primary w-100"><i
                                    class="fas fa-plus"></i> Tiếp
                                nhận</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div id="table-data">

            </div>
        </div>
    </div>
@endsection
