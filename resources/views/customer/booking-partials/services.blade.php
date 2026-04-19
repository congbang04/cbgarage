@foreach ($services as $service)
    <div class="col-4">
        <label class="card service-card p-3 text-center h-100">
            <input type="radio" class="d-none" name="service_id" value="{{ $service->id }}">
            <div class="service-icon">
                @if ($service->icon)
                    <img src="{{ Storage::disk()->url($service->icon) }}">
                @else
                    <i class="fas fa-question-circle"></i>
                @endif
            </div>
            <div class="fw-bold small">{{ $service->name }}</div>
            <div class="text-danger fw-bold mt-1">{{ number_format($service->price) }}đ</div>
            <div class="text-muted small">Thời gian ước tính: {{ $service->estimated_duration }} phút</div>
        </label>
    </div>
@endforeach
