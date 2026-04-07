/**
 * Admin Customer Management Module
 * Handles UI interactions for customer management page
 */

import {
    tableList,
    createCustomer,
    updateCustomer,
    deleteCustomer,
    toggleStatus,
} from "../services/admin-customer-api.js";

$(document).ready(function () {
    loadCustomerTable();
    initSearch();
    initFilters();
    initFormSubmit();
    initEditButtons();
    initDeleteButtons();
    initModalReset();
});

let currentFilters = {
    search: "",
    status: "",
    sort_by: "",
    sort_order: "desc",
};
$(document).on("click", ".pagination a", function (e) {
    e.preventDefault();
    let url = $(this).attr("href");
    loadCustomerTable(url);
});
/**
 * Load customer table with current filters
 */
function loadCustomerTable(url = window.routes?.adminCustomersList) {
    let sort_by = $("#customer-sort-filter").val();
    let sort_order = "desc";

    if (sort_by === "name") {
        sort_order = "asc";
    }

    const filters = {
        search: $("#customer-search").val().trim() || undefined,
        sort_by: sort_by || undefined,
        sort_order: sort_order,
    };

    // Remove undefined values
    Object.keys(filters).forEach(
        (key) => filters[key] === undefined && delete filters[key],
    );

    tableList(url, filters)
        .done(function (html) {
            $("#customer-table-container").html(html);
        })
        .fail(function (error) {
            console.error("Error loading customer table:", error);
            $("#customer-table-container").html(
                `<div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Có lỗi khi tải danh sách khách hàng. Vui lòng thử lại sau.
                </div>`,
            );
        });
}

/**
 * Initialize search with debounce
 */
function initSearch() {
    let debounceTimer;
    $(document).on("keyup", "#customer-search", function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            loadCustomerTable();
        }, 500);
    });
}

/**
 * Initialize filter listeners
 */
function initFilters() {
    $(document).on("change", "#customer-sort-filter", function () {
        loadCustomerTable();
    });
}

/**
 * Initialize form submission
 */
function initFormSubmit() {
    $(document).on("submit", "#form_create_customer", function (e) {
        e.preventDefault();

        const form = $(this);
        const customerId = form.find('input[name="id"]').val();
        const isCreate = !customerId;

        const formData = {
            name: form.find('input[name="name"]').val(),
            phone: form.find('input[name="phone"]').val(),
            email: form.find('input[name="email"]').val(),
            address: form.find('textarea[name="address"]').val(),
        };

        const request = isCreate
            ? createCustomer(formData)
            : updateCustomer(customerId, formData);

        request
            .done(function (response) {
                if (response.success) {
                    showAlert("success", response.message);

                    // Close modal
                    const modal = bootstrap.Modal.getInstance(
                        document.getElementById("create-customer"),
                    );
                    if (modal) modal.hide();

                    // Reload table after a short delay
                    setTimeout(() => {
                        loadCustomerTable();
                    }, 500);
                } else {
                    showAlert("error", response.message || "Có lỗi xảy ra");
                }
            })
            .fail(function (xhr) {
                console.error("Save error:", xhr);

                if (xhr.status === 422) {
                    // Validation errors
                    const errors = xhr.responseJSON.errors || {};
                    displayFormErrors(errors);
                    showAlert("error", "Vui lòng sửa các lỗi bên dưới");
                } else {
                    showAlert("error", "Có lỗi xảy ra khi lưu khách hàng");
                }
            });
    });
}

/**
 * Initialize edit buttons
 */
function initEditButtons() {
    $(document).on("click", ".edit-customer", function () {
        const customer = $(this).data("customer");
        loadCustomerForm(customer);
    });
}

/**
 * Initialize delete buttons
 */
function initDeleteButtons() {
    $(document).on("click", ".delete-customer", function () {
        const customerId = $(this).data("customer-id");
        const customerName = $(this).data("customer-name");
        confirmDeleteCustomer(customerId, customerName);
    });
}

