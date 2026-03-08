export function clearErrors(formElement = null) {
    let container = formElement ? $(formElement) : $(document);
    container.find(".text-danger").remove();
    container.find(".is-invalid").removeClass("is-invalid");
}

export function showValidationErrors(errors, formElement = null) {
    clearErrors(formElement);
    let container = formElement ? $(formElement) : $(document);
    $.each(errors, function (key, messages) {
        let input = container.find("[name='" + key + "']");
        input.addClass("is-invalid");
        input.after(`<div class="text-danger">${messages[0]}</div>`);
    });
}
export function handleAjaxError(
    xhr,
    formElement = null,
    button = null,
    originalText = null,
) {
    if (xhr.status === 422 && xhr.responseJSON?.errors) {
        showValidationErrors(xhr.responseJSON.errors, formElement);
        return;
    }

    let message =
        xhr.responseJSON?.message ?? "Có lỗi xảy ra, vui lòng thử lại.";

    Swal.fire({
        icon: "error",
        title: "Rất tiếc...",
        text: message,
        didClose: () => {
            if (button) {
                $(button).prop("disabled", false).html(originalText);
            }
        },
    });
}
