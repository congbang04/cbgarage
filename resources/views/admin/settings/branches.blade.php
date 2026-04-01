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
            <div id="branch-cards-container">

            </div>
        </div>
    </div>
    @include('admin.settings.branch-partials.create-modal')
@endsection
