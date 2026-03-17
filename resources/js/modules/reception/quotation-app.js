/**
 * Quotation App Entry Point
 * Initializes quotation module on page load
 * Pattern: Page-specific initialization that calls module initializers
 */

import { initQuotationModule } from './quotation.js';

$(document).ready(function () {
    // Initialize all quotation functionality
    initQuotationModule();
});
