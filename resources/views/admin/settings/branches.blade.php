@extends('layouts.admin')
@section('title', 'Quản lý Chi nhánh')
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
@section('content')
    <div class="main-content">
        <header class="top-navbar justify-content-between">
            <h4 class="mb-0 fw-bold">Quản lý Chi nhánh</h4>
            <button type="button" class="btn btn-primary add-branch" data-bs-toggle="modal" data-bs-target="#create-branch">
                <i class="fas fa-plus"></i>
                Thêm chi nhánh
            </button>
        </header>

        <div class="p-4">
            <!-- Data Container -->
            <div id="branch-cards-container">

            </div>
        </div>
    </div>
    @include('admin.settings.branch-partials.create-modal')
@endsection
