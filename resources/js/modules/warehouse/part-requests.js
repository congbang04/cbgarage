/**
 * Warehouse Part Requests Module
 * Manages part request approvals and rejections
 */

import * as warehouseApi from "../../services/warehouse-api.js";

let realtimeListenerSetup = false; // ✅ Flag để tránh duplicate listener
let isTransferLoaded = false; // ✅ Thêm biến cờ cho Lazy Load
export function initPartRequestsModule() {
    setupRealTimeListener();
    loadCardPartRequest();
    setupTabLazyLoad();
    setupSearchForm();
    setupTransferSearchForm();
}

function setupTabLazyLoad() {
    const branchTabEl = document.querySelector("#branch-tab");

    if (branchTabEl) {
        branchTabEl.addEventListener("shown.bs.tab", function (event) {
            if (!isTransferLoaded) {
                loadStockTransfers();
                isTransferLoaded = true;
            }
        });
    }
}
function setupSearchForm() {
    $("#partRequestSearchForm").on("submit", function (e) {
        e.preventDefault();

        const filters = {
            status: $("#statusFilter").val(),
            date_from: $("#dateFromFilter").val(),
            date_to: $("#dateToFilter").val(),
        };

        loadCardPartRequest(window.routes.warehouseSearchPartRequests, filters);
    });

    // Handle reset button
    $("#partRequestSearchForm").on("reset", function () {
        setTimeout(() => {
            loadCardPartRequest();
        }, 10);
    });
}

function setupTransferSearchForm() {
    $("#transferSearchForm").on("submit", function (e) {
        e.preventDefault();

        const filters = {
            status: $("#transferStatusFilter").val(),
            date_from: $("#transferDateFromFilter").val(),
            date_to: $("#transferDateToFilter").val(),
        };

        loadStockTransfers(
            window.routes.warehouseCardTransferRequests,
            filters,
        );
    });

    // Handle reset button
    $("#transferSearchForm").on("reset", function () {
        setTimeout(() => {
            loadStockTransfers();
        }, 10);
    });
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
function loadCardPartRequest(
    url = window.routes.warehouseCardPartRequests,
    filters = {},
) {
    const queryParams = new URLSearchParams(filters).toString();
    const fullUrl = queryParams ? `${url}?${queryParams}` : url;

    warehouseApi
        .searchPartRequests(url, filters)
        .done(function (resHtml) {
            $("#requestsContainer").html(resHtml);
        })
        .fail(function () {
            alert("Có lỗi xảy ra khi tải dữ liệu card!");
        });
}
function loadStockTransfers(
    url = window.routes.warehouseCardTransferRequests,
    filters = {},
) {
    const queryParams = new URLSearchParams(filters).toString();
    const fullUrl = queryParams ? `${url}?${queryParams}` : url;

    warehouseApi
        .searchTransferRequests(url, filters)
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
