@extends('layouts.admin')
@section('title', 'Duyệt yêu cầu xuất kho')
@section('vite')
    @vite(['resources/js/modules/warehouse/part-requests.js'])
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    <style>
        .nav-tabs .nav-link {
            color: #6c757d;
        }

        .nav-tabs .nav-link:hover:not(.active) {
            background-color: #f1f3f5 !important;
            color: #0d6efd !important;
            border-color: transparent;
        }

        .nav-tabs .nav-link.active {
            color: var(--bs-nav-tabs-link-active-color);
            background-color: var(--bs-nav-tabs-link-active-bg);
            border-color: var(--bs-nav-tabs-link-active-border-color);
        }
    </style>
@endsection
@section('body-class', 'admin-wrapper')
@section('sidebar')
    @include('layouts.partials.admin-sidebar')
@endsection
@section('content')
    <div class="main-content">
        <header class="top-navbar">
            <div class="d-flex align-items-center">
                <a href="{{ route('warehouse.inventory') }}" class="btn btn-light me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h5 class="fw-bold mb-0">Duyệt yêu cầu xuất kho</h5>
            </div>
        </header>
        <div class="p-4">
            <ul class="nav nav-tabs mb-4" id="requestTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="technician-tab" data-bs-toggle="tab"
                        data-bs-target="#technician-content" type="button" role="tab">
                        <i class="fas fa-user-cog me-2"></i>Yêu cầu từ Kỹ thuật viên
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="branch-tab" data-bs-toggle="tab" data-bs-target="#branch-content"
                        type="button" role="tab">
                        <i class="fas fa-store me-2"></i>Yêu cầu từ Chi nhánh
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="requestTabsContent">
                <div class="tab-pane fade show active" id="technician-content" role="tabpanel">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form id="partRequestSearchForm" class="row g-3">
                                <div class="col-md-4">
                                    <label for="statusFilter" class="form-label">Trạng thái</label>
                                    <select class="form-select" id="statusFilter" name="status">
                                        <option value="">-- Tất cả trạng thái --</option>
                                        <option value="PENDING">Chờ duyệt</option>
                                        <option value="COMPLETED">Đã xuất</option>
                                        <option value="REJECTED">Từ chối</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="dateFromFilter" class="form-label">Từ ngày</label>
                                    <input type="date" class="form-control" id="dateFromFilter" name="date_from">
                                </div>
                                <div class="col-md-4">
                                    <label for="dateToFilter" class="form-label">Đến ngày</label>
                                    <input type="date" class="form-control" id="dateToFilter" name="date_to">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Tìm kiếm
                                    </button>
                                    <button type="reset" class="btn btn-secondary ms-2">
                                        <i class="fas fa-redo"></i> Đặt lại
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row g-4" id="requestsContainer">
                        {{-- ajax render --}}
                    </div>
                </div>

                <div class="tab-pane fade" id="branch-content" role="tabpanel">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form id="transferSearchForm" class="row g-3">
                                <div class="col-md-4">
                                    <label for="transferStatusFilter" class="form-label">Trạng thái</label>
                                    <select class="form-select" id="transferStatusFilter" name="status">
                                        <option value="">-- Tất cả trạng thái --</option>
                                        <option value="PENDING">Chờ duyệt</option>
                                        <option value="COMPLETED">Đã xuất</option>
                                        <option value="CANCELED">Đã hủy</option>
                                        <option value="IN_TRANSIT">Đang vận chuyển</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="transferDateFromFilter" class="form-label">Từ ngày</label>
                                    <input type="date" class="form-control" id="transferDateFromFilter" name="date_from">
                                </div>
                                <div class="col-md-4">
                                    <label for="transferDateToFilter" class="form-label">Đến ngày</label>
                                    <input type="date" class="form-control" id="transferDateToFilter" name="date_to">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Tìm kiếm
                                    </button>
                                    <button type="reset" class="btn btn-secondary ms-2">
                                        <i class="fas fa-redo"></i> Đặt lại
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row g-4" id="transferContainer">
                        {{-- ajax render --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
