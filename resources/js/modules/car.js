import { createCar, deleteCar, updateCar } from "../services/car-api";
import { clearErrors, showValidationErrors } from "../helpers/validation";
import { reloadDOM, resetModal } from "../helpers/dom";

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

    Swal.fire({
        title: "Bạn chắc chắn?",
        text: "Xe sẽ bị xóa vĩnh viễn!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Xóa",
        cancelButtonText: "Hủy",
    }).then((result) => {
        if (result.isConfirmed) {
            deleteCar(carId)
                .done(function (res) {
                    Swal.fire("Đã xóa!", res.message, "success");
                    reloadDOM("#car_list");
                })
                .fail(function (xhr) {
                    if (xhr.status === 422) {
                        showValidationErrors(xhr.responseJSON.errors);
                    } else {
                        Swal.fire("Lỗi!", "Không thể xóa xe.", "error");
                    }
                });
        }
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
