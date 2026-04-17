/**
 * Quotation Core Handler
 * Handles form submission, item management, calculations
 * Pattern: Event handlers and DOM manipulation logic
 */

import * as QuotationAPI from "../../services/reception-quotation-api.js";
import {
    showValidationErrors,
    handleAjaxError,
} from "../../helpers/validation.js";

let quotationData = {
    services: [],
    parts: [],
    labors: [],
    technicians: [],
    items: [], // Current items in quotation
};

/**
 * Load existing quotation items (if orderId provided)
 */
export function loadExistingQuotation(orderId) {
    if (!orderId) return;

    // Fetch quotation items as HTML partial
    QuotationAPI.getQuotation(orderId)
        .done(function (html) {
            if (html && html.trim()) {
                // Insert the partial HTML into the table
                $("#quotation-items").html(html);

                // Recalculate totals based on loaded items
                recalculateFromTable();
                const technicianId = $("#technician_id").val();
                if (technicianId) {
                    $('select[name="technician_id"]')
                        .val(technicianId)
                        .trigger("change");
                }
            }
        })
        .fail(function (xhr) {
            console.error("Failed to load quotation items", xhr);
        });
}

/**
 * Calculate totals from existing table rows
 */
export function recalculateFromTable() {
    let totalGoods = 0;
    let totalLabor = 0;
    let totalService = 0;

    // Clear items array before rebuilding
    quotationData.items = [];

    // Parse items from table rows
    $("#quotation-items tr").each(function (index) {
        const $row = $(this);
        const nameItem = $row.find(".name-item").text().trim();
        const itemIdParts = $row.data("item-id").split("-");
        const itemType = itemIdParts[0];
        const itemId = parseInt(itemIdParts[1], 10);
        const quantity = parseFloat($row.find(".item-quantity").val()) || 1;
        const price =
            parseFloat($row.find(".item-price").text().replace(/\./g, "")) || 0;
        const discount = 0; // Default discount

        const lineTotal =
            quantity * price - quantity * price * (discount / 100);

        // Update quotation data with complete item info
        quotationData.items.push({
            name: nameItem,
            item_type: itemType,
            item_id: itemId,
            quantity: quantity,
            price: price,
            discount: discount,
        });

        if (itemType.includes("Labor")) {
            totalLabor += lineTotal;
        } else if (itemType.includes("Service")) {
            totalService += lineTotal;
        } else {
            totalGoods += lineTotal;
        }
    });

    // Update display with calculated totals
    const finalTotal = totalGoods + totalLabor + totalService;

    $(".total-goods").html(totalGoods.toLocaleString("vi-VN"));
    $(".total-labor").html(totalLabor.toLocaleString("vi-VN"));
    $(".total-service").html(totalService.toLocaleString("vi-VN"));
    $(".total-payment").html(finalTotal.toLocaleString("vi-VN"));
}

/**
 * Load services from backend
 */
export function loadServices() {
    QuotationAPI.getServices()
        .done(function (services) {
            quotationData.services = services;
            renderServicesModal(services);
        })
        .fail(function (xhr) {
            handleAjaxError(xhr, "#quotation-container");
        });
}

/**
 * Render services in modal
 */
function renderServicesModal(services) {
    let html = "";
    services.forEach((service) => {
        html += `
            <div class="list-group-item d-flex justify-content-between align-items-center cursor-pointer"
                 data-type="App\\Models\\Service" 
                 data-id="${service.id}" 
                 data-name="${service.name}" 
                 data-price="${service.price}">
                <div>
                    <div class="fw-bold">${service.name}</div>
                    <small class="text-muted">Giá: ${service.price.toLocaleString("vi-VN")} ₫</small>
                </div>
                <i class="fas fa-chevron-right text-muted"></i>
            </div>
        `;
    });
    $("#services-list").html(html);
}

/**
 * Filter services by search
 */
export function filterServices(query) {
    const allServices = $("#services-list").find(".list-group-item");

    allServices.each(function () {
        const name = $(this).find(".fw-bold").text().toLowerCase();
        const price = $(this).find(".text-muted").text().toLowerCase();

        if (
            name.includes(query.toLowerCase()) ||
            price.includes(query.toLowerCase())
        ) {
            $(this).removeClass("d-none");
        } else {
            $(this).addClass("d-none");
        }
    });
}

/**
 * Load parts from backend
 */
export function loadParts() {
    QuotationAPI.getParts()
        .done(function (parts) {
            quotationData.parts = parts;
            renderPartsModal(parts);
        })
        .fail(function (xhr) {
            handleAjaxError(xhr, "#quotation-container");
        });
}

/**
 * Render parts in modal
 */
function renderPartsModal(parts) {
    let html = "";
    parts.forEach((part) => {
        html += `
            <div class="list-group-item d-flex justify-content-between align-items-center cursor-pointer"
                 data-type="App\\Models\\Part" 
                 data-id="${part.id}" 
                 data-name="${part.name}" 
                 data-price="${part.price_sell}">
                <div>
                    <div class="fw-bold">${part.name}</div>
                    <small class="text-muted">${part.sku} | Giá: ${part.price_sell.toLocaleString("vi-VN")} ₫</small>
                </div>
                <i class="fas fa-chevron-right text-muted"></i>
            </div>
        `;
    });
    $("#parts-list").html(html);
}

/**
 * Filter parts by search
 */
export function filterParts(query) {
    const allParts = $("#parts-list").find(".list-group-item");
    console.log(query);

    allParts.each(function () {
        const name = $(this).find(".fw-bold").text().toLowerCase();
        const sku = $(this).find(".text-muted").text().toLowerCase();

        if (
            name.includes(query.toLowerCase()) ||
            sku.includes(query.toLowerCase())
        ) {
            $(this).removeClass("d-none");
        } else {
            $(this).addClass("d-none");
        }
    });
}
/**
 * Load Labors from backend
 */
export function loadLabors() {
    QuotationAPI.getLabors()
        .done(function (labors) {
            quotationData.labors = labors;
            renderLaborsModal(labors);
        })
        .fail(function (xhr) {
            handleAjaxError(xhr, "#quotation-container");
        });
}
/**
 * Render Labors in modal
 */
function renderLaborsModal(labors) {
    let html = "";
    labors.forEach((labor) => {
        html += `
            <div class="list-group-item d-flex justify-content-between align-items-center cursor-pointer"
                 data-type="App\\Models\\Labor" 
                 data-id="${labor.id}" 
                 data-name="${labor.name}" 
                 data-price="${labor.price}">
                <div>
                    <div class="fw-bold">${labor.name}</div>
                    <small class="text-muted">${labor.sku} | Giá: ${labor.price.toLocaleString("vi-VN")} ₫</small>
                </div>
                <i class="fas fa-chevron-right text-muted"></i>
            </div>
        `;
    });
    $("#labors-list").html(html);
}
/**
 * Filter Labors by search
 */
export function filterLabors(query) {
    const allLabors = $("#labors-list").find(".list-group-item");
    allLabors.each(function () {
        const name = $(this).find(".fw-bold").text().toLowerCase();
        const sku = $(this).find(".text-muted").text().toLowerCase();
        if (
            name.includes(query.toLowerCase()) ||
            sku.includes(query.toLowerCase())
        ) {
            $(this).removeClass("d-none");
        } else {
            $(this).addClass("d-none");
        }
    });
}
/**
 * Load technicians from backend
 */
export function loadTechnicians() {
    QuotationAPI.getTechnicians()
        .done(function (technicians) {
            quotationData.technicians = technicians;
            // Populate technician select
            const $select = $('select[name="technician_id"]');
            quotationData.technicians.forEach((tech) => {
                $select.append(
                    `<option value="${tech.id}">${tech.name}</option>`,
                );
            });
        })
        .fail(function (xhr) {
            handleAjaxError(xhr, "#quotation-container");
        });
}

/**
 * Handle adding service/part to quotation
 */
