@if ($items->hasPages())
    <div class="card-footer bg-white border-top-0 py-3">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-3">
            <div class="pagination-container" data-type="{{ $type ?? 'default' }}">
                {{-- Giao diện Desktop: Đầy đủ số trang --}}
                <div class="d-none d-md-block">
                    {{ $items->links('pagination::bootstrap-5') }}
                </div>

                {{-- Giao diện Mobile: Chỉ hiện nút Trước/Sau (Simple Pagination) --}}
                <div class="d-md-none">
                    <nav class="d-flex justify-content-center">
                        <ul class="pagination pagination-sm mb-0">
                            {{-- Nút Previous --}}
                            @if ($items->onFirstPage())
                                <li class="page-item"><span class="page-link" rel="prev">
                                        <i class="fas fa-chevron-left"></i></span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $items->previousPageUrl() }}"
                                        rel="prev"><i class="fas fa-chevron-left"></i></a></li>
                            @endif

                            {{-- Hiển thị trang hiện tại / tổng trang --}}
                            <li class="page-item disabled">
                                <span class="page-link text-dark fw-bold">
                                    {{ $items->currentPage() }} / {{ $items->lastPage() }}
                                </span>
                            </li>

                            {{-- Nút Next --}}
                            @if ($items->hasMorePages())
                                <li class="page-item"><a class="page-link" href="{{ $items->nextPageUrl() }}"
                                        rel="next"><i class="fas fa-chevron-right"></i></a></li>
                            @else
                                <li class="page-item disabled"><span class="page-link"><i
                                            class="fas fa-chevron-right"></i></span></li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endif

<style>
    /* Fix lỗi hiển thị mặc định của Laravel Pagination */
    .pagination-container nav div:first-child {
        display: none !important;
    }

    .pagination-container nav div:last-child {
        display: flex !important;
    }

    @media (max-width: 767.98px) {
        .pagination-container .page-link {
            padding: 0.5rem 0.85rem;
            font-size: 0.875rem;
        }
    }
</style>
