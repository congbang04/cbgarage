/**
 * Reception Quotation API Service
 * Handles all HTTP calls for quotation functionality
 * Pattern: Service layer pure API wrapper using jQuery $.ajax
 */

/**
 * Get all available services
 * GET /reception/services
 */
export function getServices() {
    return $.ajax({
        url: '/reception/services',
        type: 'GET',
        dataType: 'json'
    });
}

/**
 * Get all available parts
 * GET /reception/parts
 */
export function getParts() {
    return $.ajax({
        url: '/reception/parts',
        type: 'GET',
        dataType: 'json'
    });
}

/**
 * Get all technicians for assignment
 * GET /reception/technicians
 */
export function getTechnicians() {
    return $.ajax({
        url: '/reception/technicians',
        type: 'GET',
        dataType: 'json'
    });
}

/**
 * Save quotation with order details
 * POST /reception/quotation/{orderId}
 */
export function saveQuotation(orderId, details, technicianId = null) {
    return $.ajax({
        url: '/reception/quotation/' + orderId,
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            details: details,
            technician_id: technicianId,
            _token: $('meta[name="csrf-token"]').attr('content')
        }),
        dataType: 'json'
    });
}

/**
 * Get quotation details
 * GET /reception/quotation/{orderId}
 */
export function getQuotation(orderId) {
    return $.ajax({
        url: '/reception/quotation/' + orderId,
        type: 'GET',
        dataType: 'json'
    });
}
