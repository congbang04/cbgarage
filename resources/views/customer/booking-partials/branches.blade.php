@foreach ($branches as $branch)
    <div class="card mb-3 shadow-sm col-6">
        <div class="card-body">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="branch_id" id="{{ $branch->id }}"
                    value="{{ $branch->id }}">
                <label class="form-check-label w-100" for="{{ $branch->id }}">
                    <span class="fw-bold d-block">{{ $branch->name }}</span>
                    <span class="text-muted small"><i class="fas fa-location-arrow me-1"></i>
                        {{ $branch->address }}</span>
                    <span class="badge bg-success float-end mt-n4">Gần bạn (2km)</span>
                </label>
            </div>
        </div>
    </div>
@endforeach
