@extends('layouts.admin')
@section('title', 'Duyệt yêu cầu xuất kho')
@section('vite')
    @vite(['resources/js/modules/warehouse/part-requests.js'])
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
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
                <h4 class="mb-0 fw-bold">Yêu cầu từ Kỹ thuật viên</h4>
            </div>
        </header>

        <div class="p-4">
            <div class="row g-4" id="requestsContainer">
                {{-- ajax render --}}
            </div>
        </div>
    </div>
@endsection
