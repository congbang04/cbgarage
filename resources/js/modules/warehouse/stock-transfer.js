/**
 * Warehouse Stock Transfer Module
 * Manages stock transfer form and part selection
 */

import * as warehouseApi from "../../services/warehouse-api.js";

let parts = [];
let rowCounter = 1;

export function initStockTransferModule() {
    loadParts();
    setupEventListeners();
    setupRealTimeListener();
}

function loadParts() {
    warehouseApi
        .getSampleParts()
        .done(function (data) {
            parts = data;
            populatePartSelects();
        })
        .fail(function (xhr) {
            console.error("Failed to load parts", xhr);
        });
}

function populatePartSelects() {
    const partSelectHtml = parts
        .map(
            (part) =>
                `<option value="${part.id}">${part.sku} - ${part.name}</option>`,
        )
        .join("");

    // Update existing selects and future ones
    $(document).on("click", "#addRowBtn", function () {
        addTableRow();
    });

    // Initial population
    $(".part-select").each(function () {
        $(this).html(
            `<option value="">-- Chọn phụ tùng --</option>${partSelectHtml}`,
        );
    });
}

function setupEventListeners() {
    // Form submission
    $("#transferForm").on("submit", function (e) {
        e.preventDefault();
        submitTransfer();
    });

    // Remove row button
    $(document).on("click", ".remove-row", function () {
        $(this).closest("tr").remove();
    });

    // Add row button
    $("#addRowBtn").on("click", function () {
        addTableRow();
    });
}

function addTableRow() {
    rowCounter++;

    const newRow = `
        <tr class="transfer-item">
            <td>
                <select class="form-select border-0 part-select" name="items[${rowCounter - 1}][part_id]" required>
                    <option value="">-- Chọn phụ tùng --</option>
                    ${parts.map((p) => `<option value="${p.id}">${p.sku} - ${p.name}</option>`).join("")}
                </select>
            </td>
            <td>
                <input type="number" class="form-control" name="items[${rowCounter - 1}][quantity]" min="1" required>
            </td>
            <td>
                <button type="button" class="btn btn-sm text-danger remove-row">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `;

    $("#transferItems").find("tr").last().before(newRow);
}

function submitTransfer() {
    const formData = new FormData(document.getElementById("transferForm"));

    // Build request data
    const data = {
        from_branch_id: formData.get("from_branch_id"),
        to_branch_id: formData.get("to_branch_id"),
        note: formData.get("note"),
        items: [],
    };

    // Collect items
    document.querySelectorAll(".transfer-item").forEach((row) => {
        const partId = row.querySelector(".part-select").value;
        const quantity = row.querySelector('input[type="number"]').value;

        if (partId && quantity > 0) {
            data.items.push({
                part_id: parseInt(partId),
                quantity: parseInt(quantity),
            });
        }
    });

    if (data.items.length === 0) {
        Swal.fire("Cảnh báo!", "Vui lòng thêm ít nhất một mục", "warning");
        return;
    }

    warehouseApi
        .createStockTransfer(data)
        .done(function (response) {
            Swal.fire("Thành công!", response.message, "success").then(() => {
                location.reload();
            });
        })
        .fail(function (xhr) {
            Swal.fire(
                "Lỗi!",
                xhr.responseJSON?.message || "Có lỗi xảy ra",
                "error",
            );
        });
}

function setupRealTimeListener() {
    if (typeof window.Echo !== "undefined") {
        window.Echo.channel("stock-transfers").listen(
            "StockTransferUpdated",
            (event) => {
                console.log("Stock transfer updated:", event.transfer);
                // Reload transfer history
                loadTransferHistory();
            },
        );
    }
}

function loadTransferHistory() {
    // Reload transfers via API if needed
    location.reload();
}

// Initialize on document ready
$(document).ready(function () {
    initStockTransferModule();
});
