@forelse($requests as $request)
    <div class="col-md-6 col-lg-3">
        <div class="card h-100 border-top border-4" data-request-id="{{ $request->id }}"
            data-status="{{ $request->status }}">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">#TRA-{{ str_pad($request->id, 5, '0', STR_PAD_LEFT) }}</h5>

                @php
                    $statusClass = match ($request->status) {
                        'PENDING' => 'bg-warning text-dark',
                        'COMPLETED' => 'bg-success',
                        'CANCELED' => 'bg-danger',
                        default => 'bg-primary',
                    };
                @endphp
                <span class="badge {{ $statusClass }} px-3 py-2 fs-6" id="status-{{ $request->id }}">
                    {{ match ($request->status) {
                        'PENDING' => 'Chờ duyệt',
                        'COMPLETED' => 'Đã xuất',
                        'CANCELED' => 'Đã hủy',
                        default => 'Đang vận chuyển',
                    } }}
                </span>
            </div>

            <div class="card-body d-flex flex-column">
                <div class="d-flex mb-3">
                    <div class="me-3">
                        <div class="bg-light rounded p-2 text-center" style="width: 50px;">
                            <i class="fas fa-user-cog fs-4 text-secondary"></i>
                        </div>
                    </div>
                    <div>
                        <div class="fw-bold">Kho: {{ $request->toBranch->name ?? 'N/A' }}</div>
                        <div class="small text-muted">
                            Từ kho: {{ $request->fromBranch->name ?? 'N/A' }}
                            - Đến kho: {{ $request->toBranch->name ?? 'N/A' }}
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

                @if ($request->status === 'PENDING')
                    <div class="d-grid gap-2 mt-auto">
                        <button class="btn btn-success" onclick="approveTransferRequest({{ $request->id }})">
                            <i class="fas fa-check"></i>
                            Xuất kho
                        </button>
                        <button class="border border-danger btn btn-outline-danger"
                            onclick="rejectTransferRequest({{ $request->id }}, 'reject')">
                            <i class="fa-solid fa-x"></i>
                            Từ chối
                        </button>
                    </div>
                @elseif ($request->status === 'CANCELED')
                    <div class="d-grid gap-2 mt-auto">
                        <button class="bg-danger-subtle btn btn-outline-danger btn btn-light w-100 border" disabled>
                            Đã hủy</button>
                    </div>
                @elseif ($request->status === 'COMPLETED')
                    <div class="d-grid gap-2 mt-auto">
                        <button class="bg-success-subtle text-success btn btn-light w-100 border" disabled>
                            Đã hoàn thành</button>
                    </div>
                @else
                    <div class="d-grid gap-2 mt-auto">
                        <button class="bg-primary-subtle text-primary btn btn-light w-100 border" disabled>
                            <i class="fa-solid fa-truck"></i>
                            Đang vận chuyển
                        </button>
                        <button class="border border-danger btn btn-outline-danger"
                            onclick="rejectTransferRequest({{ $request->id }}, 'cancel')">
                            <i class="fa-solid fa-x"></i>
                            Hủy vận chuyển
                        </button>
                    </div>
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
<!-- Pagination -->
@if ($requests->total() > $requests->perPage())
    <div class="card-footer bg-white border-top-0 d-flex justify-content-end py-3">
        <style>
            .pagination-container nav div:first-child {
                display: none !important;
            }

            .pagination-container nav div:last-child {
                display: flex !important;
            }
        </style>

        <div class="pagination-container" data-type="transfer">
            {{ $requests->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif
