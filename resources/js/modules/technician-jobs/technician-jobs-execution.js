/**
 * Technician Jobs - Execution Page Module
 * Handles job status updates, work item completion, and part requests
 */

import * as TechnicianJobsAPI from "../../services/technician-jobs-api.js";
import { handleAjaxError } from "../../helpers/validation.js";

const orderId = window.location.pathname.split("/").pop();
let partsData = [];

export function initJobExecution() {
    bindWorkChecklistEvents();
    bindStatusUpdateEvents();
    bindRequestPartEvents();
    // Parts are loaded on modal open
}

/**
 * Work Checklist - mark items complete
 */
function bindWorkChecklistEvents() {
    $(document).on("change", ".work-item-checkbox", function () {
        const detailId = $(this).data("id");
        const isChecked = $(this).is(":checked");

        if (isChecked) {
            // Mark as completed
            const $item = $(this).closest(".list-group-item");
            const $badge = $item.find(".badge");

            $badge
                .removeClass("bg-secondary")
                .addClass("bg-success")
                .text("Hoàn thành");

            // Optionally call API to record completion
            TechnicianJobsAPI.completeWorkItem(orderId, detailId)
                .done(function (response) {
                    if (!response.success) {
                        Swal.fire(
                            "Lỗi",
                            response.message || "Không thể đánh dấu hoàn thành",
                            "error",
                        );
                    }
                })
                .fail(function (xhr) {
                    handleAjaxError(xhr, "Lỗi khi đánh dấu hoàn thành");
                });
        }
    });
}

/**
 * Status Update - update order status and notes
 */
function bindStatusUpdateEvents() {
    $("#updateStatusBtn").on("click", function (e) {
        e.preventDefault();

        const status = $('input[name="status"]:checked').val();
        const note = $("#technicalNotes").val();

        if (!status) {
            Swal.fire("Thông báo", "Vui lòng chọn trạng thái", "warning");
            return;
        }

        TechnicianJobsAPI.updateJobStatus(orderId, { status, note })
            .done(function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Thành công",
                        text: response.message,
                        timer: 2000,
                    });

                    // Update UI
                    updateStatusBadge(status);
                } else {
                    Swal.fire(
                        "Lỗi",
                        response.message || "Cập nhật thất bại",
                        "error",
                    );
                }
            })
            .fail(function (xhr) {
                handleAjaxError(xhr, "Lỗi cập nhật trạng thái");
            });
    });
}

/**
 * Request Part - modal and form handling with multiple parts support
 */
