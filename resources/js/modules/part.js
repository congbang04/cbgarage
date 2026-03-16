import {
    partList,
    partStore,
    destroyPart,
    updatePart,
} from "../services/part-api";
import { confirmDelete } from "../helpers/sweetalert";
import { showValidationErrors } from "../helpers/validation";

$(document).ready(function () {
    loadParts();
});

function loadParts(url = window.routes.partList) {
    partList(url)
        .done(function (res) {
            $("#table-part").html(res);
        })
        .fail(function (xhr) {
            console.log(xhr.responseText);
        });
}

// Handle search form submission
$(document).on("submit", "#form_search_part", function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    const params = new URLSearchParams(formData);
    const searchUrl = window.routes.partList + "?" + params.toString();
    loadParts(searchUrl);
});
$(document).on("click", "#btn_reset_search", function () {
    const form = $("#form_search_part");

    form.find("input, select").val("");
    setTimeout(() => {
        form.trigger("submit");
    }, 50);
});
$(document).on("submit", "#form_create_part", function (e) {
    e.preventDefault();
    const form = $(this);
    const formData = new FormData(this);
    const id = form.find("input[name='id']").val();

    if (id) {
        updatePart(id, formData)
            .done(function (response) {
                Swal.fire({
                    icon: "success",
                    title: "Cập nhật thành công!",
                    text: response.message,
                    confirmButtonText: "Xác nhận!",
                });
                $("#create-part").modal("hide");
                loadParts();
            })
            .fail(function (xhr) {
                if (xhr.status === 422) {
                    showValidationErrors(xhr.responseJSON.errors, form);
                } else {
                    Swal.fire("Lỗi!", "Không thể cập nhật phụ tùng.", "error");
                }
            });
    } else {
        partStore(formData)
            .done(function (response) {
                Swal.fire({
                    icon: "success",
                    title: "Thành công!",
                    text: response.message,
                    confirmButtonText: "Xác nhận!",
                });
                $("#create-part").modal("hide");
                loadParts();
            })
            .fail(function (xhr) {
                if (xhr.status === 422) {
                    showValidationErrors(xhr.responseJSON.errors, form);
                } else {
                    Swal.fire("Lỗi!", "Không thể thêm phụ tùng.", "error");
                }
            });
    }
});

$(document).on("click", ".delete-part", function () {
    const id = $(this).data("id");
    confirmDelete({
        text: "Phụ tùng sẽ bị xóa vĩnh viễn!",
        onConfirm: () => destroyPart(id),
        reload: () => loadParts(),
    });
});

$(document).on("click", ".edit-part", function () {
    const btn = $(this);
    $(".part-modal-title").text("Cập nhật phụ tùng");

    $("#create-part input[name='id']").val(btn.data("id"));
    $("#create-part input[name='sku']").val(btn.data("sku"));
    $("#create-part input[name='name']").val(btn.data("name"));
    $("#create-part input[name='price_sell']").val(btn.data("price_sell"));
    $("#create-part input[name='unit']").val(btn.data("unit"));
});

$(document).on("click", ".add-part", function () {
    $("#form_create_part")[0].reset();
    $("#create-part input[name='id']").val("");
    $(".part-modal-title").text("Thêm phụ tùng");
});

$(document).on("click", ".pagination-container a", function (e) {
    e.preventDefault();
    const url = $(this).attr("href");
    loadParts(url);
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

    loadParts(url);
});
