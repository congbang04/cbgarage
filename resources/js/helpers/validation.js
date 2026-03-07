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
