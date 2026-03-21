/**
 * Warehouse Inventory Module
 * Manages inventory search, filtering, and list updates with pagination
 */

import * as warehouseApi from "../../services/warehouse-api.js";

let searchTimeout;
let realtimeListenerSetup = false; // ✅ Flag để tránh duplicate listener

export function initInventoryModule() {
    setupEventListeners();
    loadInventories();
    setupInventoryRealtime();
}
function setupInventoryRealtime() {
    // ✅ Kiểm tra nếu đã setup rồi thì không setup lại
    if (realtimeListenerSetup || typeof window.Echo === "undefined") {
        return;
    }

    realtimeListenerSetup = true;

    window.Echo.channel("part-requests").listen(".PartRequestUpdated", (e) => {
        console.log("🔔 inventory nhận event", e.partRequest);
        updatePendingBadge(e.partRequest);
    });
}

function updatePendingBadge(partRequest) {
    const badge = document.getElementById("pendingRequestBadge");
    if (!badge) return;

    let count = parseInt(badge.innerText) || 0;

    const status = partRequest.status.toLowerCase();

    // 👉 logic quan trọng
    if (status === "pending") {
        count++;
    } else {
        count = Math.max(0, count - 1);
    }

    badge.innerText = count;

    // ẩn badge nếu = 0
    if (count === 0) {
        badge.style.display = "none";
    } else {
        badge.style.display = "inline-block";
    }
}

function setupEventListeners() {
    // Search input - debounce for better performance
    $(document).on("keyup", "#searchInput", function () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            loadInventories();
        }, 500);
    });

    // Status filter
    $(document).on("change", "#statusFilter", function () {
        loadInventories();
    });

    // Price range filters
    $(document).on("change", "#priceFromFilter, #priceToFilter", function () {
        loadInventories();
    });

    // Pagination click handler - prevent default and reload with new page
    $(document).on(
        "click",
        "#inventoryTableContainer .pagination a",
        function (e) {
            e.preventDefault();
            const url = $(this).attr("href");
            loadInventoriesFromUrl(url);
        },
    );

    // Import form submit
    $(document).on("click", "#importSubmitBtn", function () {
        submitImportForm();
    });
}

function loadInventories() {
    const filters = {
        keyword: $("#searchInput").val(),
        status: $("#statusFilter").val(),
        price_from: $("#priceFromFilter").val(),
        price_to: $("#priceToFilter").val(),
    };

    warehouseApi
        .getInventories(window.routes.warehouseInventoryList, filters)
        .done(function (html) {
            $("#inventoryTableContainer").html(html);
        })
        .fail(function (xhr) {
            Swal.fire("Lỗi!", "Không thể tải dữ liệu", "error");
        });
}

function loadInventoriesFromUrl(url) {
    const filters = {
        keyword: $("#searchInput").val(),
        status: $("#statusFilter").val(),
        price_from: $("#priceFromFilter").val(),
        price_to: $("#priceToFilter").val(),
    };

    warehouseApi
        .getInventories(url, filters)
        .done(function (html) {
            $("#inventoryTableContainer").html(html);
        })
        .fail(function (xhr) {
            Swal.fire("Lỗi!", "Không thể tải dữ liệu", "error");
        });
}

function submitImportForm() {
    const form = document.getElementById("importForm");
    const formData = new FormData(form);

    $.ajax({
        url: window.routes.warehouseInventoryList,
        method: "POST",
        data: Object.fromEntries(formData),
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
        },
        success: function (response) {
            Swal.fire(
                "Thành công!",
                "Nhập hàng vào kho thành công",
                "success",
            ).then(() => {
                loadInventories();
                bootstrap.Modal.instance(
                    document.getElementById("importModal"),
                ).hide();
                form.reset();
            });
        },
        error: function (xhr) {
            Swal.fire(
                "Lỗi!",
                xhr.responseJSON?.message || "Có lỗi xảy ra",
                "error",
            );
        },
    });
}

window.editInventory = function (inventoryId) {
    console.log("Edit inventory:", inventoryId);
};

// Initialize on document ready
$(document).ready(function () {
    initInventoryModule();
});
