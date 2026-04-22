@extends('layouts.admin')
@section('title', 'CBAUTO - Quản lý Cầu Nâng')
@section('vite')
    @vite(['resources/js/modules/bay.js'])
@endsection
@section('styles')
    <link rel="stylesheet" href={{ asset('assets/css/design-system.css') }}>
    <link rel="stylesheet" href={{ asset('assets/css/admin.css') }}>
@endsection
@section('body-class', 'admin-wrapper')
@section('sidebar')
    @include('layouts.partials.admin-setting-sidebar')
@endsection
@section('topbar_actions')
    <button type="button" class="btn btn-primary add-bay" data-bs-toggle="modal" data-bs-target="#create-bay">
        <i class="fas fa-plus"></i>
        Thêm cầu nâng
    </button>
@endsection
@section('content')
    <div class="main-content">
        @include('layouts.partials.admin-top-navbar', [
            'headerTitle' => 'Quản lý Cầu Nâng',
        ])

        <div class="p-4">
            <div class="card mb-4">
                <div class="card-body">
                    <form id="form_search_bay" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Tên cầu nâng</label>
                            <input type="text" class="form-control" id="bay-search-name" name="name"
                                placeholder="Tìm tên cầu nâng..." value="{{ request('name') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Loại</label>
                            <select class="form-select" id="bay-filter-status" name="status">
                                <option value="">Chọn trạng thái</option>
                                <option value="available">Có sẵn</option>
                                <option value="maintenance">Bảo trì</option>
                                <option value="closed">Đóng</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Chi nhánh</label>
                            <select class="form-select" id="bay-filter-branch" name="branch_id">
                                <option value="">Chọn chi nhánh</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                            <button type="button" class="btn btn-secondary" id="btn_reset_search">
                                <i class="fas fa-redo"></i> Xóa bộ lọc
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Data Container -->
            <div id="bay-cards-container">

            </div>
        </div>
    </div>
    @include('admin.settings.bay-partials.create-modal')
@endsection
