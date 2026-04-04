/**
 * Payment Module - Handle payment UI interactions
 */

import PaymentService from "../../services/payment.js";

/**
 * Initialize payment module
 * Entry point for payment pages
 */
export function initPaymentModule() {
    bindPaymentButtons();
    bindRefundButtons();
    bindFilterSubmit();
    bindPaginationClick();
    bindViewOrderDetails();
    loadTransactions();
}

/**
 * Bind payment button click
 */
function bindPaymentButtons() {
    $(document).on("click", ".btn-pay-order", function (e) {
        e.preventDefault();
        const orderId = $(this).data("order-id");

        if (!orderId) {
            showError("Không tìm thấy mã đơn hàng");
            return;
        }

        // Show confirmation
        if (!confirm("Xác nhận thanh toán cho đơn hàng này?")) {
            return;
        }

        const $btn = $(this);
        $btn.prop("disabled", true).html(
            '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...',
        );

        PaymentService.pay(orderId)
            .done((response) => {
                if (response.success) {
                    showSuccess(response.message);
                    // Reload table or update row
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showError(response.message || "Thanh toán thất bại");
                }
            })
            .fail((xhr) => {
                const errorMsg = xhr.responseJSON?.message || "Có lỗi xảy ra";
                showError(errorMsg);
            })
            .always(() => {
                $btn.prop("disabled", false).html(
                    '<i class="fas fa-money-bill-wave"></i> Thanh toán',
                );
            });
    });
}

/**
 * Bind refund button click
 */
function bindRefundButtons() {
    $(document).on("click", ".btn-refund-order", function (e) {
        e.preventDefault();
        const orderId = $(this).data("order-id");

        if (!orderId) {
            showError("Không tìm thấy mã đơn hàng");
            return;
        }

        // Show confirmation with reason input
        showRefundModal(orderId);
    });
}

/**
 * Show refund modal
 */