export function handleAddItem(itemType, itemId, itemName, price) {
    const quantity = parseFloat($("#add-item-quantity").val()) || 1;
    const customPrice = parseFloat($("#add-item-price").val());
    const finalPrice = customPrice > 0 ? customPrice : price;

    const item = {
        item_type: itemType,
        item_id: itemId,
        name: itemName,
        quantity: quantity,
        price: finalPrice,
        discount: 0,
    };

    // Add to items array
    quotationData.items.push(item);

    // Render in table
    addItemToTable(quotationData.items.length, item);

    // Update totals
    updateQuotationTotal();

    // Reset form
    $("#add-item-quantity").val(1);
    $("#add-item-price").val(0);

    // Hide item form section and reset modal
    $("#item-form-section").hide();
    $("#search-services").val("");
    $("#search-parts").val("");

    // Close modal
    const modal = bootstrap.Modal.getInstance(
        document.getElementById("addItemModal"),
    );
    if (modal) {
        modal.hide();
    }
    if (itemType === "App\\Models\\Part") {
        // 1. Tìm phụ tùng vừa chọn trong mảng data đã load từ API
        const selectedPart = quotationData.parts.find((p) => p.id === itemId);

        // 2. Kiểm tra xem phụ tùng này có tiền công gợi ý đi kèm không
        if (
            selectedPart &&
            selectedPart.suggested_labors &&
            selectedPart.suggested_labors.length > 0
        ) {
            // Lấy công đầu tiên trong danh sách gợi ý
            const labor = selectedPart.suggested_labors[0];

            // 3. Hiển thị popup hỏi người dùng
            Swal.fire({
                title: "Thêm tiền công tương ứng?",
                text: `Hệ thống nhận thấy bạn vừa thêm "${itemName}". Bạn có muốn thêm "${labor.name}" với giá ${parseFloat(labor.price).toLocaleString("vi-VN")} ₫ không?`,
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Có, thêm vào báo giá!",
                cancelButtonText: "Không cần",
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tạo object tiền công
                    const laborItem = {
                        item_type: "App\\Models\\Labor",
                        item_id: labor.id,
                        name: labor.name,
                        quantity: 1, // Mặc định số lượng công là 1
                        price: parseFloat(labor.price) || 0,
                        discount: 0,
                    };

                    // Push thẳng vào mảng items và render lại bảng
                    quotationData.items.push(laborItem);
                    addItemToTable(quotationData.items.length, laborItem);
                    updateQuotationTotal();

                    // Thông báo thêm thành công (Toast mượt mà)
                    Swal.fire({
                        icon: "success",
                        title: "Đã thêm tiền công",
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 1500,
                    });
                }
            });
        }
    }
}

/**
 * Store current selected item for confirmation
 */
let selectedItem = null;

export function selectItem(itemType, itemId, itemName, price) {
    selectedItem = { itemType, itemId, itemName, price };

    // Show quantity/price form
    $("#item-form-section").show();
    $("#add-item-price").val(price);
    $("#add-item-quantity").val(1);

    // Focus on quantity input
    $("#add-item-quantity").focus();
}

/**
 * Get selected item
 */
export function getSelectedItem() {
    return selectedItem;
}

/**
 * Add item row to quotation table
 */
function addItemToTable(index, item) {
    // Hide empty state
    $("#empty-state").hide();

    const lineTotal =
        item.quantity * item.price -
        item.quantity * item.price * (item.discount / 100);

    let badgeClass = "";
    if (item.item_type === "App\\Models\\Service") {
        badgeClass =
            '<span class="badge bg-info-subtle text-info fw-bold" style="width: 75px">Dịch vụ</span>';
    } else if (item.item_type === "App\\Models\\Labor") {
        badgeClass =
            '<span class="badge bg-primary-subtle text-primary fw-bold" style="width: 75px">Tiền công</span>';
    } else {
        badgeClass =
            '<span class="badge bg-secondary-subtle text-secondary fw-bold" style="width: 75px">Vật tư</span>';
    }

    const rowHtml = `
        <tr data-item-id="${item.item_type}-${item.item_id}" data-index="${index}">
            <td class="text-center">${index}</td>
            <td class="text-start">
                <div class="fw-bold">${item.name}</div>
                <div class="small text-muted">id: ${item.item_id}</div>
            </td>
            <td class="text-center">${badgeClass}</td>
            <td class="text-center">cái</td>
            <td class="text-center">
                <input class="text-center" style="max-width: 60px;" value="${item.quantity}" data-index="${index}" disabled>
            </td>
            <td class="text-end item-price">${item.price.toLocaleString("vi-VN")}</td>
            <td class="text-end fw-bold item-total">${lineTotal.toLocaleString("vi-VN")}</td>
            <td class="text-center">
                <button type="button" class="btn btn-sm text-danger btn-remove-item" data-index="${index}">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `;

    $("#quotation-items").append(rowHtml);
}

/**
 * Handle removing item from quotation
 */
