/**
 * Payment Detail Module
 * Handle payment detail page interactions
 * Xử lý tương tác trên trang chi tiết thanh toán
 */

import PaymentService from "../../services/payment.js";

class PaymentDetailModule {
    constructor() {
        this.selectedPaymentMethod = "cash";
        this.init();
    }

    init() {
        this.bindEvents();
    }

    bindEvents() {
        // Select payment method
        $(document).on(
            "click",
            ".payment-method-btn",
            this.selectPaymentMethod.bind(this),
        );

        // Process payment button
        $(document).on(
            "click",
            ".btn-process-payment",
            this.processPayment.bind(this),
        );

        $(document).on(
            "click",
            "#confirmRefund",
            this.confirmRefund.bind(this),
        );

        $(document).on("show.bs.modal", "#refundModal", function (event) {
            const button = $(event.relatedTarget); // Lấy nút đã kích hoạt modal
            const orderId = button.data("order-id");

            $("#refundOrderId").val(orderId);

            $("#refundReason").val("");
            $("#refundNotes").val("");
        });

        $(document).on("hidden.bs.modal", "#refundModal", function () {
            $("#refundOrderId").val("");
            $("#refundReason").val("");
            $("#refundNotes").val("");

            $("#confirmRefund")
                .prop("disabled", false)
                .html('<i class="fas fa-undo"></i> Xác nhận hoàn tiền');
        });
    }

    /**
     * Select payment method
     */
    selectPaymentMethod(e) {
        const $btn = $(e.currentTarget);
        const method = $btn.data("method");

        // Update active state
        $(".payment-method-btn").removeClass("active");
        $btn.addClass("active");

        // Store selected method
        this.selectedPaymentMethod = method;
        $("#selectedPaymentMethod").val(method);
    }

    /**
     * Process payment
     */
    processPayment(e) {
        const $btn = $(e.currentTarget);
        const orderId = $btn.data("order-id");
        const method = this.selectedPaymentMethod || "cash";

        Swal.fire({
            title: "Xác nhận thanh toán?",
            text: "Bạn có chắc muốn thanh toán bằng phương thức này?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Xác nhận",
            cancelButtonText: "Hủy",
            reverseButtons: true,
        }).then((result) => {
            if (!result.isConfirmed) return;

            // Loading UI
            $btn.prop("disabled", true).html(
                '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...',
            );

            Swal.fire({
                title: "Đang xử lý...",
                text: "Vui lòng chờ trong giây lát",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });

            // Call API
            PaymentService.processPayment(orderId, method)
                .done((response) => {
                    if (response.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Thành công!",
                            text: response.message || "Thanh toán thành công!",
                            confirmButtonText: "OK",
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Thất bại!",
                            text: response.message || "Thanh toán thất bại",
                        });

                        $btn.prop("disabled", false).html(
                            '<i class="fas fa-money-bill-wave"></i> Thanh toán',
                        );
                    }
                })
                .fail((xhr) => {
                    const errorMsg =
                        xhr.responseJSON?.message || "Có lỗi xảy ra";

                    Swal.fire({
                        icon: "error",
                        title: "Lỗi!",
                        text: errorMsg,
                    });

                    $btn.prop("disabled", false).html(
                        '<i class="fas fa-money-bill-wave"></i> Thanh toán',
                    );
                });
        });
    }

    /**
     * Confirm refund action
     */
    confirmRefund(e) {
        const $btn = $(e.currentTarget);
        const orderId = $("#refundOrderId").val();
        const reason = $("#refundReason").val();
        const notes = $("#refundNotes").val();

        if (!reason) {
            Swal.fire({
                icon: "warning",
                title: "Thiếu thông tin",
                text: "Vui lòng chọn lý do hoàn tiền",
            });
            return;
        }

        Swal.fire({
            title: "Xác nhận hoàn tiền?",
            text: "Hành động này không thể hoàn tác!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Xác nhận hoàn tiền",
            cancelButtonText: "Hủy",
            reverseButtons: true,
        }).then((result) => {
            if (!result.isConfirmed) return;

            // Disable button + loading UI
            $btn.prop("disabled", true).html(
                '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...',
            );

            Swal.fire({
                title: "Đang xử lý...",
                text: "Vui lòng chờ trong giây lát",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });

            // Call AJAX
            PaymentService.refund(orderId, reason, notes)
                .done((response) => {
                    if (response.success) {
                        // Đóng modal bootstrap
                        const modalEl = document.getElementById("refundModal");
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();

                        Swal.fire({
                            icon: "success",
                            title: "Thành công!",
                            text: response.message || "Hoàn tiền thành công!",
                            confirmButtonText: "OK",
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Thất bại!",
                            text: response.message || "Hoàn tiền thất bại",
                        });

                        $btn.prop("disabled", false).html(
                            '<i class="fas fa-undo"></i> Hoàn tiền',
                        );
                    }
                })
                .fail((xhr) => {
                    const errorMsg =
                        xhr.responseJSON?.message || "Có lỗi xảy ra";

                    Swal.fire({
                        icon: "error",
                        title: "Lỗi!",
                        text: errorMsg,
                    });

                    $btn.prop("disabled", false).html(
                        '<i class="fas fa-undo"></i> Hoàn tiền',
                    );
                });
        });
    }
    /**
     * Show success toast
     */
    showSuccess(message) {
        this.showToast(message, "success");
    }

    /**
     * Show error toast
     */
    showError(message) {
        this.showToast(message, "error");
    }

    /**
     * Display toast notification
     */
    showToast(message, type = "info") {
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
}

// Initialize on document ready
$(document).ready(function () {
    new PaymentDetailModule();
});

export default PaymentDetailModule;
