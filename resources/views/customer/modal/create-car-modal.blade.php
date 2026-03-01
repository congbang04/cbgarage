    <div class="modal fade" id="addCarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Thêm xe mới</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form_create_car" method="POST" action="{{ route('car.store') }}">
                        @csrf

                        <div class="row g-3 align-items-end">

                            <div class="mb-1">
                                <label for="name" class="form-label">Biển số xe (*)</label>
                                <input type="text" class="form-control" id="name" name="license_plate"
                                    placeholder="Nhập biển số xe (VD: 51A12345)">
                            </div>

                            <div class="mb-1">
                                <label for="name" class="form-label">Hãng xe (*)</label>
                                <select class="form-select" id="brand_id" name="brand_id">
                                    <option value="">-- Chọn hãng xe --</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-1">
                                <label for="name" class="form-label">Dòng xe (*)</label>
                                <select class="form-select" id="car_model_id" name="car_model_id">
                                    <option value="">-- Chọn dòng xe --</option>
                                </select>
                            </div>

                            <div class="mb-1">
                                <label for="name" class="form-label">Số khung (VIN)</label>
                                <input type="text" class="form-control" id="name" name="chassis_number"
                                    placeholder="Nhập số khung">
                            </div>

                            <div class="mb-1">
                                <label for="name" class="form-label">Năm sản xuất (*)</label>
                                <input type="text" class="form-control" id="manufacture_year" name="manufacture_year"
                                    placeholder="Nhập năm sản xuất">
                            </div>

                            <div class="mb-1">
                                <label for="name" class="form-label">Màu xe (*)</label>
                                <input type="text" class="form-control" id="color" name="color"
                                    placeholder="Nhập màu xe">
                            </div>

                            <div class="mb-1">
                                <label for="name" class="form-label">ODO (*)</label>
                                <input type="text" class="form-control" id="current_odo" name="current_odo"
                                    placeholder="Nhập số ODO hiện tại">
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary">Lưu thông tin</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        const brands = @json($brands);

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
    </script>
