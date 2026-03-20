/**
 * Warehouse Goods Issue Module
 * Manages goods issue approval and quantity confirmation
 */

$(document).ready(function () {
    initGoodsIssueModule();
});

function initGoodsIssueModule() {
    setupEventListeners();
}

function setupEventListeners() {
    // Xác nhận xuất kho
    $(document).on("click", "#confirmIssueBtn", function (e) {
        e.preventDefault();
        const partRequestId = $(this).data("part-request-id");
        const actualQties = {};

        document.querySelectorAll(".actual-qty").forEach((input) => {
            actualQties[input.dataset.detailId] = parseInt(input.value) || 0;
        });

        // Validate tất cả input
        if (Object.values(actualQties).some((qty) => qty === 0)) {
            Swal.fire(
                "Cảnh báo!",
                "Vui lòng nhập đủ số lượng xuất cho tất cả mục",
                "warning",
            );
            return;
        }

        Swal.fire({
            title: "Xác nhận xuất kho?",
            text: "Bạn chắc chắn muốn xác nhận xuất kho?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Xác nhận",
            cancelButtonText: "Hủy",
        }).then((result) => {
            if (result.isConfirmed) {
                submitIssue(partRequestId, actualQties);
            }
        });
    });
}

function submitIssue(partRequestId, actualQties) {
    $.ajax({
        url: window.routes.warehouseApprovePartRequest.replace(
            ":partRequestId",
            partRequestId,
        ),
        method: "POST",
        data: {
            part_request_id: partRequestId,
            actual_quantities: actualQties,
        },
        success: function (response) {
            Swal.fire("Thành công!", response.message, "success").then(() => {
                window.location.href = window.routes.warehousePartRequests;
            });
        },
        error: function (xhr) {
            let errorMsg = "Có lỗi xảy ra";
            if (xhr.responseJSON?.message) {
                errorMsg = xhr.responseJSON.message;
            } else if (xhr.responseJSON?.errors) {
                errorMsg = Object.values(xhr.responseJSON.errors)
                    .flat()
                    .join(", ");
            }
            Swal.fire("Lỗi!", errorMsg, "error");
        },
    });
}
