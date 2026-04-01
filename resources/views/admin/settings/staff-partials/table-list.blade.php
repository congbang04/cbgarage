<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="text-start">Email</th>
                        <th class="text-center">Họ tên</th>
                        <th class="text-center">Vai trò</th>
                        <th class="text-center">Chuyên môn</th>
                        <th class="text-center">Chi nhánh</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($staff as $item)
                        <tr>
                            <td class="fw-bold text-start">{{ $item->email }}</td>
                            <td class="fw-bold text-center">{{ optional($item->technician)->name }}</td>

                            <td class="text-center">
                                @php
                                    $roleLabels = [
                                        'TECHNICIAN' => 'Kỹ thuật viên',
                                        'ADVISOR' => 'Cố vấn dịch vụ',
                                        'WAREHOUSE' => 'Thủ kho',
                                        'CASHIER' => 'Thu ngân',
                                        'BRANCH_ADMIN' => 'Quản trị viên',
                                    ];
                                    $badgeClass = match ($item->role) {
                                        'TECHNICIAN' => 'bg-warning',
                                        'ADVISOR' => 'bg-primary',
                                        'WAREHOUSE' => 'bg-info',
                                        'CASHIER' => 'bg-success',
                                        'BRANCH_ADMIN' => 'bg-danger',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}" style="width: 100px">
                                    {{ $roleLabels[$item->role] ?? $item->role }}
                                </span>
                            </td>
                            <td class="text-center">
                                {{ optional($item->technician)->specialization }}
                            </td>
                            <td class="text-center">
                                @if ($item->branch)
                                    <span class="small">{{ $item->branch->name }}</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-link toggle-staff-status"
                                    data-id="{{ $item->id }}" data-active="{{ $item->is_actived }}">
                                    <i class="fas {{ $item->is_actived ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                    <span class="badge {{ $item->is_actived ? 'bg-success' : 'bg-danger' }}">
                                        {{ $item->is_actived ? 'Hoạt động' : 'Ngừng hoạt động' }}
                                    </span>
                                </button>
                            </td>

                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-light border edit-staff"
                                    data-bs-toggle="modal" data-bs-target="#create-staff" data-id="{{ $item->id }}"
                                    data-email="{{ $item->email }}" data-role="{{ $item->role }}"
                                    data-branch_id="{{ $item->branch_id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-light border delete-staff"
                                    data-id="{{ $item->id }}">
                                    <i class="fas fa-trash text-danger"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Chưa có nhân viên nào
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @include('layouts.partials.pagination', ['items' => $staff])
</div>
