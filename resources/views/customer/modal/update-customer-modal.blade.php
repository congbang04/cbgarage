    <div class="modal fade" id="updateProfileModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Cập nhật thông tin cá nhân</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Crop Image Section (Hidden by default) -->
                    <div id="customer_crop_section" style="display: none; margin-bottom: 20px;">
                        <div class="crop-container mb-3">
                            <img id="customer_crop_image" src="" alt="Crop Image"
                                style="max-width: 100%; max-height: 500px;">
                        </div>
                        <div class="crop-controls mb-3">
                            <button type="button" class="btn btn-sm btn-outline-secondary crop-zoom-in"
                                data-target="customer">
                                <i class="fas fa-search-plus"></i> Zoom In
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary crop-zoom-out"
                                data-target="customer">
                                <i class="fas fa-search-minus"></i> Zoom Out
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary crop-reset"
                                data-target="customer">
                                <i class="fas fa-redo"></i> Reset
                            </button>
                            <button type="button" class="btn btn-sm btn-success crop-confirm" data-target="customer">
                                <i class="fas fa-check"></i> Crop
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger crop-cancel"
                                data-target="customer">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                        </div>
                    </div>

                    <form id="customer_update_form" method="POST" action="{{ route('customer.update') }}"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @csrf

                        <div class="row g-3">
                            <!-- Avatar Image Section -->
                            <div class="col-md-6">
                                <label class="form-label">Ảnh đại diện</label>
                                <div class="mb-3">
                                    <input type="file" class="form-control" id="customer_image" name="image"
                                        accept="image/*">
                                    <small class="text-muted d-block mt-2">JPG, PNG, GIF (Max 5MB)</small>
                                </div>
                                <div class="image-preview-container mb-3" id="customer_preview_container"
                                    style="display: none;">
                                    <img id="customer_preview" src="" class="img-fluid rounded"
                                        style="max-height: 300px; object-fit: cover;">
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-warning edit-image-btn"
                                            data-target="customer">
                                            <i class="fas fa-crop-alt"></i> Crop Ảnh
                                        </button>
                                    </div>
                                </div>
                                <!-- Hidden input to store cropped image data -->
                                <input type="hidden" id="customer_cropped_data">
                            </div>

                            <!-- Form Fields Section -->
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label for="name" class="form-label">Họ và tên</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ Auth::user()->customer->name }}" placeholder="Nhập họ tên">
                                </div>

                                <div class="mb-1">
                                    <label for="phone" class="form-label">Số điện thoại</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        value="{{ Auth::user()->customer->phone }}" placeholder="Nhập số điện thoại">
                                </div>

                                <div class="mb-1">
                                    <label for="address" class="form-label">Địa chỉ</label>
                                    <input type="text" class="form-control" id="address" name="address"
                                        value="{{ Auth::user()->customer->address ?? '' }}" placeholder="Nhập địa chỉ">
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
                </form>
            </div>
        </div>
    </div>