/**
 * Reset modal when it closes
 */
function initModalReset() {
    $("#create-customer").on("hidden.bs.modal", function () {
        resetCustomerForm();
    });
}

/**
 * Load customer data into form for editing
 */
function loadCustomerForm(customer) {
    const form = $("#form_create_customer");

    // Set form to edit mode
    form.find('input[name="id"]').val(customer.id);

    // Load customer data
    form.find('input[name="name"]').val(customer.name);
    form.find('input[name="phone"]').val(customer.phone);
    form.find('input[name="email"]').val(customer.email || "");
    form.find('textarea[name="address"]').val(customer.address || "");

    // Update modal title
    $(".customer-modal-title").text("Chỉnh sửa khách hàng");

    // Clear validation messages
    form.find(".invalid-feedback").text("");
    form.find(".form-control").removeClass("is-invalid");

    // Show modal
    const modal = new bootstrap.Modal(
        document.getElementById("create-customer"),
    );
    modal.show();
}

/**
 * Reset customer form to create mode
 */
function resetCustomerForm() {
    const form = $("#form_create_customer");
    form[0].reset();

    // Reset to create mode
    form.find('input[name="id"]').val("");
    $(".customer-modal-title").text("Thêm khách hàng");

    // Clear validation messages
    form.find(".invalid-feedback").text("");
    form.find(".form-control").removeClass("is-invalid");
}

/**
 * Confirm and delete customer
 */
function confirmDeleteCustomer(customerId, customerName) {
    Swal.fire({
        title: "Xác nhận xóa",
        html: `Bạn có chắc muốn xóa khách hàng <strong>${customerName}</strong>? Hành động này không thể hoàn tác.`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc3545",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Xóa",
        cancelButtonText: "Hủy",
        focusCancel: true,
    }).then((result) => {
        if (result.isConfirmed) {
            deleteCustomer(customerId)
                .done(function (response) {
                    if (response.success) {
                        showAlert("success", response.message);
                        setTimeout(() => {
                            loadCustomerTable();
                        }, 500);
                    } else {
                        showAlert("error", response.message || "Có lỗi xảy ra");
                    }
                })
                .fail(function (xhr) {
                    console.error("Delete error:", xhr);
                    const errorMessage =
                        xhr.responseJSON?.message ||
                        "Có lỗi xảy ra khi xóa khách hàng";
                    showAlert("error", errorMessage);
                });
        }
    });
}

/**
 * Display form validation errors
 */
function displayFormErrors(errors) {
    const form = $("#form_create_customer");

    // Clear previous errors
    form.find(".invalid-feedback").text("");
    form.find(".form-control").removeClass("is-invalid");

    // Display new errors
    Object.keys(errors).forEach((field) => {
        const input = form.find(`[name="${field}"]`);
        if (input.length) {
            input.addClass("is-invalid");
            input.next(".invalid-feedback").text(errors[field][0] || "");
        }
    });
}

/**
 * Show alert notification
 */
function showAlert(type = "success", message = "") {
    const bgClass = type === "success" ? "bg-success" : "bg-danger";
    const icon =
        type === "success"
            ? '<i class="fas fa-check-circle me-2"></i>'
            : '<i class="fas fa-exclamation-circle me-2"></i>';

    // Tạo container nếu chưa có
    if ($("#toast-container").length === 0) {
        $("body").append(`
            <div id="toast-container" 
                 class="position-fixed top-0 end-0 p-3" 
                 style="z-index: 9999;"></div>
        `);
    }

    const toastId = "toast-" + Date.now();

    const toastHtml = `
        <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0 mb-2" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    ${icon} ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;

    $("#toast-container").append(toastHtml);

    const toastEl = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastEl, {
        delay: 4000,
    });

    toast.show();

    // Xóa DOM sau khi ẩn
    toastEl.addEventListener("hidden.bs.toast", function () {
        $(this).remove();
    });
}