function bindRequestPartEvents() {
    let selectedParts = []; // Store selected parts
    let allParts = []; // Cache all parts for search

    // Open modal
    $("#requestPartBtn").on("click", function () {
        const modal = new bootstrap.Modal(
            document.getElementById("requestPartModal"),
        );
        modal.show();
    });

    // Load parts on modal open
    $("#requestPartModal").on("show.bs.modal", function () {
        if (allParts.length === 0) {
            loadPartsForModal();
        }
    });

    // Search parts functionality
    $("#partSearch").on("keyup", function () {
        const searchTerm = $(this).val().toLowerCase();
        const $select = $("#partSelect");

        $select.find("option:not(:first)").remove();

        allParts.forEach((part) => {
            if (
                part.name.toLowerCase().includes(searchTerm) ||
                part.sku.toLowerCase().includes(searchTerm)
            ) {
                $select.append(`
                    <option value="${part.id}" data-price="${part.unit_price}">
                        ${part.name} (SKU: ${part.sku}) - ${new Intl.NumberFormat(
                            "vi-VN",
                            { style: "currency", currency: "VND" },
                        ).format(part.unit_price)}
                    </option>
                `);
            }
        });
    });

    // Add part to table
    $("#addPartBtn").on("click", function (e) {
        e.preventDefault();

        const partId = parseInt($("#partSelect").val());
        const quantity = parseInt($("#partQuantity").val());

        if (!partId || !quantity || quantity < 1) {
            Swal.fire(
                "Thông báo",
                "Vui lòng chọn vật tư và nhập số lượng",
                "warning",
            );
            return;
        }

        // Find part data
        const part = allParts.find((p) => p.id === partId);
        if (!part) {
            Swal.fire("Lỗi", "Vật tư không tìm thấy", "error");
            return;
        }

        // Check if already added
        if (selectedParts.some((p) => p.id === partId)) {
            Swal.fire("Thông báo", "Vật tư này đã được thêm rồi", "warning");
            return;
        }

        // Add to selected parts
        selectedParts.push({
            id: part.id,
            name: part.name,
            sku: part.sku,
            unit_price: part.unit_price,
            quantity: quantity,
        });

        // Update UI
        renderSelectedPartsTable(selectedParts);
        resetPartForm();
        focusPartSearch();
    });

    // Delete from table
    $(document).on("click", ".delete-part-btn", function () {
        const partId = parseInt($(this).data("part-id"));
        selectedParts = selectedParts.filter((p) => p.id !== partId);
        renderSelectedPartsTable(selectedParts);
    });

    // Submit form - send all parts
    $("#requestPartForm").on("submit", function (e) {
        e.preventDefault();

        if (selectedParts.length === 0) {
            Swal.fire("Thông báo", "Vui lòng chọn ít nhất 1 vật tư", "warning");
            return;
        }

        // Prepare data - send array of parts
        const requestData = {
            parts: selectedParts.map((p) => ({
                part_id: p.id,
                quantity: p.quantity,
            })),
        };

        TechnicianJobsAPI.requestParts(orderId, requestData)
            .done(function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Thành công",
                        text: response.message,
                        timer: 2000,
                    });

                    // Reset and close
                    selectedParts = [];
                    renderSelectedPartsTable([]);
                    $("#requestPartForm")[0].reset();
                    bootstrap.Modal.getInstance(
                        document.getElementById("requestPartModal"),
                    ).hide();

                    // Reload the page to show new parts
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    Swal.fire(
                        "Lỗi",
                        response.message || "Yêu cầu thất bại",
                        "error",
                    );
                }
            })
            .fail(function (xhr) {
                handleAjaxError(xhr, "Lỗi gửi yêu cầu vật tư");
            });
    });

    // Reset modal on close
    $("#requestPartModal").on("hidden.bs.modal", function () {
        selectedParts = [];
        renderSelectedPartsTable([]);
        resetPartForm();
    });

    /**
     * Helper: Load all parts
     */
    function loadPartsForModal() {
        TechnicianJobsAPI.getAllParts()
            .done(function (parts) {
                if (Array.isArray(parts)) {
                    allParts = parts;
                    populatePartsDropdown(parts);
                }
            })
            .fail(function (xhr) {
                console.error("Failed to load parts", xhr);
            });
    }

    /**
     * Helper: Populate dropdown
     */
    function populatePartsDropdown(parts) {
        const $select = $("#partSelect");
        $select.find("option:not(:first)").remove();

        parts.forEach((part) => {
            $select.append(`
                <option value="${part.id}" data-price="${part.unit_price}">
                    ${part.name} (SKU: ${part.sku}) - ${new Intl.NumberFormat(
                        "vi-VN",
                        { style: "currency", currency: "VND" },
                    ).format(part.unit_price)}
                </option>
            `);
        });
    }

    /**
     * Helper: Render selected parts table
     */
    function renderSelectedPartsTable(parts) {
        const $tbody = $("#selectedPartsTable");
        $tbody.empty();

        if (parts.length === 0) {
            $tbody.html(`
                <tr id="emptyRow">
                    <td colspan="6" class="text-center text-muted py-3">
                        Chưa chọn vật tư nào
                    </td>
                </tr>
            `);
            $("#totalAmount").text("0 đ");
            return;
        }

        let total = 0;
        parts.forEach((part) => {
            const subtotal = part.unit_price * part.quantity;
            total += subtotal;

            const row = `
                <tr>
                    <td><strong>${part.name}</strong></td>
                    <td><code>${part.sku}</code></td>
                    <td>${part.quantity}</td>
                    <td>${new Intl.NumberFormat("vi-VN", {
                        style: "currency",
                        currency: "VND",
                    }).format(part.unit_price)}</td>
                    <td>${new Intl.NumberFormat("vi-VN", {
                        style: "currency",
                        currency: "VND",
                    }).format(subtotal)}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger delete-part-btn" 
                            data-part-id="${part.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            $tbody.append(row);
        });

        $("#totalAmount").text(
            new Intl.NumberFormat("vi-VN", {
                style: "currency",
                currency: "VND",
            }).format(total),
        );
    }

    /**
     * Helper: Reset form
     */
    function resetPartForm() {
        $("#partSelect").val("");
        $("#partQuantity").val(1);
        $("#partSearch").val("");
    }

    /**
     * Helper: Focus search
     */
    function focusPartSearch() {
        $("#partSearch").focus();
    }
}

/**
 * Update status badge in header
 */
function updateStatusBadge(status) {
    const badgeClass = {
        IN_PROGRESS: '<span class="status-badge info">Đang thực hiện</span>',
        WAITING_PART: '<span class="status-badge warning">Chờ phụ tùng</span>',
        COMPLETED: '<span class="status-badge success">Hoàn thành</span>',
    };

    const header = $(".top-navbar .d-flex.align-items-center");
    header.find(".status-badge").replaceWith(badgeClass[status]);
}

/**
 * Add newly requested part to table
 */
function addPartToTable(partRequest) {
    const tableBody = $("#partsTableBody");
    const emptyMessage = tableBody.find('tr td[colspan="5"]');

    if (emptyMessage.length) {
        emptyMessage.closest("tr").remove();
    }

    // Parts is the array from the relationship
    if (partRequest.parts && Array.isArray(partRequest.parts)) {
        partRequest.parts.forEach((part) => {
            const quantity = part.pivot?.quantity || 1;
            const total = (part.unit_price || 0) * quantity;

            const row = `
                <tr>
                    <td class="fw-bold">${part.name || "Vật tư không rõ"}</td>
                    <td><code>${part.sku || "-"}</code></td>
                    <td>${quantity}</td>
                    <td>${new Intl.NumberFormat("vi-VN", { style: "currency", currency: "VND" }).format(part.unit_price || 0)}</td>
                    <td>${new Intl.NumberFormat("vi-VN", { style: "currency", currency: "VND" }).format(total)}</td>
                </tr>
            `;

            tableBody.append(row);
        });
    }
}

// Initialize on document ready
$(document).ready(function () {
    initJobExecution();
});
