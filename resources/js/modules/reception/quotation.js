/**
 * Quotation Main Module
 * Orchestrates quotation functionality and event binding
 * Pattern: Main event dispatcher for quotation feature
 */

import {
    loadServices,
    loadParts,
    loadLabors,
    loadTechnicians,
    handleAddItem,
    handleRemoveItem,
    handleSaveQuotation,
    handlePrintQuotation,
    handleSendWhatsApp,
    selectItem,
    getSelectedItem,
    filterServices,
    filterParts,
    filterLabors,
} from "./quotation-core.js";

/**
 * Initialize quotation module
 * Entry point for quotation page
 */
export function initQuotationModule() {
    // Load data
    loadServices();
    loadParts();
    loadLabors();
    loadTechnicians();

    // Bind events
    bindAddItemButtons();
    bindSearchInputs();
    bindConfirmItemButton();
    bindRemoveItemButtons();
    bindQuantityInputs();
    bindSaveButton();
    bindPrintButton();
    bindWhatsAppButton();
}

/**
 * Bind add item button events
 * Handles both service and part additions
 */
function bindAddItemButtons() {
    $(document).on("click", ".list-group-item[data-type]", function (e) {
        e.preventDefault();

        const itemType = $(this).data("type");
        const itemId = $(this).data("id");
        const itemName = $(this).data("name");
        const price = parseFloat($(this).data("price"));

        selectItem(itemType, itemId, itemName, price);
    });
}

/**
 * Bind remove item button events
 */
function bindRemoveItemButtons() {
    $(document).on("click", ".btn-remove-item", function (e) {
        e.preventDefault();

        const index = $(this).data("index");

        Swal.fire({
            icon: "warning",
            title: "Xác nhận",
            text: "Xóa hạng mục này?",
            showCancelButton: true,
            confirmButtonText: "Xóa",
            cancelButtonText: "Hủy",
        }).then((result) => {
            if (result.isConfirmed) {
                handleRemoveItem(index);
            }
        });
    });
}

/**
 * Bind quantity input changes
 */
function bindQuantityInputs() {
    $(document).on("change", ".item-quantity", function () {
        handleQuantityChange(this);
    });
}

/**
 * Bind save quotation button
 */
function bindSaveButton() {
    const orderId = $('input[name="order_id"]').val();
    const $form = $("#quotation-form");

    $(document).on("click", "#btn-save-quotation", function (e) {
        e.preventDefault();
        handleSaveQuotation(orderId, $form);
    });
}

/**
 * Bind search functionality
 */
function debounce(func, delay = 300) {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), delay);
    };
}

function bindSearchInputs() {
    const debouncedServiceSearch = debounce(function () {
        filterServices($(this).val());
    });

    const debouncedPartSearch = debounce(function () {
        filterParts($(this).val());
    });

    const debouncedLaborSearch = debounce(function () {
        filterLabors($(this).val());
    });

    $(document).on("input", "#search-services", debouncedServiceSearch);
    $(document).on("input", "#search-parts", debouncedPartSearch);
    $(document).on("input", "#search-labors", debouncedLaborSearch);
}

/**
 * Bind confirm item button
 */
function bindConfirmItemButton() {
    $(document).on("click", "#btn-confirm-item", function (e) {
        e.preventDefault();

        const selectedItem = getSelectedItem();
        if (!selectedItem) {
            Swal.fire({
                icon: "warning",
                title: "Cảnh báo",
                text: "Vui lòng chọn một hạng mục!",
            });
            return;
        }

        handleAddItem(
            selectedItem.itemType,
            selectedItem.itemId,
            selectedItem.itemName,
            selectedItem.price,
        );
    });
}

/**
 * Bind print quotation button
 */
function bindPrintButton() {
    $(document).on("click", "#btn-print-quotation", function (e) {
        e.preventDefault();
        handlePrintQuotation();
    });
}

/**
 * Bind send WhatsApp button
 */
function bindWhatsAppButton() {
    $(document).on("click", "#btn-send-message", function (e) {
        e.preventDefault();
        handleSendWhatsApp();
    });
}
