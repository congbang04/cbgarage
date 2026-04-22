@forelse ($bays->items() as $bay)
    <div class="card mb-3 bay-card" data-bay-id="{{ $bay->id }}" data-bay-name="{{ $bay->name }}"
        data-bay-branch="{{ $bay->branch_id }}" data-bay-type="{{ $bay->type }}" data-bay-status="{{ $bay->status }}">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <h5 class="card-title mb-2">{{ $bay->name }}</h5>
                    <div class="bay-details">
                        <p class="mb-2">
                            <strong>Chi nhánh:</strong>
                            <span class="badge bg-info">{{ $bay->branch->name ?? 'N/A' }}</span>
                        </p>
                        <p class="mb-0">
                            <strong>Trạng thái:</strong>
                            <span
                                class="badge 
                                @switch($bay->status)
                                    @case('available')
                                        bg-success
                                    @break
                                    @case('maintenance')
                                        bg-warning
                                    @break
                                    @case('closed')
                                        bg-danger
                                    @break
                                    @default
                                        bg-secondary
                                @endswitch
                            ">
                                @switch($bay->status)
                                    @case('available')
                                        Có sẵn
                                    @break

                                    @case('maintenance')
                                        Bảo trì
                                    @break

                                    @case('closed')
                                        Đóng
                                    @break

                                    @default
                                        Không xác định
                                @endswitch
                            </span>
                        </p>
                    </div>
                </div>
                <div class="bay-actions">
                    <button type="button" class="btn btn-sm btn-outline-primary edit-bay"
                        data-bay-id="{{ $bay->id }}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger delete-bay"
                        data-bay-id="{{ $bay->id }}">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @empty
        <div class="alert alert-info" role="alert">
            <i class="fas fa-info-circle"></i> Không có cầu nâng nào. Hãy thêm cầu nâng mới.
        </div>
    @endforelse

    @include('layouts.partials.pagination', ['items' => $bays])
