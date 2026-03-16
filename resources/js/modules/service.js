import {
    serviceList,
    serviceStore,
    destroyService,
    updateService,
} from "../services/service-api";
import { confirmDelete } from "../helpers/sweetalert";
import { showValidationErrors } from "../helpers/validation";

$(document).ready(function () {
    loadServices();
});
function loadServices(url = window.routes.serviceList) {
    serviceList(url)
        .done(function (res) {
            $("#table-service").html(res);
        })
        .fail(function (xhr) {
            console.log(xhr.responseText);
        });
}
$(document).on("submit", "#form_create_service", function (e) {
    e.preventDefault();
    const form = $(this);
    const formData = new FormData(this);
    const id = form.find("input[name='id']").val();
    if (id) {
        updateService(id, formData)
            .done(function (response) {
                Swal.fire({
                    icon: "success",
                    title: "Cập nhật thành công!",
                    text: response.message,
                    confirmButtonText: "Xác nhận!",
                });
                $("#create-service").modal("hide");
                loadServices();
            })
            .fail(function (xhr) {
                if (xhr.status === 422) {
                    showValidationErrors(xhr.responseJSON.errors, form);
                } else {
                    Swal.fire("Lỗi!", "Không thể cập nhật dịch vụ.", "error");
                }
            });
    } else {
        serviceStore(formData)
            .done(function (response) {
                Swal.fire({
                    icon: "success",
                    title: "Thành công!",
                    text: response.message,
                    confirmButtonText: "Xác nhận!",
                });
                $("#create-service").modal("hide");
                loadServices();
            })
            .fail(function (xhr) {
                if (xhr.status === 422) {
                    showValidationErrors(xhr.responseJSON.errors, form);
                } else {
                    Swal.fire("Lỗi!", "Không thể thêm dịch vụ.", "error");
                }
            });
    }
});
$(document).on("change", "#service_icon", function () {
    const file = this.files[0];
    const $preview = $("#icon_preview");
    const $container = $(".service-icon-preview");

    if (!file) {
        $preview.hide().attr("src", "");
        $container.removeClass("has-image");
        return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
        $preview.attr("src", e.target.result).fadeIn(300);
        $container.addClass("has-image");
    };

    reader.readAsDataURL(file);
});
$("#create-service").on("hidden.bs.modal", function () {
    $("#icon_preview").hide().attr("src", "");
    $("#service_icon").val("");
    $(".service-icon-preview").removeClass("has-image");
});
$(document).on("click", ".delete-service", function () {
    const id = $(this).data("id");
    confirmDelete({
        text: "Xe sẽ bị xóa vĩnh viễn!",
        onConfirm: () => destroyService(id),
        reload: () => loadServices(),
    });
});
$(document).on("click", ".edit-service", function () {
    const id = $(this).data("id");
    $("#create-service").find("input[name='id']").val(id);
});
$(document).on("click", ".edit-service", function () {
    const btn = $(this);
    $(".service-modal-title").text("Cập nhật dịch vụ");

    $("#create-service input[name='id']").val(btn.data("id"));
    $("#create-service input[name='name']").val(btn.data("name"));
    $("#create-service input[name='price']").val(btn.data("price"));
    $("#create-service input[name='estimated_duration']").val(
        btn.data("estimated_duration"),
    );
    $("#create-service textarea[name='description']").val(
        btn.data("description"),
    );
    const icon = btn.data("icon");

    if (icon) {
        $("#icon_preview").attr("src", icon).show();
    }
});
$(document).on("click", ".add-service", function () {
    $("#form_create_service")[0].reset();
    $("#create-service input[name='id']").val("");
    $(".service-modal-title").text("Thêm dịch vụ");
    $("#icon_preview").attr("src", "").hide();
    $("#service_icon").val("");
});
