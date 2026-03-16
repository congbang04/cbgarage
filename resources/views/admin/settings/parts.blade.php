@extends('layouts.admin')
@section('title', 'Phụ tùng & Vật tư')
@section('vite')
    @vite(['resources/js/modules/part.js'])
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
            <h4 class="mb-0 fw-bold">Danh mục Dịch vụ & Phụ tùng</h4>
            <button type="button" class="btn btn-primary add-part" data-bs-toggle="modal" data-bs-target="#create-part">
                <i class="fas fa-plus"></i>
                Thêm mới
            </button>
        </header>

        <div class="p-4">
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a class="nav-link text-secondary fw-bold" href="{{ route('services.index') }}">Dịch vụ (Công)</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active fw-bold" href="{{ route('parts.index') }}">Phụ tùng (Vật tư)</a>
                </li>
            </ul>

            <!-- Data Table -->
            <div id="table-part">

            </div>
        </div>
    </div>
    @include('admin.settings.part-partials.create-modal')
@endsection
