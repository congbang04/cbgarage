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

export function getPendingRequests() {
    return $.ajax({
        url: window.routes.warehousePartRequests,
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
            _token: document.querySelector('meta[name="csrf-token"]').content,
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
