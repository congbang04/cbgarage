/**
 * Warehouse Part Requests Module
 * Manages part request approvals and rejections
 */

import * as warehouseApi from "../../services/warehouse-api.js";

let realtimeListenerSetup = false; // ✅ Flag để tránh duplicate listener

export function initPartRequestsModule() {
    setupRealTimeListener();
    loadCardPartRequest();
}
function setupRealTimeListener() {
    if (realtimeListenerSetup || typeof window.Echo === "undefined") return;

    realtimeListenerSetup = true;

    window.Echo.channel("part-requests")
        .subscribed(() => {
            console.log("✅ Connected: part-requests");
        })
        .listen(".PartRequestUpdated", () => {
            loadCardPartRequest();
        });
    window.Echo.channel("stock-transfers")
        .subscribed(() => {
            console.log("✅ Connected: stock-transfers");
        })
        .listen(".StockTransferUpdated", () => {
            loadStockTransfers();
        });
}

window.approveRequest = function (partRequestId) {
    // Navigate to goods-issue page to approve with quantity confirmation
    window.location.href = window.routes.warehouseGoodsIssue.replace(
        ":partRequestId",
        partRequestId,
    );
};
window.approveTransferRequest = function (stockTransferId) {
    window.location.href = window.routes.warehouseGoodsIssueTransfer.replace(
        ":stockTransferId",
        stockTransferId,
    );
};

window.rejectRequest = function (partRequestId) {
    Swal.fire({
        title: "Từ chối yêu cầu",
        input: "textarea",
        inputLabel: "Lý do từ chối",
        inputPlaceholder: "Nhập lý do...",
        showCancelButton: true,
        confirmButtonText: "Từ chối",
        cancelButtonText: "Hủy",
        confirmButtonColor: "#dc3545",
    }).then((result) => {
        if (result.isConfirmed) {
            const reason = result.value;

            warehouseApi
                .rejectPartRequest(partRequestId, reason)
                .done(function (response) {
                    Swal.fire("Thành công!", response.message, "success");
                })
                .fail(function (xhr) {
                    Swal.fire(
                        "Lỗi!",
                        xhr.responseJSON?.message || "Có lỗi xảy ra",
                        "error",
                    );
                });
        }
    });
};
window.rejectTransferRequest = function (stockTransferId, type = "reject") {
    const isCancel = type === "cancel";
    Swal.fire({
        title: isCancel ? "Hủy vận chuyển" : "Từ chối yêu cầu",
        input: "textarea",
        inputLabel: isCancel ? "Lý do hủy" : "Lý do từ chối",
        inputPlaceholder: "Nhập lý do...",
        showCancelButton: true,
        confirmButtonText: isCancel ? "Hủy vận chuyển" : "Từ chối",
        cancelButtonText: "Hủy",
        confirmButtonColor: "#dc3545",
    }).then((result) => {
        if (result.isConfirmed) {
            const reason = result.value;

            warehouseApi
                .cancelTransferRequest(stockTransferId, reason)
                .done(function (response) {
                    Swal.fire("Thành công!", response.message, "success");
                })
                .fail(function (xhr) {
                    Swal.fire(
                        "Lỗi!",
                        xhr.responseJSON?.message || "Có lỗi xảy ra",
                        "error",
                    );
                });
        }
    });
};
function loadCardPartRequest(url = window.routes.warehouseCardPartRequests) {
    warehouseApi
        .getPendingRequests(url)
        .done(function (resHtml) {
            $("#requestsContainer").html(resHtml);
        })
        .fail(function () {
            alert("Có lỗi xảy ra khi tải dữ liệu card!");
        });
}
function loadStockTransfers(url = window.routes.warehouseCardTransferRequests) {
    warehouseApi
        .getTransferRequests(url)
        .done(function (resHtml) {
            $("#transferContainer").html(resHtml);
        })
        .fail(function () {
            alert("Có lỗi xảy ra khi tải dữ liệu card!");
        });
}
$(document).on("click", ".pagination a", function (e) {
    e.preventDefault();

    let url = $(this).attr("href");
    let type = $(this).closest(".pagination-container").data("type");

    if (type === "part") {
        loadCardPartRequest(url);
    } else if (type === "transfer") {
        loadStockTransfers(url);
    }
});
// Initialize on document ready
$(document).ready(function () {
    initPartRequestsModule();
});