export function handleRemoveItem(index) {
    quotationData.items.splice(index - 1, 1);
    $(`tr[data-index="${index}"]`).remove();
    updateQuotationTotal();
}

/**
 * Update quotation total and VAT
 */
export function updateQuotationTotal() {
    let totalGoods = 0;
    let totalLabor = 0;
    let totalService = 0;

    quotationData.items.forEach((item) => {
        const lineTotal = item.quantity * item.price;

        if (item.item_type === "App\\Models\\Labor") {
            totalLabor += lineTotal;
        } else if (item.item_type === "App\\Models\\Service") {
            totalService += lineTotal;
        } else {
            totalGoods += lineTotal;
        }
    });

    const finalTotal = totalGoods + totalLabor;

    // Update display
    $(".total-goods").html(totalGoods.toLocaleString("vi-VN"));
    $(".total-labor").html(totalLabor.toLocaleString("vi-VN"));
    $(".total-payment").html(finalTotal.toLocaleString("vi-VN"));
}

/**
 * Handle save quotation
 */
export function handleSaveQuotation(orderId, form) {
    if (quotationData.items.length === 0) {
        Swal.fire({
            icon: "warning",
            title: "Cảnh báo",
            text: "Vui lòng thêm ít nhất một hạng mục!",
        });
        return;
    }

    const $btn = form.find('button[type="submit"]');
    const originalText = $btn.html();
    const technicianId = $('select[name="technician_id"]').val();

    // Show loading state
    $btn.prop("disabled", true).html(
        '<i class="fas fa-spinner fa-spin me-2"></i> Đang lưu...',
    );

    QuotationAPI.saveQuotation(orderId, quotationData.items, technicianId)
        .done(function (response) {
            Swal.fire({
                icon: "success",
                title: "Thành công",
                text: response.message,
            });
        })
        .fail(function (xhr) {
            if (xhr.status === 422) {
                showValidationErrors(xhr.responseJSON.errors, form);
            } else {
                handleAjaxError(xhr, form);
            }
        })
        .always(function () {
            $btn.prop("disabled", false).html(originalText);
        });
}
/**
 * Handle cancel quotation
 */
$(document).on("click", ".btn-cancel-order", function () {
    const orderId = $(this).data("id");

    Swal.fire({
        title: "Bạn chắc chắn muốn hủy?",
        text: "Hành động này không thể hoàn tác!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Hủy đơn",
        cancelButtonText: "Đóng",
    }).then((result) => {
        if (!result.isConfirmed) return;

        QuotationAPI.cancelOrder(orderId)
            .done(function (res) {
                Swal.fire("Thành công", res.message, "success");
                updateQuotationHeader(res.data);
            })
            .fail(function (xhr) {
                Swal.fire(
                    "Lỗi",
                    xhr.responseJSON?.message ?? "Có lỗi xảy ra",
                    "error",
                );
            });
    });
});

function updateQuotationHeader(order) {
    const statusHtml = renderStatusBadge(order.status);
    $("#quotation-status").html(statusHtml);

    if (order.status === "CANCELED") {
        $(".btn-cancel-order").remove();
    }

    if (order.status !== "COMPLETED") {
        $(".btn-pay").remove();
    }
}

function renderStatusBadge(status) {
    switch (status) {
        case "RECEIVED":
            return `<span class="status-badge fs-6 px-3 fw-bold info">Đã tiếp nhận</span>`;
        case "QUOTED":
            return `<span class="status-badge fs-6 px-3 fw-bold warning">Đã báo giá</span>`;
        case "IN_PROGRESS":
            return `<span class="status-badge fs-6 px-3 fw-bold bg-primary-subtle text-primary">Đang thực hiện</span>`;
        case "WAITING_PART":
            return `<span class="status-badge fs-6 px-3 fw-bold bg-secondary-subtle text-secondary">Chờ phụ tùng</span>`;
        case "COMPLETED":
            return `<span class="status-badge fs-6 px-3 fw-bold success">Hoàn thành</span>`;
        case "PAID":
            return `<span class="status-badge fs-6 px-3 fw-bold bg-success text-light">Đã thanh toán</span>`;
        case "CANCELED":
            return `<span class="status-badge fs-6 px-3 fw-bold danger">Đã huỷ</span>`;
        default:
            return `<span class="status-badge fs-6 px-3 fw-bold bg-dark text-light">Không xác định</span>`;
    }
}
/**
 * Handle print quotation
 */
