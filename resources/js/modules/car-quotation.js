import { getCarQuotationItems } from "../services/car-api";
import { recalculateFromTable } from "./reception/quotation-core.js";

$(document).ready(function () {
    loadExistingQuotation();
});

function loadExistingQuotation() {
    const urlParams = new URLSearchParams(window.location.search);
    const orderId = urlParams.get("orderId");

    if (!orderId) return;
    console.log("orderId", orderId);

    getCarQuotationItems(orderId)
        .done(function (html) {
            if (html && html.trim()) {
                $("#quotation-items").html(html);
                recalculateFromTable();
            }
        })
        .fail(function (xhr) {
            console.error("Failed to load quotation items", xhr);
        });
}
