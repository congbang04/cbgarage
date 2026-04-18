/**
 * Payment Detail Module
 * Handle payment detail page interactions
 * Xử lý tương tác trên trang chi tiết thanh toán
 */

import PaymentService from "../../services/payment.js";

// Store selected payment method
let selectedPaymentMethod = "cash";

/**
 * Initialize payment detail module
 * Entry point for payment detail page
 */
export function initPaymentDetailModule() {
    bindPaymentMethodSelection();
    bindProcessPayment();
    bindConfirmRefund();
    bindRefundModalEvents();
    bindPrintButton();
}

/**
 * Bind payment method selection
 */
function bindPaymentMethodSelection() {
    $(document).on("click", ".payment-method-btn", function (e) {
        e.preventDefault();
        const method = $(this).data("method");

        // Update active state
        $(".payment-method-btn").removeClass("active");
        $(this).addClass("active");

        // Store selected method
        selectedPaymentMethod = method;
        $("#selectedPaymentMethod").val(method);
    });
}

/**
 * Bind process payment button
 */
function bindProcessPayment() {
    $(document).on("click", ".btn-process-payment", function (e) {
        e.preventDefault();
        const $btn = $(this);
        const orderId = $btn.data("order-id");
        const method = selectedPaymentMethod || "cash";

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
    });
}

/**
 * Bind confirm refund button
 */
function bindConfirmRefund() {
    $(document).on("click", "#confirmRefund", function (e) {
        e.preventDefault();
        const $btn = $(this);
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
    });
}

/**
 * Bind refund modal events
 */
function bindRefundModalEvents() {
    $(document).on("show.bs.modal", "#refundModal", function (event) {
        const button = $(event.relatedTarget);
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
 * Handle print payment
 */
export function handlePrintPayment() {
    // Generate print window
    const printWindow = window.open("", "", "height=600,width=800");

    const paymentHTML = generatePrintHTML();

    printWindow.document.write(paymentHTML);
    printWindow.document.close();

    setTimeout(() => {
        printWindow.print();
    }, 250);
}

/**
 * Generate print HTML
 */
function generatePrintHTML() {
    const config = window.PaymentConfig;
    const orderId = config.orderId;

    const branchName = config.branch.name;
    const branchPhone = config.branch.phone;
    const branchAddress = config.branch.address;

    const customerName = config.customer.name;
    const customerPhone = config.customer.phone;
    const customerAddress = config.customer.address;

    const carName = config.car.name;
    const carLicensePlate = config.car.license_plate;

    const orderDetails = config.orderDetails;

    let totalGoods = 0;
    let totalLabor = 0;
    let totalService = 0;
    let itemsHTML = "";

    console.log("chi tiết: ", orderDetails);

    orderDetails.forEach((item, index) => {
        const lineTotal = item.quantity * item.price;

        let itemType = "N/A";
        if (item.item_type === "App\\Models\\Service") {
            itemType = "Dịch vụ";
        } else if (item.item_type === "App\\Models\\Labor") {
            itemType = "Tiền công";
        } else {
            itemType = "Vật tư";
        }
        itemsHTML += `
            <tr>
                <td>${index + 1}</td>
                <td>${item.item.name}</td>
                <td>${itemType}</td>
                <td>${item.quantity}</td>
                <td style="text-align: right;">${item.price.toLocaleString("vi-VN")}</td>
                <td style="text-align: right;">${lineTotal.toLocaleString("vi-VN")}</td>
            </tr>
        `;

        if (item.item_type === "App\\Models\\Labor") {
            totalLabor += lineTotal;
        } else if (item.item_type === "App\\Models\\Service") {
            totalService += lineTotal;
        } else {
            totalGoods += lineTotal;
        }
    });

    const finalTotal = totalGoods + totalLabor + totalService;

    return `
        <!DOCTYPE html>
        <html lang="vi">
        <head>
            <meta charset="UTF-8">
            <title>Báo giá</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 20px; }
                .header h1 { margin: 0; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                .totals { float: right; width: 300px; text-align: right; }
                .totals p { margin: 5px 0; }
                .total-amount { font-weight: bold; font-size: 18px; }
            </style>
        </head>
        <body>
            <div style="display:flex; justify-content:space-between; align-items:flex-start; border-bottom:2px solid #000; padding-bottom:10px; margin-bottom:20px;">

                <!-- LEFT -->
                <div style="width:55%;">
                    <div style="font-size:18px; font-weight:bold; text-transform:uppercase;">
                        CBAUTO GARAGE
                    </div>

                    <div style="font-size:13px; margin-top:5px;">
                        <div>Chi nhánh: <strong>${branchName}</strong></div>
                        <div>Địa chỉ: ${branchAddress}</div>
                        <div>Điện thoại: <strong>${branchPhone}</strong></div>
                    </div>
                </div>

                <!-- RIGHT -->
                <div style="width:45%; text-align:right;">
                    <div style="font-size:20px; font-weight:bold;">
                        HÓA ĐƠN
                    </div>

                    <div style="font-size:13px; margin-top:5px;">
                        <div>Số: <strong>ORD-${orderId}</strong></div>
                        <div>Ngày: ${new Date().toLocaleDateString("vi-VN")}</div>
                    </div>
                </div>

            </div>
            
            <div style="display:flex; justify-content:space-between; align-items:flex-start; border-bottom:2px solid #000; padding-bottom:10px; margin-bottom:20px;">

                <!-- LEFT -->
                <div style="width:45%;">
                    <div style="font-size:16px; font-weight:bold; text-transform:uppercase;">
                        Thông tin khách hàng
                    </div>

                    <div style="font-size:13px; margin-top:5px;">
                        <div>Khách hàng: <strong>${customerName}</strong></div>
                        <div>Điện thoại: <strong>${customerPhone}</strong></div>
                        <div>Địa chỉ: ${customerAddress}</div>
                    </div>
                </div>

                <div style="width:45%; margin-left: 10px">
                    <div style="font-size:16px; font-weight:bold; text-transform:uppercase;">
                        Thông tin xe
                    </div>

                    <div style="font-size:13px; margin-top:5px;">
                        <div>Xe: <strong>${carName}</strong></div>
                        <div>Biển số: <strong>${carLicensePlate}</strong></div>
                    </div>
                </div>

            </div>

            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên hạng mục</th>
                        <th>Loại</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    ${itemsHTML}
                </tbody>
            </table>

            <div class="totals" style="border-top:2px solid #000; padding-top:10px; margin-top:20px;">
                <p>Tổng tiền dịch vụ: <strong>${totalService.toLocaleString("vi-VN")}</strong></p>
                <p>Tổng tiền vật tư: <strong>${totalGoods.toLocaleString("vi-VN")}</strong></p>
                <p>Tổng tiền công: <strong>${totalLabor.toLocaleString("vi-VN")}</strong></p>
                <p class="total-amount">Tổng thanh toán: ${finalTotal.toLocaleString("vi-VN")} ₫</p>
            </div>

            <div style="clear: both; margin-top: 180px; text-align: center; color: #999;">
                <p>Cảm ơn quý khách sử dụng dịch vụ của chúng tôi!</p>
            </div>
        </body>
        </html>
    `;
} /**
 * Bind print quotation button
 */
function bindPrintButton() {
    $(document).on("click", "#btn-print-invoice", function (e) {
        e.preventDefault();
        handlePrintPayment();
    });
}
// Initialize on document ready
$(document).ready(function () {
    initPaymentDetailModule();
});

export default initPaymentDetailModule;