export function handlePrintQuotation() {
    if (quotationData.items.length === 0) {
        Swal.fire({
            icon: "warning",
            title: "Cảnh báo",
            text: "Vui lòng thêm ít nhất một hạng mục trước khi in!",
        });
        return;
    }

    // Generate print window
    const printWindow = window.open("", "", "height=600,width=800");

    const quotationHTML = generatePrintHTML();

    printWindow.document.write(quotationHTML);
    printWindow.document.close();

    setTimeout(() => {
        printWindow.print();
    }, 250);
}

/**
 * Generate print HTML
 */
function generatePrintHTML() {
    const config = window.QuotationConfig;
    const orderId = config.orderId;

    const branchName = config.branch.name;
    const branchPhone = config.branch.phone;
    const branchAddress = config.branch.address;

    const customerName = config.customer.name;
    const customerPhone = config.customer.phone;
    const customerAddress = config.customer.address;

    const carName = config.car.name;
    const carLicensePlate = config.car.license_plate;

    let totalGoods = 0;
    let totalLabor = 0;
    let totalService = 0;
    let itemsHTML = "";

    quotationData.items.forEach((item, index) => {
        const lineTotal = item.quantity * item.price;

        let itemType = "N/A";
        if (item.item_type === "App\\Models\\Service") {
            itemType = "Dịch vụ";
        } else if (item.item_type === "App\\Models\\Labor") {
            itemType = "Tiền công";
        } else {
            itemType = "Vật tư";
        }
        itemsHTML += `
            <tr>
                <td>${index + 1}</td>
                <td>${item.name}</td>
                <td>${itemType}</td>
                <td>${item.quantity}</td>
                <td style="text-align: right;">${item.price.toLocaleString("vi-VN")}</td>
                <td style="text-align: right;">${lineTotal.toLocaleString("vi-VN")}</td>
            </tr>
        `;

        if (item.item_type === "App\\Models\\Labor") {
            totalLabor += lineTotal;
        } else if (item.item_type === "App\\Models\\Service") {
            totalService += lineTotal;
        } else {
            totalGoods += lineTotal;
        }
    });

    const finalTotal = totalGoods + totalLabor + totalService;

    return `
        <!DOCTYPE html>
        <html lang="vi">
        <head>
            <meta charset="UTF-8">
            <title>Báo giá</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 20px; }
                .header h1 { margin: 0; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                .totals { float: right; width: 300px; text-align: right; }
                .totals p { margin: 5px 0; }
                .total-amount { font-weight: bold; font-size: 18px; }
            </style>
        </head>
        <body>
            <div style="display:flex; justify-content:space-between; align-items:flex-start; border-bottom:2px solid #000; padding-bottom:10px; margin-bottom:20px;">

                <!-- LEFT -->
                <div style="width:55%;">
                    <div style="font-size:18px; font-weight:bold; text-transform:uppercase;">
                        CBAUTO GARAGE
                    </div>

                    <div style="font-size:13px; margin-top:5px;">
                        <div>Chi nhánh: <strong>${branchName}</strong></div>
                        <div>Địa chỉ: ${branchAddress}</div>
                        <div>Điện thoại: <strong>${branchPhone}</strong></div>
                    </div>
                </div>

                <!-- RIGHT -->
                <div style="width:45%; text-align:right;">
                    <div style="font-size:20px; font-weight:bold;">
                        BÁO GIÁ SỬA CHỮA
                    </div>

                    <div style="font-size:13px; margin-top:5px;">
                        <div>Số: <strong>ORD-${orderId}</strong></div>
                        <div>Ngày: ${new Date().toLocaleDateString("vi-VN")}</div>
                    </div>
                </div>

            </div>
            
            <div style="display:flex; justify-content:space-between; align-items:flex-start; border-bottom:2px solid #000; padding-bottom:10px; margin-bottom:20px;">

                <!-- LEFT -->
                <div style="width:45%;">
                    <div style="font-size:16px; font-weight:bold; text-transform:uppercase;">
                        Thông tin khách hàng
                    </div>

                    <div style="font-size:13px; margin-top:5px;">
                        <div>Khách hàng: <strong>${customerName}</strong></div>
                        <div>Điện thoại: <strong>${customerPhone}</strong></div>
                        <div>Địa chỉ: ${customerAddress}</div>
                    </div>
                </div>

                <div style="width:45%; margin-left: 10px">
                    <div style="font-size:16px; font-weight:bold; text-transform:uppercase;">
                        Thông tin xe
                    </div>

                    <div style="font-size:13px; margin-top:5px;">
                        <div>Xe: <strong>${carName}</strong></div>
                        <div>Biển số: <strong>${carLicensePlate}</strong></div>
                    </div>
                </div>

            </div>

            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên hạng mục</th>
                        <th>Loại</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    ${itemsHTML}
                </tbody>
            </table>

            <div class="totals" style="border-top:2px solid #000; padding-top:10px; margin-top:20px;">
                <p>Tổng tiền dịch vụ: <strong>${totalService.toLocaleString("vi-VN")}</strong></p>
                <p>Tổng tiền vật tư: <strong>${totalGoods.toLocaleString("vi-VN")}</strong></p>
                <p>Tổng tiền công: <strong>${totalLabor.toLocaleString("vi-VN")}</strong></p>
                <p class="total-amount">Tổng thanh toán: ${finalTotal.toLocaleString("vi-VN")} ₫</p>
            </div>

            <div style="clear: both; margin-top: 180px; text-align: center; color: #999;">
                <p>Cảm ơn quý khách sử dụng dịch vụ của chúng tôi!</p>
            </div>
        </body>
        </html>
    `;
}

