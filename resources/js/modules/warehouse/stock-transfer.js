/**
 * Warehouse Stock Transfer Module
 * Manages stock transfer form and part selection
 */

import * as warehouseApi from "../../services/warehouse-api.js";

let parts = [];
let rowCounter = 1;
let searchTimeout;

export function initStockTransferModule() {
    loadParts();
    loadHistory();
    loadTransfers();
    setupEventListeners();
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

    // Search input - debounce for better performance
    $(document).on("keyup", "#searchInput", function () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            loadTransfers();
        }, 500);
    });

    // Status filter
    $(document).on("change", "#statusFilter", function () {
        loadTransfers();
    });

    // Date range filters
    $(document).on("change", "#dateFromFilter, #dateToFilter", function () {
        loadTransfers();
    });
    // Pagination click handler - prevent default and reload with new page
    $(document).on("click", "#table-data .pagination a", function (e) {
        e.preventDefault();
        const url = $(this).attr("href");
        loadTransfers(url);
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
                <input type="number" class="form-control" name="items[${rowCounter - 1}][quantity]" min="1" value="1">
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

function loadHistory(url) {
    warehouseApi
        .transferHistory(url)
        .done(function (res) {
            $("#transfer-history").html(res);
        })
        .fail(function (xhr) {
            console.log(xhr.responseText);
        });
}
function loadTransfers(url) {
    const filters = {
        keyword: $("#searchInput").val(),
        status: $("#statusFilter").val(),
        date_from: $("#dateFromFilter").val(),
        date_to: $("#dateToFilter").val(),
    };
    warehouseApi
        .searchFullTransferHistoryTable(url, filters)
        .done(function (res) {
            $("#table-data").html(res);
        })
        .fail(function (xhr) {
            console.log(xhr.responseText);
        });
}
window.transferDetail = function (stockTransferId) {
    window.location.href = window.routes.warehouseGoodsIssueTransfer.replace(
        ":stockTransferId",
        stockTransferId,
    );
};
// Initialize on document ready
$(document).ready(function () {
    initStockTransferModule();
});
