/**
 * Quotation Core Handler
 * Handles form submission, item management, calculations
 * Pattern: Event handlers and DOM manipulation logic
 */

import * as QuotationAPI from '../../services/reception-quotation-api.js';
import { showValidationErrors, handleAjaxError } from '../../helpers/validation.js';

let quotationData = {
    services: [],
    parts: [],
    technicians: [],
    items: [] // Current items in quotation
};

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
            handleAjaxError(xhr, '#quotation-container');
        });
}

/**
 * Render services in modal
 */
function renderServicesModal(services) {
    let html = '';
    services.forEach(service => {
        html += `
            <div class="list-group-item d-flex justify-content-between align-items-center cursor-pointer"
                 data-type="App\\Models\\Service" 
                 data-id="${service.id}" 
                 data-name="${service.name}" 
                 data-price="${service.price}">
                <div>
                    <div class="fw-bold">${service.name}</div>
                    <small class="text-muted">Giá: ${service.price.toLocaleString('vi-VN')} ₫</small>
                </div>
                <i class="fas fa-chevron-right text-muted"></i>
            </div>
        `;
    });
    $('#services-list').html(html);
}

/**
 * Filter services by search
 */
export function filterServices(query) {
    const allServices = $('#services-list').find('.list-group-item');
    
    allServices.each(function() {
        const name = $(this).find('.fw-bold').text().toLowerCase();
        const price = $(this).find('.text-muted').text().toLowerCase();
        
        if (name.includes(query.toLowerCase()) || price.includes(query.toLowerCase())) {
            $(this).show();
        } else {
            $(this).hide();
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
            handleAjaxError(xhr, '#quotation-container');
        });
}

/**
 * Render parts in modal
 */
function renderPartsModal(parts) {
    let html = '';
    parts.forEach(part => {
        html += `
            <div class="list-group-item d-flex justify-content-between align-items-center cursor-pointer"
                 data-type="App\\Models\\Part" 
                 data-id="${part.id}" 
                 data-name="${part.name}" 
                 data-price="${part.price_sell}">
                <div>
                    <div class="fw-bold">${part.name}</div>
                    <small class="text-muted">${part.sku} | Giá: ${part.price_sell.toLocaleString('vi-VN')} ₫</small>
                </div>
                <i class="fas fa-chevron-right text-muted"></i>
            </div>
        `;
    });
    $('#parts-list').html(html);
}

/**
 * Filter parts by search
 */
export function filterParts(query) {
    const allParts = $('#parts-list').find('.list-group-item');
    
    allParts.each(function() {
        const name = $(this).find('.fw-bold').text().toLowerCase();
        const sku = $(this).find('.text-muted').text().toLowerCase();
        
        if (name.includes(query.toLowerCase()) || sku.includes(query.toLowerCase())) {
            $(this).show();
        } else {
            $(this).hide();
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
            quotationData.technicians.forEach(tech => {
                $select.append(`<option value="${tech.id}">${tech.name}</option>`);
            });
        })
        .fail(function (xhr) {
            handleAjaxError(xhr, '#quotation-container');
        });
}

/**
 * Handle adding service/part to quotation
 */
export function handleAddItem(itemType, itemId, itemName, price) {
    const quantity = parseFloat($('#add-item-quantity').val()) || 1;
    const customPrice = parseFloat($('#add-item-price').val());
    const finalPrice = customPrice > 0 ? customPrice : price;

    const item = {
        item_type: itemType,
        item_id: itemId,
        name: itemName,
        quantity: quantity,
        price: finalPrice,
        discount: 0
    };

    // Add to items array
    quotationData.items.push(item);

    // Render in table
    addItemToTable(quotationData.items.length, item);

    // Update totals
    updateQuotationTotal();

    // Reset form
    $('#add-item-quantity').val(1);
    $('#add-item-price').val(0);
    
    // Hide item form section and reset modal
    $('#item-form-section').hide();
    $('#search-services').val('');
    $('#search-parts').val('');
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('addItemModal'));
    if (modal) {
        modal.hide();
    }
}

/**
 * Store current selected item for confirmation
 */
let selectedItem = null;

export function selectItem(itemType, itemId, itemName, price) {
    selectedItem = { itemType, itemId, itemName, price };
    
    // Show quantity/price form
    $('#item-form-section').show();
    $('#add-item-price').val(price);
    $('#add-item-quantity').val(1);
    
    // Focus on quantity input
    $('#add-item-quantity').focus();
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
    $('#empty-state').hide();

    const lineTotal = (item.quantity * item.price) - (item.quantity * item.price * (item.discount / 100));
    const badgeClass = item.item_type === 'App\\Models\\Service' 
        ? '<span class="badge bg-info-subtle text-info">Công</span>' 
        : '<span class="badge bg-secondary-subtle text-secondary">Vật tư</span>';
    
    const rowHtml = `
        <tr data-item-id="${item.item_type}-${item.item_id}" data-index="${index}">
            <td>${index}</td>
            <td>
                <div class="fw-bold">${item.name}</div>
                <div class="small text-muted">${item.item_id}</div>
            </td>
            <td>${badgeClass}</td>
            <td>cái</td>
            <td>
                <input type="number" class="form-control form-control-sm text-center item-quantity" value="${item.quantity}" data-index="${index}">
            </td>
            <td class="text-end item-price">${item.price.toLocaleString('vi-VN')}</td>
            <td class="text-end fw-bold item-total">${lineTotal.toLocaleString('vi-VN')}</td>
            <td>
                <button type="button" class="btn btn-sm text-danger btn-remove-item" data-index="${index}">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `;

    $('#quotation-items').append(rowHtml);
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
 * Handle quantity change
 */
export function handleQuantityChange(event) {
    const index = $(event.target).data('index');
    const newQuantity = parseFloat($(event.target).val()) || 0;

    if (newQuantity > 0) {
        quotationData.items[index - 1].quantity = newQuantity;
        updateQuotationTotal();
    }
}

/**
 * Update quotation total and VAT
 */
export function updateQuotationTotal() {
    let totalGoods = 0;
    let totalLabor = 0;

    quotationData.items.forEach(item => {
        const lineTotal = (item.quantity * item.price) - (item.quantity * item.price * (item.discount / 100));

        if (item.item_type === 'App\\Models\\Service') {
            totalLabor += lineTotal;
        } else {
            totalGoods += lineTotal;
        }
    });

    const vat = (totalGoods + totalLabor) * 0.08;
    const finalTotal = totalGoods + totalLabor + vat;

    // Update display
    $('.total-goods').html(totalGoods.toLocaleString('vi-VN'));
    $('.total-labor').html(totalLabor.toLocaleString('vi-VN'));
    $('.total-vat').html(vat.toLocaleString('vi-VN'));
    $('.total-payment').html(finalTotal.toLocaleString('vi-VN'));
}

/**
 * Handle save quotation
 */
export function handleSaveQuotation(orderId, form) {
    if (quotationData.items.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Cảnh báo',
            text: 'Vui lòng thêm ít nhất một hạng mục!'
        });
        return;
    }

    const $btn = form.find('button[type="submit"]');
    const originalText = $btn.html();
    const technicianId = $('select[name="technician_id"]').val();

    // Show loading state
    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Đang lưu...');

    QuotationAPI.saveQuotation(orderId, quotationData.items, technicianId)
        .done(function (response) {
            Swal.fire({
                icon: 'success',
                title: 'Thành công',
                text: response.message
            }).then(() => {
                // Reload or redirect
                window.location.href = '/reception';
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
            $btn.prop('disabled', false).html(originalText);
        });
}

/**
 * Handle print quotation
 */
export function handlePrintQuotation() {
    const orderId = $('input[name="order_id"]').val();
    
    if (quotationData.items.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Cảnh báo',
            text: 'Vui lòng thêm ít nhất một hạng mục trước khi in!'
        });
        return;
    }

    // Generate print window
    const printWindow = window.open('', '', 'height=600,width=800');
    
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
    let totalGoods = 0;
    let totalLabor = 0;
    let itemsHTML = '';

    quotationData.items.forEach((item, index) => {
        const lineTotal = (item.quantity * item.price) - (item.quantity * item.price * (item.discount / 100));
        const itemType = item.item_type === 'App\\Models\\Service' ? 'Dịch vụ' : 'Phụ tùng';

        itemsHTML += `
            <tr>
                <td>${index + 1}</td>
                <td>${item.name}</td>
                <td>${itemType}</td>
                <td>${item.quantity}</td>
                <td style="text-align: right;">${item.price.toLocaleString('vi-VN')}</td>
                <td style="text-align: right;">${lineTotal.toLocaleString('vi-VN')}</td>
            </tr>
        `;

        if (item.item_type === 'App\\Models\\Service') {
            totalLabor += lineTotal;
        } else {
            totalGoods += lineTotal;
        }
    });

    const vat = (totalGoods + totalLabor) * 0.08;
    const finalTotal = totalGoods + totalLabor + vat;

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
            <div class="header">
                <h1>BÁO GIÁ SỬA CHỮA</h1>
                <p>Ngày: ${new Date().toLocaleDateString('vi-VN')}</p>
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

            <div class="totals">
                <p>Tổng tiền hàng: <strong>${totalGoods.toLocaleString('vi-VN')}</strong></p>
                <p>Tổng tiền công: <strong>${totalLabor.toLocaleString('vi-VN')}</strong></p>
                <p>VAT (8%): <strong>${vat.toLocaleString('vi-VN')}</strong></p>
                <p class="total-amount">Tổng thanh toán: ${finalTotal.toLocaleString('vi-VN')} ₫</p>
            </div>

            <div style="clear: both; margin-top: 50px; text-align: center; color: #999;">
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
            icon: 'warning',
            title: 'Cảnh báo',
            text: 'Vui lòng thêm ít nhất một hạng mục!'
        });
        return;
    }

    // Generate message
    const message = generateQuotationMessage();
    
    // For now, show message that can be copied
    Swal.fire({
        icon: 'info',
        title: 'Gửi qua WhatsApp',
        html: `<textarea style="width: 100%; height: 200px; padding: 10px;">${message}</textarea>`,
        confirmButtonText: 'Copy',
        showCancelButton: true,
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            // Copy to clipboard
            const textarea = document.querySelector('textarea');
            textarea.select();
            document.execCommand('copy');
            
            Swal.fire({
                icon: 'success',
                title: 'Thành công',
                text: 'Đã sao chép. Dán vào WhatsApp để gửi cho khách!'
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
    let itemsText = '';

    quotationData.items.forEach((item, index) => {
        const lineTotal = (item.quantity * item.price) - (item.quantity * item.price * (item.discount / 100));
        const itemType = item.item_type === 'App\\Models\\Service' ? 'Dịch vụ' : 'Phụ tùng';

        itemsText += `${index + 1}. ${item.name} (${itemType}) - SL: ${item.quantity} - ${lineTotal.toLocaleString('vi-VN')}₫\n`;

        if (item.item_type === 'App\\Models\\Service') {
            totalLabor += lineTotal;
        } else {
            totalGoods += lineTotal;
        }
    });

    const vat = (totalGoods + totalLabor) * 0.08;
    const finalTotal = totalGoods + totalLabor + vat;

    return `*BÁO GIÁ SỬA CHỮA*

${itemsText}
━━━━━━━━━━━━━━━
Tổng tiền hàng: ${totalGoods.toLocaleString('vi-VN')}₫
Tổng tiền công: ${totalLabor.toLocaleString('vi-VN')}₫
VAT (8%): ${vat.toLocaleString('vi-VN')}₫
*Tổng cộng: ${finalTotal.toLocaleString('vi-VN')}₫*`;
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
