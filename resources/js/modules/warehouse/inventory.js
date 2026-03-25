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
    reloadPendingBadge();
}
function setupInventoryRealtime() {
    // ✅ Kiểm tra nếu đã setup rồi thì không setup lại
    if (realtimeListenerSetup || typeof window.Echo === "undefined") {
        return;
    }

    realtimeListenerSetup = true;

    window.Echo.channel("part-requests")
        .subscribed(() => {
            console.log("✅ Connected: part-request");
        })
        .listen(".PartRequestUpdated", (e) => {
            reloadPendingBadge();
        });
    window.Echo.channel("stock-transfers")
        .subscribed(() => {
            console.log("✅ Connected: stock-transfers");
        })
        .listen(".StockTransferUpdated", (e) => {
            reloadPendingBadge();
        });
}

function reloadPendingBadge() {
    warehouseApi.getPendingCount().done(function (res) {
        const badge = $("#pendingRequestBadge");
        badge.text(res.count);
    });
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
            loadInventories(url);
        },
    );

    $(document).on("click", "#importSubmitBtn", function () {
        submitImportForm();
    });

    $("#receiveTransferModal").on("show.bs.modal", function () {
        loadIncomingTransfers();
    });
}

$(document).on("click", ".btn-confirm-receive", function () {
    const transferId = $(this).data("id");

    Swal.fire({
        title: "Xác nhận nhận hàng?",
        text: "Số lượng sẽ được cộng trực tiếp vào tồn kho chi nhánh của bạn.",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Nhận hàng",
        cancelButtonText: "Hủy",
    }).then((result) => {
        if (result.isConfirmed) {
            // Gọi API
            warehouseApi
                .completeTransfer(transferId)
                .done(function (response) {
                    Swal.fire("Thành công!", response.message, "success");
                    // Load lại danh sách trong Modal
                    loadIncomingTransfers();
                    // Load lại bảng tồn kho chính bên ngoài
                    loadInventories();
                })
                .fail(function (xhr) {
                    Swal.fire(
                        "Lỗi!",
                        xhr.responseJSON?.message || "Có lỗi xảy ra",
                        "error",
                    );
                });
        }
    });
});
// Hàm render danh sách phiếu đang giao vào Modal
function loadIncomingTransfers() {
    $("#incomingTransfersBody").html(
        '<tr><td colspan="4" class="text-center"><i class="fas fa-spinner fa-spin"></i> Đang tải...</td></tr>',
    );

    warehouseApi
        .getIncomingTransfers()
        .done(function (data) {
            if (data.length === 0) {
                $("#incomingTransfersBody").html(
                    '<tr><td colspan="4" class="text-center text-muted">Không có kiện hàng nào đang giao tới.</td></tr>',
                );
                return;
            }

            let html = "";
            data.forEach((transfer) => {
                // Render chi tiết các món hàng trong phiếu
                let detailsHtml = '<ul class="mb-0 ps-3">';
                transfer.details.forEach((d) => {
                    detailsHtml += `<li><small>${d.part.name} (x${d.quantity} ${d.part.unit})</small></li>`;
                });
                detailsHtml += "</ul>";

                html += `
                <tr>
                    <td class="fw-bold text-primary">#TRF-${String(transfer.id).padStart(4, "0")}</td>
                    <td>${transfer.from_branch ? transfer.from_branch.name : "N/A"}</td>
                    <td>${detailsHtml}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-success btn-confirm-receive" data-id="${transfer.id}">
                            <i class="fas fa-check"></i> Xác nhận nhận
                        </button>
                    </td>
                </tr>
            `;
            });
            $("#incomingTransfersBody").html(html);
        })
        .fail(function () {
            $("#incomingTransfersBody").html(
                '<tr><td colspan="4" class="text-center text-danger">Lỗi tải dữ liệu</td></tr>',
            );
        });
}

function loadInventories(url) {
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
