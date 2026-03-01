import { createCar } from "../../services/car-api";

$(document).ready(function () {
    $("#form_create_car").on("submit", function (e) {
        e.preventDefault();

        let form = $(this);
        let formData = form.serialize();

        clearErrors();

        createCar(formData)
            .done(function (res) {
                alert("Thêm xe thành công");

                $("#form_create_car")[0].reset();

                $(".modal").modal("hide");

                loadCarList();
            })
            .fail(function (xhr) {
                if (xhr.status === 422) {
                    showValidationErrors(xhr.responseJSON.errors);
                } else {
                    alert("Lỗi hệ thống");
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
