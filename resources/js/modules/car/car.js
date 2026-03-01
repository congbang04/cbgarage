import { createCar, deleteCar } from "../../services/car-api";

$(document).ready(function () {
    $("#form_create_car").on("submit", function (e) {
        e.preventDefault();

        let form = $(this);
        let formData = form.serialize();

        clearErrors();

        createCar(formData)
            .done(function (res) {
                Swal.fire("Thành công!", res.message, "success");

                $("#form_create_car")[0].reset();

                $(".modal").modal("hide");

                loadCarList();
            })
            .fail(function (xhr) {
                if (xhr.status === 422) {
                    showValidationErrors(xhr.responseJSON.errors);
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
                        loadCarList();
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
    /*
    ===============================
    HELPER FUNCTIONS
    ===============================
    */

    function clearErrors() {
        $(".text-danger").remove();
        $(".is-invalid").removeClass("is-invalid");
    }

    function showValidationErrors(errors) {
        $.each(errors, function (key, messages) {
            let input = $("[name='" + key + "']");

            input.addClass("is-invalid");

            input.after(`<div class="text-danger">${messages[0]}</div>`);
        });
    }
    function loadCarList() {
        $("#car_list").load(window.location.href + " #car_list > *");
    }
});
