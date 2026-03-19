/**
 * Staff Management Module
 * CRUD staff (users), toggle status
 */

import { showValidationErrors } from "../helpers/validation";
import { confirmDelete } from "../helpers/sweetalert";

$(document).ready(function () {
    loadStaff();
    initSearch();
    initFilters();
});

/**
 * Load staff table with filters
 */
function loadStaff(url = window.routes.staffList) {
    const filters = {
        search: $("#staff-search").val().trim() || undefined,
        role: $("#staff-role-filter").val() || undefined,
        branch: $("#staff-branch-filter").val() || undefined,
        status: $("#staff-status-filter").val() || undefined,
    };

    // Remove undefined values
    Object.keys(filters).forEach(
        (key) => filters[key] === undefined && delete filters[key],
    );

    $.ajax({
        url: url,
        method: "GET",
        data: filters,
        dataType: "html",
    })
        .done(function (res) {
            $("#staff-table-container").html(res);
        })
        .fail(function (xhr) {
            console.log("Error loading staff:", xhr);
            Swal.fire("Lỗi!", "Không thể tải danh sách nhân viên.", "error");
        });
}

/**
 * Initialize search with debounce
 */
function initSearch() {
    let debounceTimer;
    $(document).on("keyup", "#staff-search", function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            loadStaff();
        }, 500);
    });
}

/**
 * Initialize filter listeners
 */
function initFilters() {
    $(document).on(
        "change",
        "#staff-role-filter, #staff-branch-filter, #staff-status-filter",
        function () {
            loadStaff();
        },
    );
}

/**
 * Handle form submission (create/update)
 */
$(document).on("submit", "#form_create_staff", function (e) {
    e.preventDefault();

    const form = $(this);
    const formData = new FormData(this);
    const id = form.find('input[name="id"]').val();

    // Clear previous errors
    form.find(".invalid-feedback").hide().text("");
    form.find(".form-control, .form-select").removeClass("is-invalid");

    // Add loading state
    const $submitBtn = form.find('button[type="submit"]');
    const originalText = $submitBtn.html();
    $submitBtn
        .prop("disabled", true)
        .html('<i class="fas fa-spinner fa-spin"></i> Đang lưu...');

    if (id) {
        // Update
        formData.append("_method", "PUT");

        $.ajax({
            url: window.routes.staffUpdate.replace(":staff", id),
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
        })
            .done(function (response) {
                Swal.fire({
                    icon: "success",
                    title: "Thành công!",
                    text: response.message || "Cập nhật nhân viên thành công",
                    confirmButtonText: "Xác nhận!",
                });
                $("#create-staff").modal("hide");
                loadStaff();
            })
            .fail(function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    showValidationErrors(errors, form);
                } else {
                    Swal.fire("Lỗi!", "Không thể cập nhật nhân viên.", "error");
                }
            })
            .always(function () {
                $submitBtn.prop("disabled", false).html(originalText);
            });
    } else {
        // Create
        $.ajax({
            url: window.routes.staffStore,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
        })
            .done(function (response) {
                Swal.fire({
                    icon: "success",
                    title: "Thành công!",
                    text: response.message || "Thêm nhân viên thành công",
                    confirmButtonText: "Xác nhận!",
                });
                $("#create-staff").modal("hide");
                loadStaff();
            })
            .fail(function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    showValidationErrors(errors, form);
                } else {
                    Swal.fire("Lỗi!", "Không thể thêm nhân viên.", "error");
                }
            })
            .always(function () {
                $submitBtn.prop("disabled", false).html(originalText);
            });
    }
});

/**
 * Edit staff - populate form and open modal
 */
$(document).on("click", ".edit-staff", function () {
    const btn = $(this);
    const form = $("#form_create_staff");

    $(".staff-modal-title").text("Cập nhật nhân viên");

    form.find('input[name="id"]').val(btn.data("id"));
    form.find('input[name="email"]').val(btn.data("email"));
    form.find('select[name="role"]').val(btn.data("role"));
    form.find('select[name="branch_id"]').val(btn.data("branch_id"));

    // Clear errors
    form.find(".invalid-feedback").hide().text("");
    form.find(".form-control, .form-select").removeClass("is-invalid");
});

/**
 * Add new staff button - reset form
 */
$(document).on("click", ".add-staff", function () {
    const form = $("#form_create_staff");
    form[0].reset();
    form.find('input[name="id"]').val("");
    $(".staff-modal-title").text("Thêm nhân viên");
    form.find(".invalid-feedback").hide().text("");
    form.find(".form-control, .form-select").removeClass("is-invalid");
});

/**
 * Reset modal on close
 */
$("#create-staff").on("hidden.bs.modal", function () {
    const form = $("#form_create_staff");
    form.find(".invalid-feedback").hide().text("");
    form.find(".form-control, .form-select").removeClass("is-invalid");
});

/**
 * Delete staff
 */
$(document).on("click", ".delete-staff", function (e) {
    e.preventDefault();
    const id = $(this).data("id");

    confirmDelete({
        text: "Nhân viên sẽ bị xóa vĩnh viễn!",
        onConfirm: () => {
            $.ajax({
                url: window.routes.staffDestroy.replace(":staff", id),
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content",
                    ),
                },
            })
                .done(function (response) {
                    Swal.fire({
                        icon: "success",
                        title: "Thành công!",
                        text: response.message || "Xóa nhân viên thành công",
                        confirmButtonText: "Xác nhận!",
                    });
                    loadStaff();
                })
                .fail(function (xhr) {
                    Swal.fire("Lỗi!", "Không thể xóa nhân viên.", "error");
                });
        },
    });
});

/**
 * Toggle staff status
 */
$(document).on("click", ".toggle-staff-status", function (e) {
    e.preventDefault();
    const btn = $(this);
    const id = btn.data("id");

    $.ajax({
        url: window.routes.staffToggleStatus.replace(":staff", id),
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    })
        .done(function (response) {
            const newStatus = response.data.is_actived;
            const badge = btn.find(".badge");
            const icon = btn.find("i");

            if (newStatus) {
                badge
                    .removeClass("bg-danger")
                    .addClass("bg-success")
                    .text("Hoạt động");
                icon.removeClass("fa-toggle-off").addClass("fa-toggle-on");
                btn.data("active", 1);
            } else {
                badge
                    .removeClass("bg-success")
                    .addClass("bg-danger")
                    .text("Ngừng hoạt động");
                icon.removeClass("fa-toggle-on").addClass("fa-toggle-off");
                btn.data("active", 0);
            }

            Swal.fire({
                icon: "success",
                title: "Thành công!",
                text: response.message || "Cập nhật trạng thái thành công",
                timer: 2000,
            });
        })
        .fail(function (xhr) {
            Swal.fire("Lỗi!", "Không thể cập nhật trạng thái.", "error");
        });
});

/**
 * Handle pagination
 */
$(document).on("click", ".pagination-container a", function (e) {
    e.preventDefault();
    const url = $(this).attr("href");
    loadStaff(url);
});

// Preserve search filters when pagination
$(document).on("click", ".pagination-container a", function (e) {
    e.preventDefault();
    let url = $(this).attr("href");

    // Add current search filters to pagination URL
    const searchParams = new URLSearchParams();
    $("#form_search_part input, #form_search_part select").each(function () {
        const value = $(this).val();
        if (value) {
            searchParams.append($(this).attr("name"), value);
        }
    });

    const separator = url.includes("?") ? "&" : "?";
    if (searchParams.toString()) {
        url = url + separator + searchParams.toString();
    }

    loadStaff(url);
});
