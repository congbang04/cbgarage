<!-- Create/Edit Bay Modal -->
<div class="modal fade" id="create-bay" tabindex="-1" aria-labelledby="createBayLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createBayLabel">Thêm cầu nâng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="bay-form">
                @csrf
                <input type="hidden" id="bay-id" name="bay_id" value="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="bay-name" class="form-label">Tên cầu nâng</label>
                        <input type="text" class="form-control" id="bay-name" name="name"
                            placeholder="Nhập tên cầu nâng" required>
                        <div class="invalid-feedback" id="name-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="bay-branch" class="form-label">Chi nhánh</label>
                        <select class="form-select" id="bay-branch" name="branch_id" required>
                            <option value="">Chọn chi nhánh</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="branch_id-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="bay-status" class="form-label">Trạng thái</label>
                        <select class="form-select" id="bay-status" name="status" required>
                            <option value="">Chọn trạng thái</option>
                            <option value="available">Có sẵn</option>
                            <option value="maintenance">Bảo trì</option>
                            <option value="closed">Đóng</option>
                        </select>
                        <div class="invalid-feedback" id="status-error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>
