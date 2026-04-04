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
        return $.ajax({
            url: url,
            type: "GET",
            dataType: "html",
            data: filters, // luôn gửi
        });
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
