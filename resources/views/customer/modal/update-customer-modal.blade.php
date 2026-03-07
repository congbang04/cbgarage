    <div class="modal fade" id="updateProfileModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Cập nhật thông tin cá nhân</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="customer_update_form" method="POST" action="{{ route('customer.update') }}">
                        @method('PUT')
                        @csrf

                        <div class="row g-3 align-items-end">

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
                                <label for="name" class="form-label">Địa chỉ</label>
                                <input type="text" class="form-control" id="address" name="address"
                                    value="{{ Auth::user()->customer->address ?? '' }}" placeholder="Nhập địa chỉ">
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
