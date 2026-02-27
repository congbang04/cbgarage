import { updateCustomer } from "../../services/customer-api";

$(document).on("submit", "#customer-update-form", function (e) {
    e.preventDefault();

    const form = $(this);

    const data = {
        name: form.find("#name").val(),
        phone: form.find("#phone").val(),
        address: form.find("#address").val(),
    };

    updateCustomer(data)
        .done(function (response) {
            Swal.fire({
                icon: "success",
                title: "Thành công!",
                text: response.message,
                confirmButtonText: "Xác nhận!",
            });
            $("#updateProfileModal").modal("hide");

            $("#profileSidebar").load(
                window.location.href + " #profileSidebar > *",
            );
        })
        .fail(function (xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;

                $(".text-danger").remove();
                $(".is-invalid").removeClass("is-invalid");

                $.each(errors, function (key, messages) {
                    const input = $("#" + key);

                    input.addClass("is-invalid");

                    input.after(
                        `<div class="text-danger">${messages[0]}</div>`,
                    );
                });
            } else {
                alert("Lỗi hệ thống");
            }
        });
});
