/**
 * Technician Jobs API Service Layer
 * All HTTP calls for technician job operations
 */

export function getJobsList(filters = {}) {
    return $.ajax({
        url: window.routes.technicianJobsTable,
        type: "GET",
        data: filters,
        dataType: "html",
    });
}

export function updateJobStatus(orderId, data) {
    return $.ajax({
        url: window.routes.technicianJobsUpdateStatus.replace(
            ":orderId",
            orderId,
        ),
        type: "PUT",
        contentType: "application/json",
        data: JSON.stringify(data),
        dataType: "json",
    });
}

export function completeWorkItem(orderId, detailId) {
    return $.ajax({
        url: window.routes.technicianJobsCompleteItem.replace(
            ":orderId",
            orderId,
        ),
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify({ detail_id: detailId }),
        dataType: "json",
    });
}

export function requestPart(orderId, data) {
    return $.ajax({
        url: window.routes.technicianJobsRequestPart.replace(
            ":orderId",
            orderId,
        ),
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify(data),
        dataType: "json",
    });
}

export function requestParts(orderId, data) {
    return $.ajax({
        url: window.routes.technicianJobsRequestPart.replace(
            ":orderId",
            orderId,
        ),
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify(data),
        dataType: "json",
    });
}

export function getAllParts() {
    return $.ajax({
        url: window.routes.partsJson,
        type: "GET",
        dataType: "json",
    });
}
