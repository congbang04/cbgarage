<div class="modal fade" id="requestPartModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Yêu cầu vật tư</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div> <!-- Search & Add Parts Section -->
            <div class="card mb-4">
                <div class="card-body">
                    <!-- Search Input -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tìm kiếm vật tư</label>
                        <input type="text" class="form-control" id="partSearch"
                            placeholder="Search theo tên, SKU...">
                    </div>

                    <!-- Part Select -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Vật tư <span class="text-danger">*</span></label>
                        <select class="form-select" name="part_id" id="partSelect" required>
                            <option value="">-- Chọn vật tư --</option>
                            {{-- Options will be populated by JavaScript --}}
                        </select>
                    </div>

                    <!-- Quantity Input -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Số lượng <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="partQuantity" min="1" value="1"
                            required placeholder="Nhập số lượng">
                    </div>

                    <!-- Add Button -->
                    <button type="button" class="btn btn-primary" id="addPartBtn">
                        <i class="fas fa-plus me-2"></i> Thêm vào danh sách
                    </button>
                </div>
            </div>

            <!-- Selected Parts Table -->
            <div class="card">
                <div class="card-header bg-light border-bottom justify-content-center">
                    <h6 class="mb-0 text-uppercase fs-5 fw-bold">
                        Danh sách vật tư yêu cầu
                    </h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Tên vật tư</th>
                                <th>SKU</th>
                                <th style="width: 80px;">SL</th>
                                <th style="width: 100px;">Giá</th>
                                <th style="width: 100px;">Tổng</th>
                                <th style="width: 50px;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody id="selectedPartsTable">
                            <tr id="emptyRow">
                                <td colspan="6" class="text-center text-muted py-3">
                                    Chưa chọn vật tư nào
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-light">
                    <div class="row align-items-center">
                        <div class="col">
                            <small class="text-muted">
                                Tổng cộng: <span id="totalAmount" class="fw-bold">0 đ</span>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="alert alert-info small">
                    <i class="fas fa-info-circle me-1"></i>
                    Yêu cầu sẽ được gửi đến quản lý kho để xử lý
                </div>
            </div>
            <form id="requestPartForm">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                    <button type="submit" class="btn btn-primary" id="submitPartsBtn">
                        <i class="fas fa-check me-1"></i> Gửi yêu cầu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
