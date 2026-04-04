/**
 * Payment Service - AJAX calls for payment operations
 */

class PaymentService {
    /**
     * Process payment for an order
     */
    static pay(orderId) {
        return $.ajax({
            url: window.routes.paymentPay,
            type: "POST",
            dataType: "json",
            data: {
                order_id: orderId,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
        });
    }

    /**
     * Process payment with custom payment method
     */
    static processPayment(orderId, paymentMethod = "cash") {
        return $.ajax({
            url: window.routes.paymentProcessPayment,
            type: "POST",
            dataType: "json",
            data: {
                order_id: orderId,
                payment_method: paymentMethod,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
        });
    }

    /**
     * Process refund for an order
     */
    static refund(orderId, reason = "", notes = "") {
        return $.ajax({
            url: window.routes.paymentRefund,
            type: "POST",
            dataType: "json",
            data: {
                order_id: orderId,
                reason: reason,
                notes: notes,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
        });
    }

    /**
     * Get order payment details and transaction history
     */
    static getOrderDetails(orderId) {
        return $.ajax({
            url: window.routes.paymentOrderDetails,
            type: "GET",
            dataType: "json",
            data: {
                order_id: orderId,
            },
        });
    }

    /**
     * Get payment transactions table with filters
     */
    static getTransactionsTable(
        filters = {},
        url = window.routes.paymentTable,
    ) {
        // Check if URL already contains query parameters (from pagination)
        const hasQueryParams = url.includes("?");

        // If URL has params, don't pass data (pagination URL is complete)
        // Otherwise, pass filters as data
        const ajaxConfig = {
            url: url,
            type: "GET",
            dataType: "html",
        };

        if (!hasQueryParams && Object.keys(filters).length > 0) {
            ajaxConfig.data = filters;
        }

        return $.ajax(ajaxConfig);
    }

    /**
     * Get list of branches for super admin filter
     */
    static getBranches() {
        return $.ajax({
            url: window.routes.paymentBranches,
            type: "GET",
            dataType: "json",
        });
    }

    /**
     * Export transactions to CSV (optional)
     */
    static exportTransactions(filters = {}) {
        const params = new URLSearchParams({
            ...filters,
            export: "csv",
        });

        window.location.href = `${window.routes.paymentTable}?${params.toString()}`;
    }
}

export default PaymentService;
