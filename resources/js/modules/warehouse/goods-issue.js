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
    // Xác nhận xuất kho (Dùng chung cho cả KTV và Chi nhánh)
    $(document).on("click", "#confirmIssueBtn", function (e) {
        e.preventDefault();

        // Lấy ID từ nút bấm (Tùy xem đang ở màn hình nào)
        const partRequestId = $(this).data("part-request-id");
        const transferRequestId = $(this).data("transfer-request-id");

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
            text: "Hàng sẽ được trừ khỏi kho và bắt đầu vận chuyển.",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Xác nhận",
            cancelButtonText: "Hủy",
        }).then((result) => {
            if (result.isConfirmed) {
                // Phân luồng gọi API
                if (partRequestId) {
                    submitIssue(partRequestId, actualQties, "part");
                } else if (transferRequestId) {
                    submitIssue(transferRequestId, actualQties, "transfer");
                }
            }
        });
    });
}
// Thêm tham số type để biết đang xử lý loại phiếu nào
function submitIssue(id, actualQties, type) {
    // Xác định URL tùy theo loại phiếu
    const url =
        type === "part"
            ? window.routes.warehouseApprovePartRequest.replace(
                  ":partRequestId",
                  id,
              )
            : window.routes.warehouseApproveTransferRequest.replace(
                  ":stockTransferId",
                  id,
              );

    $.ajax({
        url: url,
        method: "POST",
        data: {
            actual_quantities: actualQties,
        },
        success: function (response) {
            Swal.fire("Thành công!", response.message, "success").then(() => {
                // Quay về trang danh sách sau khi xuất thành công
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
