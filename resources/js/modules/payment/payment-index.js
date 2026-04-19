/**
 * Payment Index Module
 * Handle payment list page interactions
 * Xử lý tương tác trên trang quản lý thanh toán
 */

import PaymentService from "../../services/payment.js";

// Store current filters for pagination
let currentFilters = {};

/**
 * Initialize payment index module
 * Entry point for payment index page
 */
export function initPaymentIndexModule() {
    bindFilterSubmit();
    bindQuickPaymentButton();
    bindViewOrderDetails();
    bindPaginationClick();
    loadTransactions();
    loadStats();
    loadBranches();
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

        // Save current filters for pagination
        currentFilters = filters;
        loadTransactions(filters);
        loadStats(filters);
    });
}
$(document).on("reset", "#paymentFilterForm", function () {
    const filters = {
        date_from: "",
        date_to: "",
        payment_method: "",
        type: "",
        branch_id: "",
    };

    currentFilters = filters;

    loadTransactions(filters);
    loadStats(filters);
});

/**
 * Bind quick payment button
 */
function bindQuickPaymentButton() {
    $(document).on("click", ".btn-quick-pay", function (e) {
        e.preventDefault();
        const orderId = $(this).data("order-id");

        if (!orderId) {
            showError("Không tìm thấy mã đơn hàng");
            return;
        }

        if (!confirm("Xác nhận thanh toán cho đơn hàng này?")) {
            return;
        }

        const $btn = $(this);
        $btn.prop("disabled", true).html(
            '<i class="fas fa-spinner fa-spin"></i>',
        );

        PaymentService.pay(orderId)
            .done((response) => {
                if (response.success) {
                    showSuccess(response.message || "Thanh toán thành công!");
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showError(response.message || "Thanh toán thất bại");
                    $btn.prop("disabled", false).html(
                        '<i class="fas fa-money-bill-wave"></i>',
                    );
                }
            })
            .fail((xhr) => {
                const errorMsg = xhr.responseJSON?.message || "Có lỗi xảy ra";
                showError(errorMsg);
                $btn.prop("disabled", false).html(
                    '<i class="fas fa-money-bill-wave"></i>',
                );
            });
    });
}

/**
 * Bind view order details button
 */
function bindViewOrderDetails() {
    $(document).on("click", ".btn-view-order-detail", function (e) {
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
 * Bind pagination click
 */
function bindPaginationClick() {
    $(document).on(
        "click",
        "#paymentTableContainer .pagination a",
        function (e) {
            e.preventDefault();
            const url = $(this).attr("href");
            loadTransactions(currentFilters, url);
        },
    );
}

/**
 * Load transactions table
 */
function loadTransactions(filters = {}, paginationUrl = null) {
    const url = paginationUrl || window.routes.paymentTable;

    PaymentService.getTransactionsTable(filters, url)
        .done((html) => {
            $("#paymentTableContainer").html(html);
        })
        .fail(() => {
            showError("Không thể tải dữ liệu");
        });
}
function loadStats(filters) {
    PaymentService.getTransactionsStats(filters)
        .done((html) => {
            $("#stats-cards").html(html);
        })
        .fail(() => {
            showError("Không thể tải dữ liệu thông số");
        });
}

/**
 * Load branches for filter dropdown
 */
function loadBranches() {
    const $branchSelect = $("#filterBranch");

    if ($branchSelect.length === 0) {
        return; // No branch filter on this page
    }

    PaymentService.getBranches()
        .done((branches) => {
            branches.forEach((branch) => {
                $branchSelect.append(
                    `<option value="${branch.id}">${branch.name}</option>`,
                );
            });
        })
        .fail(() => {
            console.error("Failed to load branches");
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
    showToast(message, "success");
}

/**
 * Show error toast
 */
function showError(message) {
    showToast(message, "error");
}

/**
 * Display toast notification
 */
function showToast(message, type = "info") {
    const icon =
        type === "success" ? "fa-check-circle" : "fa-exclamation-circle";
    const className = `toast-${type}`;

    const toastHtml = `
        <div class="toast-notification ${className}">
            <i class="fas ${icon}"></i>
            <span>${message}</span>
        </div>
    `;

    const $container = $("#toastContainer");
    if ($container.length === 0) {
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
    initPaymentIndexModule();
});

export default initPaymentIndexModule;
