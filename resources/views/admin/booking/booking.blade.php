@extends('layouts.admin')
@section('title', 'CBAUTO - Quản lý đặt lịch')
@section('vite')
    @vite(['resources/js/modules/admin-booking.js'])
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
        @include('layouts.partials.admin-top-navbar', [
            'headerTitle' => 'Quản lý đặt lịch',
        ])

        <div class="p-4">
            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" class="form-control" name="keyword"
                                    placeholder="Tìm biển số, tên khách...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" name="status">
                                <option value="">Tất cả trạng thái</option>
                                <option value="PENDING">Chờ xử lý</option>
                                <option value="CONFIRMED">Đã tiếp nhận</option>
                                <option value="CANCELED">Đã huỷ</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" name="date">
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
