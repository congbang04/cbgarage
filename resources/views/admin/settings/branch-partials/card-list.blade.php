<div class="row g-4">
    @forelse ($branches as $branch)
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="fw-bold mb-1">{{ $branch->name }}</h5>
                            @if ($branch->is_central_warehouse)
                                <span class="badge bg-primary">Trụ sở chính</span>
                                <span class="badge bg-success">Kho tổng</span>
                            @else
                                <span class="badge bg-secondary">Chi nhánh</span>
                            @endif
                        </div>
                        <div class="dropdown dropstart">
                            <button class="btn btn-light btn-sm" data-bs-toggle="dropdown"><i
                                    class="fas fa-ellipsis-v"></i></button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item edit-branch" href="#" data-bs-toggle="modal"
                                        data-bs-target="#create-branch" data-id="{{ $branch->id }}"
                                        data-name="{{ $branch->name }}" data-address="{{ $branch->address }}"
                                        data-phone="{{ $branch->phone }}" data-coordinates="{{ $branch->coordinates }}"
                                        data-is_central_warehouse="{{ $branch->is_central_warehouse }}">
                                        Sửa thông tin
                                    </a></li>
                                <li><a class="dropdown-item" href="#">Quản lý cầu nâng</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item delete-branch text-danger" href="#"
                                        data-id="{{ $branch->id }}">Xóa chi nhánh</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="mb-2"><i class="fas fa-map-marker-alt text-danger width-20"></i>
                        {{ $branch->address }}</div>
                    <div class="mb-3"><i class="fas fa-phone text-success width-20"></i> {{ $branch->phone }}</div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">Cầu nâng:
                            <strong>{{ $branchService->countByBranch($branch->id) ?? 0 }}</strong></small>
                        <small class="text-muted">Nhân sự:
                            <strong>{{ $branchService->countStaffByBranch($branch->id) ?? 0 }}</strong></small>
                        <div class="form-check form-switch">
                            <input class="form-check-input toggle-branch-status" type="checkbox"
                                @checked($branch->is_actived) data-id="{{ $branch->id }}" data-toggle="true">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center" role="alert">
                <i class="fas fa-info-circle"></i> Chưa có chi nhánh nào
            </div>
        </div>
    @endforelse
</div>
@include('layouts.partials.pagination', ['items' => $branches])
