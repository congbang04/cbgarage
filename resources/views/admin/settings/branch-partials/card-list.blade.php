<div class="card mb-4">
    <div class="card-body">
        <form id="form_search_branch" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Tên chi nhánh</label>
                <input type="text" class="form-control" name="name" placeholder="Tìm tên chi nhánh..."
                    value="{{ request('name') }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Địa chỉ</label>
                <input type="text" class="form-control" name="address" placeholder="Tìm địa chỉ..."
                    value="{{ request('address') }}">
            </div>

            <div class="col-md-2">
                <label class="form-label">Loại</label>
                <select class="form-select" name="is_central_warehouse">
                    <option value="">-- Tất cả --</option>
                    <option value="1" @selected(request('is_central_warehouse') == '1')>
                        Trụ sở chính
                    </option>
                    <option value="0" @selected(request('is_central_warehouse') == '0')>
                        Chi nhánh thường
                    </option>
                </select>
            </div>

            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Tìm kiếm
                </button>
                <button type="button" class="btn btn-secondary" id="btn_reset_search">
                    <i class="fas fa-redo"></i> Xóa bộ lọc
                </button>
            </div>
        </form>
    </div>
</div>

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
