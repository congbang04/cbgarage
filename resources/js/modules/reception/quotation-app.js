/**
 * Quotation App Entry Point
 * Initializes quotation module on page load
 * Pattern: Page-specific initialization that calls module initializers
 */

import { initQuotationModule } from "./quotation.js";
import { loadExistingQuotation } from "./quotation-core.js";

$(document).ready(function () {
    // Get orderId from hidden input
    const orderId = $('input[name="order_id"]').val();

    // Initialize all quotation functionality
    initQuotationModule();

    // Load existing quotation items if orderId exists
    if (orderId) {
        loadExistingQuotation(orderId);
    }
    $(".btn-pay").on("click", function (e) {
        e.preventDefault();
        const orderId = $(this).data("order-id");

        window.location.href = window.routes.paymentDetail.replace(
            ":id",
            orderId,
        );
    });
});