function showRefundModal(orderId) {
    const modal = `
        <div class="modal fade" id="refundModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Hoàn tiền đơn hàng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Lý do hoàn tiền</label>
                            <textarea class="form-control" id="refundReason" rows="2" placeholder="Nhập lý do..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea class="form-control" id="refundNotes" rows="2" placeholder="Ghi chú thêm (không bắt buộc)"></textarea>
                        </div>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> 
                            Hành động này không thể hoàn tác. Vui lòng xác nhận cẩn thận!
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-danger" id="confirmRefundBtn">
                            <i class="fas fa-undo"></i> Xác nhận hoàn tiền
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Remove old modal if exists
    $("#refundModal").remove();
    $("body").append(modal);

    const refundModal = new bootstrap.Modal(
        document.getElementById("refundModal"),
    );
    refundModal.show();

    // Handle confirm button
    $("#confirmRefundBtn")
        .off("click")
        .on("click", function () {
            const reason = $("#refundReason").val();
            const notes = $("#refundNotes").val();

            if (!reason.trim()) {
                showError("Vui lòng nhập lý do hoàn tiền");
                return;
            }

            $(this)
                .prop("disabled", true)
                .html('<i class="fas fa-spinner fa-spin"></i> Đang xử lý...');

            PaymentService.refund(orderId, reason, notes)
                .done((response) => {
                    if (response.success) {
                        showSuccess(response.message);
                        refundModal.hide();
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showError(response.message || "Hoàn tiền thất bại");
                    }
                })
                .fail((xhr) => {
                    const errorMsg =
                        xhr.responseJSON?.message || "Có lỗi xảy ra";
                    showError(errorMsg);
                })
                .always(() => {
                    $("#confirmRefundBtn")
                        .prop("disabled", false)
                        .html('<i class="fas fa-undo"></i> Xác nhận hoàn tiền');
                });
        });
}

/**
 * Bind filter form submit
 */
function bindFilterSubmit() {
    $(document).on("submit", "#paymentFilterForm", function (e) {
        e.preventDefault();

        const filters = {
            date_from: $("#filterDateFrom").val(),
            date_to: $("#filterDateTo").val(),
            payment_method: $("#filterPaymentMethod").val(),
            type: $("#filterType").val(),
            branch_id: $("#filterBranch").val(),
        };

        loadTransactions(filters, 1);
    });
}

/**
 * Bind pagination click
 */
function bindPaginationClick() {
    $(document).on("click", ".payment-pagination a", function (e) {
        e.preventDefault();

        const url = $(this).attr("href");
        const page = new URL(url).searchParams.get("page");

        const filters = {
            date_from: $("#filterDateFrom").val(),
            date_to: $("#filterDateTo").val(),
            payment_method: $("#filterPaymentMethod").val(),
            type: $("#filterType").val(),
            branch_id: $("#filterBranch").val(),
        };

        loadTransactions(filters, page);
    });
}

/**
 * Bind view order details
 */
function bindViewOrderDetails() {
    $(document).on("click", ".btn-view-order", function (e) {
        e.preventDefault();
        const orderId = $(this).data("order-id");

        if (!orderId) {
            return;
        }

        const $btn = $(this);
        $btn.prop("disabled", true);

        PaymentService.getOrderDetails(orderId)
            .done((response) => {
                if (response.success) {
                    displayOrderDetailsModal(response.order, response.summary);
                } else {
                    showError("Không thể lấy chi tiết đơn hàng");
                }
            })
            .fail(() => {
                showError("Có lỗi xảy ra");
            })
            .always(() => {
                $btn.prop("disabled", false);
            });
    });
}

/**
 * Load transactions table
 */
function loadTransactions(filters = {}, page = 1) {
    PaymentService.getTransactionsTable(filters, page)
        .done((html) => {
            $("#paymentTableContainer").html(html);
        })
        .fail(() => {
            showError("Không thể tải dữ liệu");
        });
}

/**
 * Display order details in modal
 */
function displayOrderDetailsModal(order, summary) {
    const transactionsHtml =
        order.transactions
            ?.map(
                (t) => `
            <tr>
                <td>${t.transaction_code}</td>
                <td>
                    <span class="badge ${
                        t.type === "payment" ? "bg-success" : "bg-warning"
                    }">
                        ${t.type === "payment" ? "Thanh toán" : "Hoàn tiền"}
                    </span>
                </td>
                <td class="text-end">${(t.amount || 0).toLocaleString(
                    "vi-VN",
                )} đ</td>
                <td>${new Date(t.created_at).toLocaleString("vi-VN")}</td>
            </tr>
        `,
            )
            .join("") ||
        '<tr><td colspan="4" class="text-center text-muted">Chưa có giao dịch</td></tr>';

    const modal = `
        <div class="modal fade" id="orderDetailsModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chi tiết đơn hàng #${
                            order.id
                        }</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p><strong>Khách hàng:</strong> ${
                                    order.customer?.name || "N/A"
                                }</p>
                                <p><strong>SĐT:</strong> ${
                                    order.customer?.phone || "N/A"
                                }</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Chi nhánh:</strong> ${
                                    order.branch?.name || "N/A"
                                }</p>
                                <p><strong>Trạng thái:</strong> <span class="badge bg-info">${
                                    order.status
                                }</span></p>
                            </div>
                        </div>

                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 text-center">
                                        <small>Tổng tiền</small>
                                        <h6>${(
                                            summary.order_amount || 0
                                        ).toLocaleString("vi-VN")} đ</h6>
                                    </div>
                                    <div class="col-md-3 text-center">
                                        <small>Đã thanh toán</small>
                                        <h6 class="text-success">${(
                                            summary.total_paid || 0
                                        ).toLocaleString("vi-VN")} đ</h6>
                                    </div>
                                    <div class="col-md-3 text-center">
                                        <small>Hoàn tiền</small>
                                        <h6 class="text-warning">${(
                                            summary.total_refunded || 0
                                        ).toLocaleString("vi-VN")} đ</h6>
                                    </div>
                                    <div class="col-md-3 text-center">
                                        <small>Còn lại</small>
                                        <h6 class="text-danger">${(
                                            summary.remaining || 0
                                        ).toLocaleString("vi-VN")} đ</h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h6 class="mb-3">Lịch sử giao dịch</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Mã giao dịch</th>
                                        <th>Loại</th>
                                        <th class="text-end">Số tiền</th>
                                        <th>Thời gian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${transactionsHtml}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    $("#orderDetailsModal").remove();
    $("body").append(modal);

    const detailsModal = new bootstrap.Modal(
        document.getElementById("orderDetailsModal"),
    );
    detailsModal.show();
}

/**
 * Show success toast
 */
function showSuccess(message) {
    const toast = `
        <div class="toast-notification toast-success">
            <i class="fas fa-check-circle"></i>
            <span>${message}</span>
        </div>
    `;
    displayToast(toast);
}

/**
 * Show error toast
 */
function showError(message) {
    const toast = `
        <div class="toast-notification toast-error">
            <i class="fas fa-exclamation-circle"></i>
            <span>${message}</span>
        </div>
    `;
    displayToast(toast);
}

/**
 * Display toast notification
 */
function displayToast(toastHtml) {
    const $container = $("#toastContainer");
    if (!$container.length) {
        $("body").append(
            '<div id="toastContainer" class="toast-container"></div>',
        );
    }

    const $toast = $(toastHtml);
    $("#toastContainer").append($toast);

    setTimeout(() => {
        $toast.addClass("show");
    }, 10);

    setTimeout(() => {
        $toast.remove();
    }, 3500);
}

// Initialize on document ready
$(document).ready(function () {
    initPaymentModule();
});

export default initPaymentModule;
