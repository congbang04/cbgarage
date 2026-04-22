@extends('layouts.admin')
@section('title', 'CBAUTO - Quản lý Chi nhánh')
@section('vite')
    @vite(['resources/js/modules/branch.js'])
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
    <button type="button" class="btn btn-primary add-branch" data-bs-toggle="modal" data-bs-target="#create-branch">
        <i class="fas fa-plus"></i>
        Thêm chi nhánh
    </button>
@endsection
@section('content')
    <div class="main-content">
        @include('layouts.partials.admin-top-navbar', [
            'headerTitle' => 'Quản lý Chi nhánh',
        ])

        <div class="p-4">
            <!-- Data Container -->
            <div class="card mb-4">
                <div class="card-body">
                    <form id="form_search_branch" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Tên chi nhánh</label>
                            <input type="text" class="form-control" name="name" placeholder="Tìm tên chi nhánh..."
                                value="{{ request('name') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Địa chỉ</label>
                            <input type="text" class="form-control" name="address" placeholder="Tìm địa chỉ..."
                                value="{{ request('address') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Loại</label>
                            <select class="form-select" name="is_central_warehouse">
                                <option value="">-- Tất cả --</option>
                                <option value="1" @selected(request('is_central_warehouse') == '1')>
                                    Trụ sở chính
                                </option>
                                <option value="0" @selected(request('is_central_warehouse') == '0')>
                                    Chi nhánh thường
                                </option>
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
            <div id="branch-cards-container">

            </div>
        </div>
    </div>
    @include('admin.settings.branch-partials.create-modal')
@endsection
