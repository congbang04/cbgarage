@extends('layouts.admin')
@section('title', 'CBAUTO - Chi tiết khách hàng - ' . $customer->name)
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
@endsection
@section('body-class', 'admin-wrapper')
@section('sidebar')
    @include('layouts.partials.admin-setting-sidebar')
@endsection
@section('topbar_actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Chỉnh sửa
        </a>
        <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>
@endsection
@section('content')
    <div class="main-content">
        @include('layouts.partials.admin-top-navbar', [
            'headerTitle' => 'Chi tiết Khách hàng',
        ])

        <div class="p-4">
            <!-- Customer Info Card -->
            <div class="row">
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            @if ($customer->image)
                                <div class="d-flex align-items-center justify-content-center"> <img
                                        src="{{ Storage::disk()->url($customer->image) }}" alt="{{ $customer->name }}"
                                        class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                                </div>
                            @else
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mb-3"
                                    style="width: 120px; height: 120px; margin: 0 auto;">
                                    <i class="fas fa-user" style="font-size: 3rem; color: #ccc;"></i>
                                </div>
                            @endif
                            <h3>{{ $customer->name }}</h3>
                            <p class="text-muted mb-3">ID: #{{ $customer->id }}</p>

                            <div class="list-group list-group-flush">
                                <div class="list-group-item">
                                    <small class="text-muted">Số điện thoại</small>
                                    <p class="mb-0">
                                        <a href="#">
                                            <i class="fas fa-phone text-primary"></i> {{ $customer->phone }}
                                        </a>
                                    </p>
                                </div>
                                @if ($customer->user?->email)
                                    <div class="list-group-item">
                                        <small class="text-muted">Email</small>
                                        <p class="mb-0">
                                            <a href="#">
                                                <i class="fas fa-envelope text-primary"></i> {{ $customer->user->email }}
                                            </a>
                                        </p>
                                    </div>
                                @endif
                                @if ($customer->address)
                                    <div class="list-group-item">
                                        <small class="text-muted">Địa chỉ</small>
                                        <p class="mb-0">
                                            <i class="fas fa-map-marker-alt text-primary"></i> {{ $customer->address }}
                                        </p>
                                    </div>
                                @endif
                                <div class="list-group-item">
                                    <small class="text-muted">Ngày tạo</small>
                                    <p class="mb-0">
                                        <i class="fas fa-calendar text-primary"></i>
                                        {{ $customer->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <!-- Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="display-6 text-primary mb-2">
                                        <i class="fas fa-car"></i> {{ $customer->cars->count() }}
                                    </div>
                                    <p class="card-text mb-0">Số xe đã đăng ký</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="display-6 text-success mb-2">
                                        <i class="fas fa-calendar-check"></i> {{ $customer->bookings()->count() }}
                                    </div>
                                    <p class="card-text mb-0">Tổng lịch đặt</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="display-6 text-warning mb-2">
                                        <i class="fas fa-hourglass-half"></i>
                                        {{ $customer->bookings()->whereIn('status', ['PENDING', 'CONFIRMED'])->count() }}
                                    </div>
                                    <p class="card-text mb-0">Lịch đang chờ</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cars Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-car"></i> Danh sách xe ({{ $customer->cars->count() }})
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($customer->cars->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead>
                                            <tr>
                                                <th>Biển số</th>
                                                <th>Hãng xe</th>
                                                <th>Năm SX</th>
                                                <th>Màu sắc</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($customer->cars as $car)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $car->license_plate }}</strong>
                                                    </td>
                                                    <td>{{ $car->brand->name ?? '--' }} {{ $car->carModel->name ?? '' }}
                                                    </td>
                                                    <td>{{ $car->manufacture_year ?? '--' }}</td>
                                                    <td>{{ $car->color ?? '--' }}</td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted text-center py-3">
                                    <i class="fas fa-inbox"></i> Khách hàng chưa đăng ký xe nào
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Bookings Section -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar"></i> Danh sách lịch đặt ({{ $customer->bookings->count() }})
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($customer->bookings->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Xe</th>
                                                <th>Dịch vụ</th>
                                                <th>Thời gian</th>
                                                <th>Trạng thái</th>
                                                <th>Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($customer->bookings as $booking)
                                                <tr>
                                                    <td>#{{ $booking->id }}</td>
                                                    <td>{{ $booking->car->license_plate ?? '--' }}</td>
                                                    <td>{{ $booking->service->name ?? '--' }}</td>

                                                    <td>
                                                        {{ Carbon\Carbon::parse($booking->start_time)->format('d/m/Y H:i') ?? '--' }}
                                                    </td>

                                                    <td>
                                                        @php
                                                            $statusClass = match ($booking->status) {
                                                                'PENDING' => 'warning',
                                                                'CONFIRMED' => 'success',
                                                                'CANCELED' => 'danger',
                                                                'COMPLETED' => 'info',
                                                                default => 'secondary',
                                                            };
                                                        @endphp
                                                        <span class="badge bg-{{ $statusClass }}"
                                                            style="width: 93px;height:22px">
                                                            {{ match ($booking->status) {
                                                                'PENDING' => 'Chờ xử lý',
                                                                'CONFIRMED' => 'Đã tiếp nhận',
                                                                'CANCELED' => 'Đã hủy',
                                                                'COMPLETED' => 'Hoàn thành',
                                                                default => $booking->status,
                                                            } }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.booking') . '?booking_id=' . $booking->id }}"
                                                            class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i> Xem
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted text-center py-3">
                                    <i class="fas fa-inbox"></i> Khách hàng chưa có lịch đặt nào
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
