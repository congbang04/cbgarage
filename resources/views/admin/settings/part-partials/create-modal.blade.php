<div class="modal fade" id="create-part" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title part-modal-title">Thêm phụ tùng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="form_create_part" method="POST" action="{{ route('parts.store') }}"
                enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <input type="hidden" id="input-part-id" name="id">

                    <!-- Mã SKU -->
                    <div class="mb-3">
                        <label class="form-label">
                            Mã SKU <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="sku" placeholder="Nhập mã SKU">
                    </div>

                    <!-- Tên phụ tùng -->
                    <div class="mb-3">
                        <label class="form-label">
                            Tên phụ tùng <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="name" placeholder="Nhập tên phụ tùng">
                    </div>

                    <!-- Giá bán -->
                    <div class="mb-3">
                        <label class="form-label">
                            Giá bán <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control" name="price_sell" placeholder="Nhập giá bán"
                            step="0.01">
                    </div>

                    <!-- Đơn vị -->
                    <div class="mb-3">
                        <label class="form-label">
                            Đơn vị <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="unit" placeholder="VD: Cái, Bộ, Chiếc">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Đóng
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Lưu phụ tùng
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
