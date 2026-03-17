<div class="modal fade" id="create-branch" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title branch-modal-title">Thêm chi nhánh</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="form_create_branch" method="POST" action="{{ route('branches.store') }}"
                enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <input type="hidden" id="input-branch-id" name="id">

                    <!-- Tên chi nhánh -->
                    <div class="mb-3">
                        <label class="form-label">
                            Tên chi nhánh <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="name" placeholder="Nhập tên chi nhánh">
                    </div>

                    <!-- Địa chỉ -->
                    <div class="mb-3">
                        <label class="form-label">
                            Địa chỉ <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="address" placeholder="Nhập địa chỉ chi nhánh">
                    </div>

                    <!-- Số điện thoại -->
                    <div class="mb-3">
                        <label class="form-label">
                            Số điện thoại <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="phone" placeholder="Nhập số điện thoại">
                    </div>

                    <!-- Tọa độ -->
                    <div class="mb-3">
                        <label class="form-label">
                            Tọa độ địa chỉ
                        </label>
                        <input type="text" class="form-control" name="coordinates"
                            placeholder="VD: 10.7769, 106.7009">
                    </div>

                    <!-- Trụ sở chính -->
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="hidden" name="is_central_warehouse" value="0">
                            <input class="form-check-input" type="checkbox" id="check-central-warehouse"
                                name="is_central_warehouse" value="1">
                            <label class="form-check-label" for="check-central-warehouse">
                                Là trụ sở chính / Kho tổng
                            </label>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Đóng
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Lưu chi nhánh
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
