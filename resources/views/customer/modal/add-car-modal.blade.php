    <div class="modal fade" id="addCarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Thêm xe mới</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form_update_profile" method="POST" action="">
                        @csrf

                        <div class="row g-3 align-items-end">

                            <div class="mb-1">
                                <label for="name" class="form-label">Biển số xe</label>
                                <input type="text" class="form-control" id="name" name="license_plate"
                                    value="{{ Auth::user()->customer->license_plate ?? '' }}"
                                    placeholder="Nhập biển số xe" @error('license_plate') is-invalid @enderror>
                                @error('license_plate')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <input type="text" class="form-control" id="user_id" name="user_id" hidden
                                    value="{{ Auth::user()->customer->user_id ?? 'NULL' }}">
                            </div>

                            <div class="mb-1">
                                <label for="name" class="form-label">Hãng xe</label>
                                <input type="text" class="form-control" id="name" name="car_brand"
                                    value="{{ Auth::user()->customer->car_brand ?? '' }}" placeholder="Nhập hãng xe"
                                    @error('car_brand') is-invalid @enderror>
                                @error('car_brand')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-1">
                                <label for="name" class="form-label">Dòng xe</label>
                                <input type="text" class="form-control" id="name" name="car_model"
                                    value="{{ Auth::user()->customer->car_model ?? '' }}" placeholder="Nhập dòng xe"
                                    @error('car_model') is-invalid @enderror>
                                @error('car_model')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <input type="text" class="form-control" id="user_id" name="user_id" hidden
                                    value="{{ Auth::user()->customer->user_id ?? 'NULL' }}">
                            </div>

                            <div class="mb-1">
                                <label for="name" class="form-label">Số khung (VIN)</label>
                                <input type="text" class="form-control" id="name" name="chassis_number"
                                    value="{{ Auth::user()->customer->chassis_number ?? '' }}"
                                    placeholder="Nhập số khung" @error('chassis_number') is-invalid @enderror>
                                @error('chassis_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <input type="text" class="form-control" id="user_id" name="user_id" hidden
                                    value="{{ Auth::user()->customer->user_id ?? 'NULL' }}">
                            </div>

                            <div class="mb-1">
                                <label for="name" class="form-label">ODO</label>
                                <input type="text" class="form-control" id="name" name="odo"
                                    value="{{ Auth::user()->customer->odo ?? '' }}" placeholder="Nhập số km đã đi"
                                    @error('odo') is-invalid @enderror>
                                @error('odo')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
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
