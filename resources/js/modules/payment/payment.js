/**
 * Payment Module - Handle payment UI interactions
 */

import PaymentService from "../../services/payment.js";

class PaymentModule {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadTransactions();
    }

    bindEvents() {
        // Payment button click
        $(document).on(
            "click",
            ".btn-pay-order",
            this.handlePayment.bind(this),
        );

        // Refund button click
        $(document).on(
            "click",
            ".btn-refund-order",
            this.handleRefund.bind(this),
        );

        // Filter form submit
        $(document).on(
            "submit",
            "#paymentFilterForm",
            this.handleFilter.bind(this),
        );

        // Pagination click
        $(document).on(
            "click",
            ".payment-pagination a",
            this.handlePagination.bind(this),
        );

        // Order details (modal)
        $(document).on(
            "click",
            ".btn-view-order",
            this.showOrderDetails.bind(this),
        );
    }

    /**
     * Handle payment button click
     */
    handlePayment(e) {
        e.preventDefault();
        const orderId = $(e.currentTarget).data("order-id");

        if (!orderId) {
            this.showError("Không tìm thấy mã đơn hàng");
            return;
        }

        // Show confirmation
        if (!confirm("Xác nhận thanh toán cho đơn hàng này?")) {
            return;
        }

        const $btn = $(e.currentTarget);
        $btn.prop("disabled", true).html(
            '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...',
        );

        PaymentService.pay(orderId)
            .done((response) => {
                if (response.success) {
                    this.showSuccess(response.message);
                    // Reload table or update row
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    this.showError(response.message || "Thanh toán thất bại");
                }
            })
            .fail((xhr) => {
                const errorMsg = xhr.responseJSON?.message || "Có lỗi xảy ra";
                this.showError(errorMsg);
            })
            .always(() => {
                $btn.prop("disabled", false).html(
                    '<i class="fas fa-money-bill-wave"></i> Thanh toán',
                );
            });
    }

    /**
     * Handle refund button click
     */
    handleRefund(e) {
        e.preventDefault();
        const orderId = $(e.currentTarget).data("order-id");

        if (!orderId) {
            this.showError("Không tìm thấy mã đơn hàng");
            return;
        }

        // Show confirmation with reason input
        this.showRefundModal(orderId);
    }

    /**
     * Show refund modal
     */
    showRefundModal(orderId) {
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
            .on("click", () => {
                const reason = $("#refundReason").val();
                const notes = $("#refundNotes").val();

                if (!reason.trim()) {
                    this.showError("Vui lòng nhập lý do hoàn tiền");
                    return;
                }

                $("#confirmRefundBtn")
                    .prop("disabled", true)
                    .html(
                        '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...',
                    );

                PaymentService.refund(orderId, reason, notes)
                    .done((response) => {
                        if (response.success) {
                            this.showSuccess(response.message);
                            refundModal.hide();
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            this.showError(
                                response.message || "Hoàn tiền thất bại",
                            );
                        }
                    })
                    .fail((xhr) => {
                        const errorMsg =
                            xhr.responseJSON?.message || "Có lỗi xảy ra";
                        this.showError(errorMsg);
                    })
                    .always(() => {
                        $("#confirmRefundBtn")
                            .prop("disabled", false)
                            .html(
                                '<i class="fas fa-undo"></i> Xác nhận hoàn tiền',
                            );
                    });
            });
    }

    /**
     * Handle filter form submit
     */
    handleFilter(e) {
        e.preventDefault();

        const filters = {
            date_from: $("#filterDateFrom").val(),
            date_to: $("#filterDateTo").val(),
            payment_method: $("#filterPaymentMethod").val(),
            type: $("#filterType").val(),
            branch_id: $("#filterBranch").val(),
        };

        this.loadTransactions(filters, 1);
    }

    /**
     * Handle pagination click
     */
    handlePagination(e) {
        e.preventDefault();

        const url = $(e.currentTarget).attr("href");
        const page = new URL(url).searchParams.get("page");

        const filters = {
            date_from: $("#filterDateFrom").val(),
            date_to: $("#filterDateTo").val(),
            payment_method: $("#filterPaymentMethod").val(),
            type: $("#filterType").val(),
            branch_id: $("#filterBranch").val(),
        };

        this.loadTransactions(filters, page);
    }

    /**
     * Load transactions table
     */
    loadTransactions(filters = {}, page = 1) {
        PaymentService.getTransactionsTable(filters, page)
            .done((html) => {
                $("#paymentTableContainer").html(html);
            })
            .fail(() => {
                this.showError("Không thể tải dữ liệu");
            });
    }

    /**
     * Show order details in modal
     */
    showOrderDetails(e) {
        e.preventDefault();
        const orderId = $(e.currentTarget).data("order-id");

        if (!orderId) {
            return;
        }

        const $btn = $(e.currentTarget);
        $btn.prop("disabled", true);

        PaymentService.getOrderDetails(orderId)
            .done((response) => {
                if (response.success) {
                    this.displayOrderDetailsModal(
                        response.order,
                        response.summary,
                    );
                } else {
                    this.showError("Không thể lấy chi tiết đơn hàng");
                }
            })
            .fail(() => {
                this.showError("Có lỗi xảy ra");
            })
            .always(() => {
                $btn.prop("disabled", false);
            });
    }

    /**
     * Display order details in modal
     */
    displayOrderDetailsModal(order, summary) {
        const transactionsHtml =
            order.transactions
                ?.map(
                    (t) => `
            <tr>
                <td>${t.transaction_code}</td>
                <td>
                    <span class="badge ${t.type === "payment" ? "bg-success" : "bg-warning"}">
                        ${t.type === "payment" ? "Thanh toán" : "Hoàn tiền"}
                    </span>
                </td>
                <td class="text-end">${(t.amount || 0).toLocaleString("vi-VN")} đ</td>
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
                            <h5 class="modal-title">Chi tiết đơn hàng #${order.id}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <p><strong>Khách hàng:</strong> ${order.customer?.name || "N/A"}</p>
                                    <p><strong>SĐT:</strong> ${order.customer?.phone || "N/A"}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Chi nhánh:</strong> ${order.branch?.name || "N/A"}</p>
                                    <p><strong>Trạng thái:</strong> <span class="badge bg-info">${order.status}</span></p>
                                </div>
                            </div>

                            <div class="card bg-light mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 text-center">
                                            <small>Tổng tiền</small>
                                            <h6>${(summary.order_amount || 0).toLocaleString("vi-VN")} đ</h6>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <small>Đã thanh toán</small>
                                            <h6 class="text-success">${(summary.total_paid || 0).toLocaleString("vi-VN")} đ</h6>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <small>Hoàn tiền</small>
                                            <h6 class="text-warning">${(summary.total_refunded || 0).toLocaleString("vi-VN")} đ</h6>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <small>Còn lại</small>
                                            <h6 class="text-danger">${(summary.remaining || 0).toLocaleString("vi-VN")} đ</h6>
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
    showSuccess(message) {
        const toast = `
            <div class="toast-notification toast-success">
                <i class="fas fa-check-circle"></i>
                <span>${message}</span>
            </div>
        `;
        this.displayToast(toast);
    }

    /**
     * Show error toast
     */
    showError(message) {
        const toast = `
            <div class="toast-notification toast-error">
                <i class="fas fa-exclamation-circle"></i>
                <span>${message}</span>
            </div>
        `;
        this.displayToast(toast);
    }

    /**
     * Display toast notification
     */
    displayToast(toastHtml) {
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
}

// Initialize on document ready
$(document).ready(function () {
    new PaymentModule();
});
