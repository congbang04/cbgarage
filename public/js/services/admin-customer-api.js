/**
 * Admin Customer API Module
 * Handles AJAX requests for customer management
 */

/**
 * Fetch customer list table (HTML)
 * @param {string|undefined} url - The URL to fetch from
 * @param {Object} data - Query parameters for filtering
 * @returns {jQuery.jqXHR}
 */
export function tableList(url = window.routes?.adminCustomersList, data = {}) {
    return $.ajax({
        url: url || "/admin/customers/list",
        method: "GET",
        data: data,
        dataType: "html",
    });
}

/**
 * Create a new customer
 * @param {Object} data - Customer data
 * @returns {jQuery.jqXHR}
 */
export function createCustomer(data) {
    return $.ajax({
        url: window.routes?.adminCustomersStore || "/admin/customers",
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        dataType: "json",
        data: data,
    });
}

/**
 * Update an existing customer
 * @param {number} customerId - Customer ID
 * @param {Object} data - Customer data
 * @returns {jQuery.jqXHR}
 */
export function updateCustomer(customerId, data) {
    const url =
        window.routes?.adminCustomersUpdate?.replace(":customer", customerId) ||
        `/admin/customers/${customerId}`;

    return $.ajax({
        url: url,
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        dataType: "json",
        data: {
            ...data,
            _method: "PUT",
        },
    });
}

/**
 * Delete a customer (soft delete)
 * @param {number} customerId - Customer ID
 * @returns {jQuery.jqXHR}
 */
export function deleteCustomer(customerId) {
    const url =
        window.routes?.adminCustomersDestroy?.replace(
            ":customer",
            customerId,
        ) || `/admin/customers/${customerId}`;

    return $.ajax({
        url: url,
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        dataType: "json",
    });
}

/**
 * Toggle customer status
 * @param {number} customerId - Customer ID
 * @returns {jQuery.jqXHR}
 */
export function toggleStatus(customerId) {
    const url =
        window.routes?.adminCustomersToggleStatus?.replace(
            ":customer",
            customerId,
        ) || `/admin/customers/${customerId}/toggle-status`;

    return $.ajax({
        url: url,
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        dataType: "json",
    });
}
