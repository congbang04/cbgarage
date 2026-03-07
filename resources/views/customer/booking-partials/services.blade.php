@foreach ($services as $service)
    <div class="col-4">
        <label class="card service-card p-3 text-center h-100">
            <input type="checkbox" class="d-none" name="service_id" value="{{ $service->id }}">
            <div class="service-icon"><i class="fas fa-oil-can"></i></div>
            <div class="fw-bold small">{{ $service->name }}</div>
            <div class="text-primary fw-bold mt-1">{{ number_format($service->price) }}đ</div>
        </label>
    </div>
@endforeach
