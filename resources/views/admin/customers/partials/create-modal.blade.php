<div class="modal fade" id="create-customer" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title customer-modal-title">Thêm khách hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="form_create_customer" method="POST">
                @csrf

                <div class="modal-body">
                    <input type="hidden" id="input-customer-id" name="id" value="">

                    <!-- Name -->
                    <div class="mb-3">
                        <label class="form-label">
                            Tên khách hàng <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="name" placeholder="Nhập tên khách hàng">
                        <div class="invalid-feedback d-block"></div>
                    </div>

                    <!-- Phone -->
                    <div class="mb-3">
                        <label class="form-label">
                            Số điện thoại <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="phone" placeholder="Nhập số điện thoại">
                        <div class="invalid-feedback d-block"></div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label">
                            Email
                        </label>
                        <input type="email" class="form-control" name="email" placeholder="Nhập email (tùy chọn)">
                        <div class="invalid-feedback d-block"></div>
                    </div>

                    <!-- Address -->
                    <div class="mb-3">
                        <label class="form-label">
                            Địa chỉ
                        </label>
                        <textarea class="form-control" name="address" rows="3" placeholder="Nhập địa chỉ (tùy chọn)"></textarea>
                        <div class="invalid-feedback d-block"></div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Đóng
                    </button>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu khách hàng
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
