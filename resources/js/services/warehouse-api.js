/**
 * Warehouse API Service
 * Handles all HTTP calls for warehouse management
 */

export function getInventories(
    url = window.routes.warehouseInventoryList,
    data = {},
) {
    return $.ajax({
        url: url,
        method: "GET",
        data: data,
        dataType: "html",
    });
}

export function getPendingRequests(
    url = window.routes.warehouseCardPartRequests,
) {
    return $.ajax({
        url: url,
        method: "GET",
    });
}

export function getTransferRequests(
    url = window.routes.warehouseCardTransferRequests,
) {
    return $.ajax({
        url: url,
        method: "GET",
    });
}
export function getPendingCount() {
    return $.ajax({
        url: window.routes.warehouseGetPendingCount,
        method: "GET",
    });
}

export function approvePartRequest(partRequestId, actualQuantities) {
    const url = window.routes.warehouseApprovePartRequest.replace(
        ":partRequestId",
        partRequestId,
    );
    return $.ajax({
        url: url,
        method: "POST",
        data: JSON.stringify({
            actual_quantities: actualQuantities,
            _token: document.querySelector('meta[name="csrf-token"]').content,
        }),
        contentType: "application/json",
        dataType: "json",
    });
}

export function rejectPartRequest(partRequestId, reason = "") {
    const url = window.routes.warehouseRejectPartRequest.replace(
        ":partRequestId",
        partRequestId,
    );
    return $.ajax({
        url: url,
        method: "POST",
        data: {
            reason,
        },
    });
}
export function cancelTransferRequest(stockTransferId, reason = "") {
    const url = window.routes.warehouseCancelTransferRequest.replace(
        ":stockTransferId",
        stockTransferId,
    );
    return $.ajax({
        url: url,
        method: "POST",
        data: {
            reason,
        },
    });
}

export function createStockTransfer(data) {
    return $.ajax({
        url: window.routes.warehouseCreateStockTransfer,
        method: "POST",
        data: JSON.stringify({
            ...data,
            _token: document.querySelector('meta[name="csrf-token"]').content,
        }),
        contentType: "application/json",
        dataType: "json",
    });
}

export function getSampleParts() {
    return $.ajax({
        url: window.routes.warehouseGetParts,
        method: "GET",
        dataType: "json",
    });
}
