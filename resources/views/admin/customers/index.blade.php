@extends('layouts.admin')
@section('title', 'Quản lý Khách hàng')
@section('vite')
    @vite(['resources/js/modules/admin-customer.js'])
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
@endsection
@section('body-class', 'admin-wrapper')
@section('sidebar')
    @include('layouts.partials.admin-setting-sidebar')
@endsection
@section('topbar_actions')
    <button type="button" class="btn btn-primary add-customer" data-bs-toggle="modal" data-bs-target="#create-customer">
        <i class="fas fa-user-plus"></i>
        Thêm khách hàng
    </button>
@endsection
@section('content')
    <div class="main-content">
        @include('layouts.partials.admin-top-navbar', [
            'headerTitle' => 'Quản lý Khách hàng',
        ])

        <div class="p-4">
            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" class="form-control" id="customer-search"
                                    placeholder="Tìm tên, SĐT, email...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="customer-sort-filter">
                                <option value="">-- Sắp xếp --</option>
                                <option value="created_at">Mới nhất</option>
                                <option value="name">Theo tên (A-Z)</option>
                                <option value="phone">Theo SĐT</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div id="customer-table-container">

            </div>
        </div>
    </div>

    <!-- Create/Edit Customer Modal -->
    @include('admin.customers.partials.create-modal')
@endsection
