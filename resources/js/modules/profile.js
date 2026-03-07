import { updateCustomer } from "../services/customer-api";
import { clearErrors, showValidationErrors } from "../helpers/validation";
import { reloadDOM, resetModal } from "../helpers/dom";

$(document).ready(function () {
    resetModal("#updateProfileModal", "#customer_update_form");
});
$(document).on("submit", "#customer_update_form", function (e) {
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
});
