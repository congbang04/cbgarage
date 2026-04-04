<div class="modal fade" id="refundModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hoàn tiền</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="refundOrderId">

                <div class="mb-3">
                    <label class="form-label">Lý do hoàn tiền</label>
                    <input type="text" id="refundReason" class="form-control" placeholder="Nhập lý do...">
                </div>

                <div class="mb-3">
                    <label class="form-label">Ghi chú</label>
                    <textarea id="refundNotes" class="form-control" rows="3" placeholder="Nhập ghi chú..."></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>

                <button type="button" class="btn btn-danger" id="confirmRefund">
                    <i class="fas fa-undo"></i> Xác nhận hoàn tiền
                </button>
            </div>
        </div>
    </div>
</div>
