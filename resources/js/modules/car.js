import { createCar, deleteCar, updateCar } from "../services/car-api";
import { clearErrors, showValidationErrors } from "../helpers/validation";
import { reloadDOM, resetModal } from "../helpers/dom";
import { confirmDelete } from "../helpers/sweetalert";
$(document).ready(function () {
    resetModal("#updateCarModal", "#form_update_car");
    resetModal("#addCarModal", "#form_create_car");
});
$("#form_create_car").on("submit", function (e) {
    e.preventDefault();

    let form = $(this);
    let formData = form.serialize();

    clearErrors(form);

    createCar(formData)
        .done(function (res) {
            Swal.fire("Thành công!", res.message, "success");

            $("#form_create_car")[0].reset();

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
    let formData = form.serialize();
    let carId = $("#car_id").val();

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
