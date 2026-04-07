import { updateCustomer } from "../services/customer-api";
import { clearErrors, showValidationErrors } from "../helpers/validation";
import { reloadDOM, resetModal } from "../helpers/dom";

// Global variable to store active cropper instances
let activeCroppers = {};

$(document).ready(function () {
    resetModal("#updateProfileModal", "#customer_update_form");
});

// Handle customer image upload preview
$(document).on("change", "#customer_image", function () {
    const file = this.files[0];
    const $preview = $("#customer_preview");
    const $container = $("#customer_preview_container");

    if (!file) {
        $preview.hide().attr("src", "");
        $container.hide();
        return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
        $preview.attr("src", e.target.result).fadeIn(300);
        $container.show();
    };

    reader.readAsDataURL(file);
});

// Handle crop button click
$(document).on(
    "click",
    ".edit-image-btn[data-target='customer']",
    function (e) {
        e.preventDefault();
        const $preview = $("#customer_preview");
        const imageSrc = $preview.attr("src");

        if (!imageSrc) return;

        // Show crop section
        $("#customer_crop_section").show();
        $("#customer_crop_image").attr("src", imageSrc);

        // Initialize or reinitialize cropper
        if (activeCroppers["customer"]) {
            activeCroppers["customer"].destroy();
        }

        activeCroppers["customer"] = new Cropper(
            document.getElementById("customer_crop_image"),
            {
                aspectRatio: 1 / 1,
                viewMode: 1,
                autoCropArea: 0.8,
                responsive: true,
                restore: true,
                guides: true,
                center: true,
                highlight: true,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: true,
            },
        );
    },
);

// Zoom in
$(document).on("click", ".crop-zoom-in[data-target='customer']", function (e) {
    e.preventDefault();
    if (activeCroppers["customer"]) {
        activeCroppers["customer"].zoom(0.1);
    }
});

// Zoom out
$(document).on("click", ".crop-zoom-out[data-target='customer']", function (e) {
    e.preventDefault();
    if (activeCroppers["customer"]) {
        activeCroppers["customer"].zoom(-0.1);
    }
});

// Reset crop
$(document).on("click", ".crop-reset[data-target='customer']", function (e) {
    e.preventDefault();
    if (activeCroppers["customer"]) {
        activeCroppers["customer"].reset();
    }
});

// Confirm crop
$(document).on("click", ".crop-confirm[data-target='customer']", function (e) {
    e.preventDefault();
    const cropper = activeCroppers["customer"];

    if (cropper) {
        const canvas = cropper.getCroppedCanvas();
        const croppedImageData = canvas.toDataURL("image/jpeg", 0.9);

        // Update preview with cropped image
        $("#customer_preview").attr("src", croppedImageData);
        $("#customer_cropped_data").val(croppedImageData);

        // Hide crop section
        $("#customer_crop_section").hide();

        // Destroy cropper
        cropper.destroy();
        delete activeCroppers["customer"];
    }
});

// Cancel crop
$(document).on("click", ".crop-cancel[data-target='customer']", function (e) {
    e.preventDefault();
    const cropper = activeCroppers["customer"];

    if (cropper) {
        cropper.destroy();
        delete activeCroppers["customer"];
    }

    $("#customer_crop_section").hide();
});

$(document).on("submit", "#customer_update_form", function (e) {
    e.preventDefault();

    const form = $(this);
    const croppedData = $("#customer_cropped_data").val();

    // If cropped data exists, convert it to file
    if (croppedData) {
        fetch(croppedData)
            .then((res) => res.blob())
            .then((blob) => {
                const file = new File([blob], "customer-avatar.jpg", {
                    type: "image/jpeg",
                });

                const fileInput = document.getElementById("customer_image");
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;

                submitCustomerForm(form);
            });
    } else {
        submitCustomerForm(form);
    }
});

function submitCustomerForm(form) {
    const formData = new FormData(form[0]);

    clearErrors(form);

    updateCustomer(formData)
        .done(function (response) {
            Swal.fire({
                icon: "success",
                title: "Thành công!",
                text: response.message,
                confirmButtonText: "Xác nhận!",
            });
            $("#updateProfileModal").modal("hide");
            $("#customer_cropped_data").val("");
            reloadDOM("#profileSidebar");
        })
        .fail(function (xhr) {
            if (xhr.status === 422) {
                showValidationErrors(xhr.responseJSON.errors, form);
            } else {
                Swal.fire(
                    "Lỗi!",
                    "Không thể cập nhật thông tin cá nhân.",
                    "error",
                );
            }
        });
}

// Reset modal on close
$("#updateProfileModal").on("hidden.bs.modal", function () {
    $("#customer_image").val("");
    $("#customer_preview").hide().attr("src", "");
    $("#customer_preview_container").hide();
    $("#customer_crop_section").hide();
    $("#customer_cropped_data").val("");

    if (activeCroppers["customer"]) {
        activeCroppers["customer"].destroy();
        delete activeCroppers["customer"];
    }
});
