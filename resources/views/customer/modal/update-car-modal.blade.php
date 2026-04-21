    <div class="modal fade" id="updateCarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Cập nhật thông tin xe</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form_update_car" method="POST" action="{{ route('car.update', $car->id) }}"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @csrf

                        <input type="hidden" id="car_id" value="{{ $car->id }}">
                        <div class="row g-3">
                            <!-- Image Section -->
                            <div class="col-md-6">
                                <label class="form-label">Ảnh xe</label>
                                <div class="mb-3">
                                    <input type="file" class="form-control" id="update_car_image" name="image"
                                        accept="image/*">
                                    <small class="text-muted d-block mt-2">JPG, PNG, GIF (Max 5MB)</small>
                                </div>
                                <div class="image-preview-container mb-3" id="update_car_preview_container">
                                    @if ($car->image)
                                        <img id="update_car_preview" src="{{ Storage::disk()->url($car->image) }}"
                                            class="img-fluid rounded" style="max-height: 300px; object-fit: cover;">
                                    @else
                                        <img id="update_car_preview" src="" class="img-fluid rounded d-none"
                                            style="max-height: 300px; object-fit: cover;">
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label for="license_plate" class="form-label">Biển số xe</label>
                                    <input type="text" class="form-control" id="license_plate" name="license_plate"
                                        value="{{ $car->license_plate }}" placeholder="Nhập biển số xe (VD: 51A12345)">
                                </div>

                                <div class="mb-1">
                                    <label for="brand_id" class="form-label">Hãng xe</label>
                                    <select class="form-select" id="brand_id" name="brand_id">
                                        <option value="">-- Chọn hãng xe --</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}"
                                                {{ $car->brand_id == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-1">
                                    <label for="car_model_id" class="form-label">Dòng xe</label>
                                    <select class="form-select" id="car_model_id" name="car_model_id">
                                        <option value="">-- Chọn dòng xe --</option>
                                        @foreach ($car->brand->carModels as $model)
                                            <option value="{{ $model->id }}"
                                                {{ $car->car_model_id == $model->id ? 'selected' : '' }}>
                                                {{ $model->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-1">
                                    <label for="vin_number" class="form-label">Số khung (VIN)</label>
                                    <input type="text" class="form-control" id="vin_number" name="vin_number"
                                        value="{{ $car->vin_number }}" placeholder="Nhập số khung">
                                </div>

                                <div class="mb-1">
                                    <label for="manufacture_year" class="form-label">Năm sản xuất</label>
                                    <input type="text" class="form-control" id="manufacture_year"
                                        name="manufacture_year" value="{{ $car->manufacture_year }}"
                                        placeholder="Nhập năm sản xuất">
                                </div>

                                <div class="mb-1">
                                    <label for="color" class="form-label">Màu xe</label>
                                    <input type="text" class="form-control" id="color" name="color"
                                        value="{{ $car->color }}" placeholder="Nhập màu xe">
                                </div>

                                <div class="mb-1">
                                    <label for="current_odo" class="form-label">ODO</label>
                                    <input type="text" class="form-control" id="current_odo" name="current_odo"
                                        value="{{ $car->current_odo }}" placeholder="Nhập số ODO hiện tại">
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Đóng</button>
                                <button type="submit" class="btn btn-primary">Lưu thông tin</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        const brands = @json($brands);

        // Handle brand change
        document.getElementById('brand_id').addEventListener('change', function() {
            let brandId = this.value;
            let modelSelect = document.getElementById('car_model_id');

            modelSelect.innerHTML = '<option value="">-- Chọn dòng xe --</option>';

            let selectedBrand = brands.find(b => b.id == brandId);

            if (selectedBrand) {
                selectedBrand.car_models.forEach(model => {
                    let option = document.createElement('option');
                    option.value = model.id;
                    option.textContent = model.name;
                    modelSelect.appendChild(option);
                });
            }
        });

        // Handle image preview
        document.getElementById('update_car_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const previewContainer = document.getElementById('update_car_preview_container');
            const previewImg = document.getElementById('update_car_preview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewImg.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
