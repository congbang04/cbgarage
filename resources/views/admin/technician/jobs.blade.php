@extends('layouts.admin')
@section('title', 'Công việc của tôi')
@section('vite')
    @vite(['resources/js/modules/technician-jobs/technician-jobs-app.js'])
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
@endsection
@section('body-class', 'admin-wrapper')
@section('content')
    <div class="main-content">
        <header class="top-navbar">
            <h4 class="mb-0 fw-bold">Danh sách công việc</h4>
            <div class="d-flex gap-2">
                <div>
                    <a class="btn btn-sm btn-primary" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); $('#logout-form').submit();">
                        Đăng xuất
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
                <button class="btn btn-light border" id="refreshBtn">
                    <i class="fas fa-sync-alt"></i>
                </button>
                <div class="d-flex align-items-center bg-white border px-3 rounded">
                    <span class="status-badge info me-2">{{ auth()->user()->technician->name }}</span>
                    Đang làm: <span id="inProgressCount" class="ms-1 fw-bold">{{ $stats['in_progress'] ?? 0 }}</span>
                </div>
            </div>
        </header>

        <div class="p-4">
            <!-- Tabs -->
            <ul class="nav nav-pills mb-4">
                <li class="nav-item">
                    <a class="nav-link technician active" href="#" data-tab="">
                        Tất cả <span class="badge bg-secondary ms-1">{{ $stats['total_assigned'] ?? 0 }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link technician" href="#" data-tab="IN_PROGRESS">
                        Đang thực hiện <span class="badge bg-primary ms-1">{{ $stats['in_progress'] ?? 0 }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link technician" href="#" data-tab="WAITING_PART">
                        Chờ phụ tùng <span class="badge bg-warning ms-1">{{ $stats['waiting_part'] ?? 0 }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link technician" href="#" data-tab="COMPLETED">
                        Hoàn thành <span class="badge bg-success ms-1">{{ $stats['completed'] ?? 0 }}</span>
                    </a>
                </li>
            </ul>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" class="form-control" name="keyword"
                                    placeholder="Tìm tên khách, số điện thoại, biển số xe">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="status" id="statusFilter">
                                <option value="">Tất cả trạng thái</option>
                                <option value="IN_PROGRESS">Đang thực hiện</option>
                                <option value="WAITING_PART">Chờ phụ tùng</option>
                                <option value="COMPLETED">Hoàn thành</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" name="date" id="dateFilter">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div id="jobs-table-container">
                {{-- AJAX content loads here --}}
            </div>
        </div>
    </div>
@endsection
