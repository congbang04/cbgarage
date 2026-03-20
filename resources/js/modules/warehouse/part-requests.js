/**
 * Warehouse Part Requests Module
 * Manages part request approvals and rejections
 */

import * as warehouseApi from "../../services/warehouse-api.js";

export function initPartRequestsModule() {
    setupRealTimeListener();
}

function setupRealTimeListener() {
    // Listen for part request updates via Echo
    if (typeof window.Echo !== "undefined") {
        window.Echo.channel("part-requests").listen(
            "PartRequestUpdated",
            (event) => {
                updateRequestCard(event.partRequest);
            },
        );
    }
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

function updateRequestCard(partRequest) {
    const $card = $(`[data-request-id="${partRequest.id}"]`);

    if ($card.length) {
        let badgeHtml = "";
        let borderClass = "";

        if (partRequest.status === "pending") {
            badgeHtml = '<span class="bg-warning text-dark">Chờ duyệt</span>';
            borderClass = "border-warning";
        } else if (partRequest.status === "completed") {
            badgeHtml =
                '<span class="bg-success-subtle text-success">Đã xuất</span>';
            borderClass = "border-success";
        } else {
            badgeHtml =
                '<span class="bg-danger-subtle text-danger">Từ chối</span>';
            borderClass = "border-danger";
        }

        // Update status badge
        $card.find(`#status-${partRequest.id}`).html(badgeHtml);

        // Update border color
        $card
            .removeClass("border-warning border-success border-danger")
            .addClass(borderClass);

        // Disable buttons if not pending
        if (partRequest.status !== "pending") {
            $card.find("button").prop("disabled", true);
            $card.find(".card-body").css("opacity", "0.75");
        }
    }
}

// Initialize on document ready
$(document).ready(function () {
    initPartRequestsModule();
});
