import { tableList, cancelBooking } from "../services/customer-booking-api.js";

window.customerBooking = {
    loadTable,
    init,
};

/**
 * Load booking table via AJAX
 * @param {Object} data - Filter parameters
 */
function loadTable(data = {}) {
    tableList(undefined, data)
        .done(function (html) {
            $("#booking-table").html(html);

            // Handle pagination links with AJAX
            $(document)
                .off("click", ".pagination a")
                .on("click", ".pagination a", function (e) {
                    e.preventDefault();
                    const url = $(this).attr("href");
                    const urlParams = new URLSearchParams(url.split("?")[1]);
                    const formData = $("#filter-form").serialize();
                    const pageParam = "page=" + urlParams.get("page");
                    const combinedData = formData
                        ? formData + "&" + pageParam
                        : pageParam;
                    loadTable(combinedData);
                });
        })
        .fail(function (error) {
            console.error("Error loading table:", error);
            $("#booking-table").html(
                `<div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Có lỗi khi tải danh sách. Vui lòng thử lại sau.
                </div>`,
            );
        });
}

/**
 * Initialize event listeners
 */
function init() {
    // Auto-load when filter changes (date or status)
    $(document).on("change", ".filter-change", function () {
        loadTable($("#filter-form").serialize());
    });

    // Reset button - clear form and reload
    $(document).on("reset", "#filter-form", function () {
        setTimeout(() => {
            loadTable();
        }, 0);
    });

    // Cancel booking button
    $(document).on("click", ".btn-cancel", function () {
        const $btn = $(this);
        const id = $btn.data("id");

        Swal.fire({
            title: "Bạn chắc chắn?",
            text: "Bạn muốn hủy lịch này? Hành động này không thể hoàn tác.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Xác nhận hủy",
            cancelButtonText: "Không",
            reverseButtons: true,
        }).then((result) => {
            if (!result.isConfirmed) return;
            cancelBooking(id)
                .done(function (response) {
                    Swal.fire({
                        icon: "success",
                        title: "Thành công",
                        text: response.message || "Đã hủy lịch đặt thành công!",
                        timer: 1500,
                        showConfirmButton: false,
                    });

                    setTimeout(() => {
                        loadTable();
                    }, 1500);
                })
                .fail(function (error) {
                    const errorMsg =
                        error.responseJSON?.message ||
                        "Có lỗi khi hủy lịch. Vui lòng thử lại.";

                    Swal.fire({
                        icon: "error",
                        title: "Lỗi",
                        text: errorMsg,
                    });

                    // Restore button
                    $btn.prop("disabled", false).html(originalText);
                });
        });
    });
}

/**
 * Show alert notification
 * @param {string} type - Alert type (success, danger, warning, info)
 * @param {string} message - Alert message
 */
function showAlert(type, message) {
    const alerts = {
        success: "success",
        danger: "danger",
        warning: "warning",
        info: "info",
    };

    const alertType = alerts[type] || "info";
    const iconMap = {
        success: "fa-check-circle",
        danger: "fa-exclamation-circle",
        warning: "fa-exclamation-triangle",
        info: "fa-info-circle",
    };

    const alertHtml = `
        <div class="alert alert-${alertType} alert-dismissible fade show" role="alert">
            <i class="fas ${iconMap[alertType]} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

    // Insert alert at the top of the container
    $("#booking-table").before(alertHtml);

    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        $(".alert")
            .not(".alert-dismiss-disabled")
            .fadeOut(300, function () {
                $(this).remove();
            });
    }, 5000);
}

$(document).ready(function () {
    init();
    loadTable();
});
