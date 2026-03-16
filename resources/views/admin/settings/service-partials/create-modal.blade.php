<div class="modal fade" id="create-service" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title service-modal-title">Thêm dịch vụ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="form_create_service" method="POST" action="{{ route('services.store') }}"
                enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <input type="hidden" id="input-service-id" name="id">

                    <!-- Tên dịch vụ -->
                    <div class="mb-3">
                        <label class="form-label">
                            Tên dịch vụ <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="name" placeholder="Nhập tên dịch vụ">
                    </div>

                    <!-- Icon -->
                    <div class="mb-3">
                        <label class="form-label">Icon dịch vụ</label>
                        <input type="file" class="form-control" name="icon" id="service_icon" accept="image/*">
                        <!-- preview -->
                        <div class="service-icon-preview mt-3">
                            <img id="icon_preview" src="">
                        </div>

                    </div>

                    <!-- Giá -->
                    <div class="mb-3">
                        <label class="form-label">
                            Giá niêm yết <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control" name="price" placeholder="Nhập giá dịch vụ">
                    </div>

                    <!-- Thời gian -->
                    <div class="mb-3">
                        <label class="form-label">
                            Thời gian thực hiện (phút)
                        </label>
                        <input type="number" class="form-control" name="estimated_duration" placeholder="VD: 30">
                    </div>

                    <!-- Mô tả -->
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Mô tả dịch vụ"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Đóng
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Lưu dịch vụ
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
