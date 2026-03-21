/**
 * Warehouse Part Requests Module
 * Manages part request approvals and rejections
 */

import * as warehouseApi from "../../services/warehouse-api.js";

let realtimeListenerSetup = false; // ✅ Flag để tránh duplicate listener

export function initPartRequestsModule() {
    setupRealTimeListener();
}

function setupRealTimeListener() {
    // ✅ Kiểm tra nếu đã setup rồi thì không setup lại
    if (realtimeListenerSetup || typeof window.Echo === "undefined") {
        return;
    }

    realtimeListenerSetup = true;

    window.Echo.channel("part-requests").listen(".PartRequestUpdated", () => {
        loadCardPartRequest();
    });
}

window.approveRequest = function (partRequestId) {
    // Navigate to goods-issue page to approve with quantity confirmation
    window.location.href = window.routes.warehouseGoodsIssue.replace(
        ":partRequestId",
        partRequestId,
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
                    Swal.fire("Thành công!", response.message, "success").then(
                        () => {
                            updateRequestCard({
                                id: partRequestId,
                                status: "rejected",
                            });
                        },
                    );
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
function loadCardPartRequest() {
    warehouseApi
        .getPendingRequests()
        .done(function (resHtml) {
            $("#requestsContainer").html(resHtml);
        })
        .fail(function () {
            alert("Có lỗi xảy ra khi tải dữ liệu card!");
        });
}
// Initialize on document ready
$(document).ready(function () {
    initPartRequestsModule();
    loadCardPartRequest();
    console.log("gg");
});
