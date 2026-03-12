@auth
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label small">Họ và tên (*)</label>
            <input type="text" class="form-control" name="contact_name" placeholder="Nhập họ và tên">
        </div>
        <div class="mb-3">
            <label class="form-label small">Số điện thoại (*)</label>
            <input type="tel" class="form-control" name="contact_phone" placeholder="Nhập số điện thoại">
        </div>
        <div class="mb-3">
            <label class="form-label small">Chọn xe</label>
            <select class="form-select" name="car_id">
                <option value="" selected>---Chọn xe của bạn---</option>
                @foreach ($cars as $car)
                    <option value="{{ $car->id }}">
                        {{ $car->brand->name }} {{ $car->carModel->name }} - {{ $car->license_plate }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-0">
            <label class="form-label small">Ghi chú thêm</label>
            <textarea class="form-control" rows="2" name="description" placeholder="VD: Xe bị kêu ở gầm..."></textarea>
        </div>
    </div>
@endauth
@guest
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label small">Họ và tên (*)</label>
            <input type="text" class="form-control" name="contact_name" placeholder="Nhập họ và tên">
        </div>
        <div class="mb-3">
            <label class="form-label small">Số điện thoại (*)</label>
            <input type="tel" class="form-control" name="contact_phone" placeholder="Nhập số điện thoại">
        </div>
        <div class="mb-0">
            <label class="form-label small">Ghi chú thêm</label>
            <textarea class="form-control" rows="2" name="description" placeholder="VD: Xe bị kêu ở gầm..."></textarea>
        </div>
    </div>
@endguest