/**
 * Handle send quotation via WhatsApp
 */
export function handleSendWhatsApp() {
    const orderId = $('input[name="order_id"]').val();

    if (quotationData.items.length === 0) {
        Swal.fire({
            icon: "warning",
            title: "Cảnh báo",
            text: "Vui lòng thêm ít nhất một hạng mục!",
        });
        return;
    }

    // Generate message
    const message = generateQuotationMessage();

    // For now, show message that can be copied
    Swal.fire({
        icon: "info",
        title: "Gửi qua tin nhắn",
        html: `<textarea style="width: 100%; height: 200px; padding: 10px;">${message}</textarea>`,
        confirmButtonText: "Copy",
        showCancelButton: true,
        cancelButtonText: "Hủy",
    }).then((result) => {
        if (result.isConfirmed) {
            // Copy to clipboard
            const textarea = document.querySelector("textarea");
            textarea.select();
            document.execCommand("copy");

            Swal.fire({
                icon: "success",
                title: "Thành công",
                text: "Đã sao chép. Dán vào tin nhắn để gửi cho khách!",
            });
        }
    });
}

/**
 * Generate quotation message for WhatsApp
 */
function generateQuotationMessage() {
    let totalGoods = 0;
    let totalLabor = 0;
    let totalService = 0;
    let itemsText = "";

    quotationData.items.forEach((item, index) => {
        const lineTotal = item.quantity * item.price;
        let itemType = "N/A";
        if (item.item_type === "App\\Models\\Service") {
            itemType = "Dịch vụ";
        } else if (item.item_type === "App\\Models\\Labor") {
            itemType = "Tiền công";
        } else {
            itemType = "Vật tư";
        }

        itemsText += `${index + 1}. ${item.name} (${itemType}) - SL: ${item.quantity} - ${lineTotal.toLocaleString("vi-VN")}₫\n`;

        if (item.item_type === "App\\Models\\Labor") {
            totalLabor += lineTotal;
        } else if (item.item_type === "App\\Models\\Service") {
            totalService += lineTotal;
        } else {
            totalGoods += lineTotal;
        }
    });

    const finalTotal = totalGoods + totalLabor + totalService;

    return `*BÁO GIÁ SỬA CHỮA*

${itemsText}
━━━━━━━━━━━━━━━
Tổng tiền dịch vụ: ${totalService.toLocaleString("vi-VN")}₫
Tổng tiền vật tư: ${totalGoods.toLocaleString("vi-VN")}₫
Tổng tiền công: ${totalLabor.toLocaleString("vi-VN")}₫
━━━━━━━━━━━━━━━
***Tổng cộng: ${finalTotal.toLocaleString("vi-VN")}₫***`;
}

/**
 * Get current quotation data
 */
export function getQuotationData() {
    return quotationData;
}

/**
 * Get current items
 */
export function getItems() {
    return quotationData.items;
}
