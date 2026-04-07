/**
 * Customer Booking API Module
 * Handles AJAX requests for customer booking functionality
 */

/**
 * Fetch booking table (HTML)
 * @param {string|undefined} url - The URL to fetch from (uses window.routes if not provided)
 * @param {Object} data - Query parameters for filtering
 * @returns {jQuery.jqXHR}
 */
export function tableList(
    url = window.routes?.customerBookingTable,
    data = {},
) {
    return $.ajax({
        url: url || "/customer-bookings/table",
        method: "GET",
        data: data,
        dataType: "html",
    });
}

/**
 * Cancel a booking
 * @param {number} id - Booking ID
 * @returns {jQuery.jqXHR}
 */
export function cancelBooking(id) {
    const url =
        window.routes?.customerBookingCancel?.replace(":id", id) ||
        `/customer-bookings/${id}/cancel`;
    return $.ajax({
        url: url,
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
}
