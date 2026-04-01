<div class="card mb-4">
    <div class="card-body">
        <form id="form_search_part" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Mã SKU</label>
                <input type="text" class="form-control" name="sku" placeholder="Tìm mã SKU..."
                    value="{{ request('sku') }}">
            </div>

            <div class="col-md-3">
                <label class="form-label">Tên phụ tùng</label>
                <input type="text" class="form-control" name="name" placeholder="Tìm tên..."
                    value="{{ request('name') }}">
            </div>

            <div class="col-md-2">
                <label class="form-label">Đơn vị</label>
                <select class="form-select" name="unit">
                    <option value="">-- Tất cả --</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit }}" @selected(request('unit') == $unit)>
                            {{ $unit }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">Giá từ</label>
                <input type="number" class="form-control" name="price_from" placeholder="0"
                    value="{{ request('price_from') }}" step="0.01">
            </div>

            <div class="col-md-2">
                <label class="form-label">Giá đến</label>
                <input type="number" class="form-control" name="price_to" placeholder="Không giới hạn"
                    value="{{ request('price_to') }}" step="0.01">
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

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Mã phụ tùng</th>
                        <th>Tên phụ tùng</th>
                        <th>Đơn vị</th>
                        <th class="text-end">Giá bán</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($parts as $part)
                        <tr>
                            <td class="fw-bold">{{ $part->sku }}</td>

                            <td>
                                {{ $part->name }}
                            </td>

                            <td>
                                {{ $part->unit }}
                            </td>

                            <td class="text-end fw-bold text-primary">
                                {{ number_format($part->price_sell) }}đ
                            </td>

                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-light border edit-part"
                                    data-bs-toggle="modal" data-bs-target="#create-part" data-id="{{ $part->id }}"
                                    data-sku="{{ $part->sku }}" data-name="{{ $part->name }}"
                                    data-price_sell="{{ $part->price_sell }}" data-unit="{{ $part->unit }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-light border delete-part" data-id="{{ $part->id }}">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Chưa có phụ tùng nào
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @include('layouts.partials.pagination', ['items' => $parts])
</div>
