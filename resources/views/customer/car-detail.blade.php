@extends('layouts.app')
@section('title', 'Chi tiết xe')
@section('vite')
    @vite(['resources/js/modules/car.js'])
@endsection
@section('styles')
    <link rel="stylesheet" href={{ asset('assets/css/design-system.css') }}>
    <link rel="stylesheet" href={{ asset('assets/css/customer.css') }}>
@endsection

@section('header')
    <div class="bg-primary text-white p-4 pb-5 rounded-bottom-4 shadow-sm">
        <div class="container" id="car_detail_header">
            <div class="d-flex align-items-center justify-content-between">
                <a href="{{ route('customer.profile') }}" class="text-white mb-3 d-block">
                    <i class="fas fa-arrow-left"></i>
                    Quay lại
                </a>
                <button type="button" class="btn btn-primary btn-sm rounded-pill" data-bs-toggle="modal"
                    data-bs-target="#updateCarModal">
                    <i class="fas fa-edit me-1"></i>
                    Chỉnh sửa thông tin xe
                </button>
            </div>
            <div class="d-flex align-items-center">
                <div class="bg-white text-primary rounded-circle p-3 me-3 shadow-sm">
                    <i class="fas fa-car fa-2x"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-0">{{ $car->brand->name ?? 'Hãng xe không xác định' }}
                        {{ $car->carModel->name ?? 'Dòng xe không xác định' }}</h2>
                    <div class="opacity-75">Biển số: {{ $car->license_plate }}</div>
                    <div class="opacity-75">VIN: {{ $car->vin_number ?? 'Chưa cập nhật' }}</div>
                </div>
            </div>
            <div class="mt-4 row g-2 text-center">
                <div class="col-4">
                    <div class="bg-white bg-opacity-10 rounded p-2">
                        <div class="small opacity-75">ODO (Km)</div>
                        <div class="fw-bold">{{ $car->current_odo ?? 'Chưa cập nhật' }}</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="bg-white bg-opacity-10 rounded p-2">
                        <div class="small opacity-75">Năm SX</div>
                        <div class="fw-bold">{{ $car->manufacture_year ?? 'Chưa cập nhật' }}</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="bg-white bg-opacity-10 rounded p-2">
                        <div class="small opacity-75">Màu</div>
                        <div class="fw-bold">{{ $car->color ?? 'Chưa cập nhật' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="p-4">
        <h3>Lịch sử sửa chữa</h5>
            <!-- Data Table -->
            <div id="table-data">
                {{-- ajax render --}}
            </div>
    </div>
    @include('customer.modal.update-car-modal')

@endsection
