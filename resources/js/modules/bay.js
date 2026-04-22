import BayService from "../services/BayService.js";

const BayModule = (() => {
    const bayService = new BayService();

    // Initialize module on DOM ready
    const init = () => {
        loadBays();
        attachEventHandlers();
    };

    // Load bays list via AJAX
    const loadBays = (url) => {
        const params = {
            name: $("#bay-search-name").val() || "",
            status: $("#bay-filter-status").val() || "",
            branch_id: $("#bay-filter-branch").val() || "",
        };

        bayService.getBayList(params, url, (response) => {
            $("#bay-cards-container").html(response);
            attachBayEventHandlers();
        });
    };

    // Attach event handlers for bay cards
    const attachBayEventHandlers = () => {
        // Edit bay
        $(".edit-bay")
            .off("click")
            .on("click", function () {
                const bayId = $(this).data("bay-id");
                editBay(bayId);
            });

        // Delete bay
        $(".delete-bay")
            .off("click")
            .on("click", function () {
                const bayId = $(this).data("bay-id");
                deleteBay(bayId);
            });
    };

    // Attach main event handlers
    const attachEventHandlers = () => {
        // Add bay button
        $(".add-bay")
            .off("click")
            .on("click", function () {
                resetBayForm();
                $("#createBayLabel").text("Thêm cầu nâng");
            });

        // Bay form submit
        $("#bay-form")
            .off("submit")
            .on("submit", function (e) {
                e.preventDefault();
                submitBayForm();
            });

        // Close modal on success
        $(document).on("hidden.bs.modal", "#create-bay", function () {
            resetBayForm();
        });

        attachBayEventHandlers();
    };

    // Reset bay form
    const resetBayForm = () => {
        $("#bay-form")[0].reset();
        $("#bay-id").val("");
        $("#bay-name").focus();
        $("#createBayLabel").text("Thêm cầu nâng");
        clearFormErrors();
    };

    // Clear form errors
    const clearFormErrors = () => {
        $("#bay-form .form-control").removeClass("is-invalid");
        $("#bay-form .form-select").removeClass("is-invalid");
        $("#bay-form .invalid-feedback").text("");
    };

    // Edit bay
    const editBay = (bayId) => {
        bayService.getBayDetail(bayId, (response) => {
            if (response.success) {
                const bay = response.bay;
                $("#bay-id").val(bay.id);
                $("#bay-name").val(bay.name);
                $("#bay-branch").val(bay.branch_id);
                $("#bay-type").val(bay.type);
                $("#bay-status").val(bay.status);
                $("#createBayLabel").text("Cập nhật cầu nâng");
                const modal = new bootstrap.Modal(
                    document.getElementById("create-bay"),
                );
                modal.show();
            }
        });
    };

    // Submit bay form
    const submitBayForm = () => {
        const bayId = $("#bay-id").val();
        const formData = {
            name: $("#bay-name").val(),
            branch_id: $("#bay-branch").val(),
            status: $("#bay-status").val(),
        };

        clearFormErrors();

        if (bayId) {
            // Update
            bayService.updateBay(bayId, formData, (response) => {
                if (response.success) {
                    const modal = bootstrap.Modal.getInstance(
                        document.getElementById("create-bay"),
                    );
                    modal.hide();
                    showToastSuccess(response.message);
                    loadBays();
                } else {
                    handleFormErrors(response.errors);
                }
            });
        } else {
            // Create
            bayService.createBay(formData, (response) => {
                if (response.success) {
                    const modal = bootstrap.Modal.getInstance(
                        document.getElementById("create-bay"),
                    );
                    modal.hide();
                    showToastSuccess(response.message);
                    loadBays();
                } else {
                    handleFormErrors(response.errors);
                }
            });
        }
    };

    // Delete bay
    const deleteBay = (bayId) => {
        Swal.fire({
            title: "Xác nhận xóa",
            text: "Bạn có chắc chắn muốn xóa cầu nâng này?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Xóa",
            cancelButtonText: "Hủy",
        }).then((result) => {
            if (result.isConfirmed) {
                bayService.deleteBay(bayId, (response) => {
                    if (response.success) {
                        showToastSuccess(response.message);
                        loadBays();
                    }
                });
            }
        });
    };

    // Handle form errors
    const handleFormErrors = (errors) => {
        if (errors) {
            $.each(errors, function (field, messages) {
                const $field = $(`#bay-${field}`);
                $field.addClass("is-invalid");
                $(`#${field}-error`).text(messages[0]);
            });
        }
    };

    // Show toast success
    const showToastSuccess = (message) => {
        Toast.fire({
            icon: "success",
            title: message,
        });
    };

    // Public API
    return {
        init: init,
        loadBays: loadBays,
    };
})();

// Handle search form submission
$(document).on("submit", "#form_search_bay", function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    const params = new URLSearchParams(formData);
    const searchUrl = window.routes.bayList + "?" + params.toString();
    console.log(searchUrl);

    BayModule.loadBays(searchUrl);
});

// Reset search filters
$(document).on("click", "#btn_reset_search", function () {
    const form = $("#form_search_bay");

    form.find("input, select").val("");
    setTimeout(() => {
        form.trigger("submit");
    }, 50);
});

// Handle pagination
$(document).on("click", ".pagination-container a", function (e) {
    e.preventDefault();
    let url = $(this).attr("href");
    BayModule.loadBays(url);
});

// Initialize when DOM is ready
$(document).ready(() => {
    BayModule.init();
});
