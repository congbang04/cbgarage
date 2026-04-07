<div class="modal fade" id="create-staff" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title staff-modal-title">Thêm nhân viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="form_create_staff" method="POST" action="{{ route('staff.store') }}">
                @csrf

                <div class="modal-body">
                    <input type="hidden" id="input-staff-id" name="id">

                    <!-- Email (Username) -->
                    <div class="mb-3">
                        <label class="form-label">
                            Email (Tên đăng nhập) <span class="text-danger">*</span>
                        </label>
                        <input type="email" class="form-control" name="email" placeholder="nhap@garage.com">
                        <div class="invalid-feedback d-block"></div>
                    </div>

                    <!-- Role -->
                    <div class="mb-3">
                        <label class="form-label">
                            Vai trò <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" name="role">
                            <option value="">-- Chọn vai trò --</option>
                            <option value="BRANCH_ADMIN">Quản lý chi nhánh</option>
                            <option value="TECHNICIAN">Kỹ thuật viên</option>
                            <option value="ADVISOR">Cố vấn dịch vụ</option>
                            <option value="WAREHOUSE">Thủ kho</option>
                            <option value="CASHIER">Thu ngân</option>
                        </select>
                        <div class="invalid-feedback d-block"></div>
                    </div>

                    <!-- Branch -->
                    <div class="mb-3">
                        <label class="form-label">
                            Chi nhánh làm việc <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" name="branch_id">
                            <option value="">-- Chọn chi nhánh --</option>
                            @forelse($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @empty
                            @endforelse
                        </select>
                        <div class="invalid-feedback d-block"></div>
                    </div>

                    <!-- Default Password Notice -->
                    <div class="alert alert-info small mb-0">
                        <i class="fas fa-info-circle"></i>
                        <strong>Là Quản lý tổng</strong> khi Vai trò là <strong>"Quản lý chi nhánh"</strong>
                        <br>
                        và Chi nhánh là <strong>"Kho trung tâm CBAUTO"</strong>
                        <br><br>
                        <i class="fas fa-info-circle"></i>
                        <strong>Mật khẩu mặc định:</strong> CBAUTO@123
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Đóng
                    </button>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu nhân viên
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
