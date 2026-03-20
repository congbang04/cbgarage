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
                @forelse($requests as $request)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-top border-4" data-request-id="{{ $request->id }}"
                            data-status="{{ $request->status }}">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold">#REQ-{{ str_pad($request->id, 5, '0', STR_PAD_LEFT) }}</h6>
                                <span class="badge" id="status-{{ $request->id }}">
                                    @if ($request->status === 'pending')
                                        <span class="bg-warning text-dark">Chờ duyệt</span>
                                    @elseif ($request->status === 'completed')
                                        <span class="bg-success-subtle text-success">Đã xuất</span>
                                    @else
                                        <span class="bg-danger-subtle text-danger">Từ chối</span>
                                    @endif
                                </span>
                            </div>

                            <div class="card-body">
                                <div class="d-flex mb-3">
                                    <div class="me-3">
                                        <div class="bg-light rounded p-2 text-center" style="width: 50px;">
                                            <i class="fas fa-user-cog fs-4 text-secondary"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-bold">KTV: {{ $request->technician->name ?? 'N/A' }}</div>
                                        <div class="small text-muted">
                                            Xe: {{ $request->order->car->brand->name ?? 'N/A' }}
                                            {{ $request->order->car->carModel->name ?? 'N/A' }}
                                            - {{ $request->order->car->license_plate ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>

                                <hr class="border-light">

                                <ul class="list-group list-group-flush mb-3">
                                    @foreach ($request->details as $detail)
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                            <div>
                                                <div class="fw-bold small">{{ $detail->part->name }}</div>
                                                <div class="x-small text-muted">Mã: {{ $detail->part->sku }}</div>
                                            </div>
                                            <span class="badge bg-primary rounded-pill">x{{ $detail->quantity }}
                                                {{ $detail->part->unit }}</span>
                                        </li>
                                    @endforeach
                                </ul>

                                @if ($request->status === 'pending')
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-success" onclick="approveRequest({{ $request->id }})">
                                            <i class="fas fa-check"></i> Xuất kho
                                        </button>
                                        <button class="btn btn-outline-danger"
                                            onclick="rejectRequest({{ $request->id }})">
                                            Từ chối
                                        </button>
                                    </div>
                                @else
                                    <button class="btn btn-light w-100 border" disabled>Đã hoàn thành</button>
                                @endif
                            </div>

                            <div class="card-footer bg-light small text-muted">
                                <i class="far fa-clock"></i> Yêu cầu lúc:
                                {{ $request->created_at }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Không có yêu cầu chờ duyệt
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
