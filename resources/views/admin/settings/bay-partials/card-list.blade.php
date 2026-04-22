@php
    // Nhóm bays theo branch
    $baysByBranch = $bays->items();
    $grouped = [];
    foreach ($baysByBranch as $bay) {
        $branchName = $bay->branch->name ?? 'Không xác định';
        if (!isset($grouped[$branchName])) {
            $grouped[$branchName] = [];
        }
        $grouped[$branchName][] = $bay;
    }
@endphp

@forelse ($grouped as $branchName => $bays)
    <div class="mb-5">
        <h5 class="mb-4 ps-2 border-start border-4 border-primary">
            <i class="fas fa-building"></i> {{ $branchName }}
        </h5>
        <div class="row g-3">
            @foreach ($bays as $bay)
                <div class="col-md-3">
                    <div class="card bay-card h-100" data-bay-id="{{ $bay->id }}" data-bay-name="{{ $bay->name }}"
                        data-bay-branch="{{ $bay->branch_id }}" data-bay-type="{{ $bay->type }}"
                        data-bay-status="{{ $bay->status }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="flex-grow-1">
                                    <h6 class="card-title mb-2 fw-bold">{{ $bay->name }}</h6>
                                </div>
                                <div class="bay-actions gap-1 d-flex">
                                    <button type="button" class="btn btn-sm btn-outline-primary edit-bay"
                                        data-bay-id="{{ $bay->id }}" data-bs-toggle="tooltip" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-bay"
                                        data-bay-id="{{ $bay->id }}" data-bs-toggle="tooltip" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="bay-details small">
                                <div class="mb-2">
                                    <strong>Loại:</strong>
                                    <span>{{ $bay->type }}</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Trạng thái:</strong>
                                    <span
                                        class="badge 
                                        @switch($bay->status)
                                            @case('available')
                                                bg-success
                                            @break
                                            @case('in-use')
                                                bg-info
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
                                                <i class="fas fa-check-circle"></i> Có sẵn
                                            @break

                                            @case('in-use')
                                                <i class="fas fa-hourglass-end"></i> Đang sử dụng
                                            @break

                                            @case('maintenance')
                                                <i class="fas fa-wrench"></i> Bảo trì
                                            @break

                                            @case('closed')
                                                <i class="fas fa-lock"></i> Đóng
                                            @break

                                            @default
                                                Không xác định
                                        @endswitch
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @empty
        <div class="alert alert-info" role="alert">
            <i class="fas fa-info-circle"></i> Không có cầu nâng nào. Hãy thêm cầu nâng mới.
        </div>
    @endforelse
