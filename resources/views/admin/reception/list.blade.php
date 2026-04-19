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
        @include('layouts.partials.admin-top-navbar', [
            'headerTitle' => 'Quản lý tiếp nhận',
        ])

        <div class="p-4">
            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" class="form-control" name="keyword"
                                    placeholder="Tìm biển số, tên khách...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" name="status">
                                <option value="">Tất cả trạng thái</option>
                                <option value="RECEIVED">Đã tiếp nhận</option>
                                <option value="QUOTED">Đã báo giá</option>
                                <option value="IN_PROGRESS">Đang thực hiện</option>
                                <option value="WAITING_PART">Chờ phụ tùng</option>
                                <option value="COMPLETED">Hoàn thành</option>
                                <option value="PAID">Đã thanh toán</option>
                                <option value="CANCELED">Đã huỷ</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="filterBranch" name="branch_id">
                                <option value="">Tất cả chi nhánh</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" name="date">
                        </div>
                        <div class="col-md-1 align-self-center">
                            <button id="btn-reset" class="btn btn-secondary w-100 h-12">
                                <i class="fas fa-redo"></i>
                            </button>
                        </div>
                        <div class="col-md-2 align-self-center">
                            <a href="{{ route('reception.create') }}" class="btn btn-primary w-100 h-12">
                                <i class="fas fa-plus"></i>
                                Tiếp nhận
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div id="table-data">
                {{-- ajax render --}}
            </div>
        </div>
    </div>
@endsection
