import {
    createCar,
    deleteCar,
    updateCar,
    carOrders,
} from "../services/car-api";
import { clearErrors, showValidationErrors } from "../helpers/validation";
import { reloadDOM, resetModal } from "../helpers/dom";
import { confirmDelete } from "../helpers/sweetalert";
$(document).ready(function () {
    resetModal("#updateCarModal", "#form_update_car");
    resetModal("#addCarModal", "#form_create_car");
    $("#create_car_preview").attr("src", "").addClass("d-none");
    $("#create_car_image").val("");
    loadCarOrders();
});
function loadCarOrders(url = window.routes.carOrders) {
    const segments = window.location.pathname.split("/");
    const carId = segments[2];

    if (!carId) return;
    console.log("carId", carId);

    carOrders(carId, url)
        .done(function (res) {
            $("#table-data").html(res);
        })
        .fail(function (xhr) {
            console.log(xhr.responseText);
        });
}

$(document).on("click", ".pagination-container a", function (e) {
    e.preventDefault();
    const url = $(this).attr("href");
    loadCarOrders(url);
});

$("#form_create_car").on("submit", function (e) {
    e.preventDefault();

    let form = $(this);
    let formData = new FormData(form[0]);

    clearErrors(form);

    createCar(formData)
        .done(function (res) {
            Swal.fire("Thành công!", res.message, "success");

            $("#form_create_car")[0].reset();

            $("#create_car_preview").attr("src", "").addClass("d-none");
            $("#create_car_image").val("");

            $(".modal").modal("hide");

            reloadDOM("#car_list");
        })
        .fail(function (xhr) {
            if (xhr.status === 422) {
                showValidationErrors(xhr.responseJSON.errors, form);
            } else {
                Swal.fire("Lỗi!", "Không thể thêm xe.", "error");
            }
        });
});

$(document).on("click", ".btn-delete-car", function () {
    const carId = $(this).data("id");
    confirmDelete({
        text: "Xe sẽ bị xóa vĩnh viễn!",
        onConfirm: () => deleteCar(carId),
        reload: () => reloadDOM("#car_list"),
    });
});

$("#form_update_car").on("submit", function (e) {
    e.preventDefault();

    let form = $(this);
    let formData = new FormData(form[0]);
    let carId = $("#car_id").val();

    clearErrors(form);

    updateCar(carId, formData)
        .done(function (res) {
            Swal.fire("Thành công!", res.message, "success");

            $("#form_update_car")[0].reset();

            $(".modal").modal("hide");

            reloadDOM("#car_detail_header");
        })
        .fail(function (xhr) {
            if (xhr.status === 422) {
                showValidationErrors(xhr.responseJSON.errors, form);
            } else {
                Swal.fire("Lỗi!", "Không thể cập nhật xe.", "error");
            }
        });
});
