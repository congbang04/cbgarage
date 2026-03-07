import { clearErrors } from "./validation";
export function reloadDOM(selector) {
    $(selector).load(window.location.href + " " + selector + " > *");
}
export function resetModal(modalSelector, formSelector) {
    $(modalSelector).on("hidden.bs.modal", function () {
        let form = $(formSelector);

        form[0].reset();

        clearErrors(form);
    });
}
