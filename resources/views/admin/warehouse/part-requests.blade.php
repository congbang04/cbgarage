@extends('layouts.admin')
@section('title', 'Duyệt yêu cầu xuất kho')
@section('vite')
    @vite(['resources/js/modules/warehouse/part-requests.js'])
    @vite(['resources/js/modules/warehouse/stock-transfer.js'])
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
            </div>
        </header>
        <div class="section-header px-4 mt-4 mb-2">
            <h5 class="fw-bold mb-0">Yêu cầu từ Kỹ thuật viên</h5>
        </div>
        <div class="p-4">
            <div class="row g-4" id="requestsContainer">
                {{-- ajax render --}}
            </div>
        </div>

        <div class="section-header px-4 mt-4 mb-2">
            <h5 class="fw-bold mb-0">Yêu cầu từ chi nhánh</h5>
        </div>
        <div class="p-4">
            <div class="row g-4" id="transferContainer">
                {{-- ajax render --}}
            </div>
        </div>
    </div>
@endsection
