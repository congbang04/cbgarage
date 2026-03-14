import { createBooking } from "../../services/booking-api.js";
import { showValidationErrors } from "../../helpers/validation.js";
import { initRealtimeSlots } from "./booking-realtime.js";
import { loadSlots } from "./booking-core.js";
import { getBookingData } from "./booking-state.js";
export function initBooking() {
    $(document).on("change", "input[name='branch_id']", function () {
        const branchId = $(this).val();
        localStorage.setItem("booking_branch_id", branchId);

        $("input[name='branch_id']")
            .closest(".card")
            .removeClass("border-primary bg-primary-subtle");

        $(this).closest(".card").addClass("border-primary bg-primary-subtle");
        loadSlots();
        $("html, body").animate(
            {
                scrollTop: $("#service-section").offset().top - 80,
            },
            400,
        );
    });
    $(document).on("change", "input[name='service_id']", function () {
        const serviceId = $(this).val();
        localStorage.setItem("booking_service_id", serviceId);

        $(".service-card").removeClass("selected");

        $(this).closest(".service-card").addClass("selected");

        loadSlots();
        $("html, body").animate(
            {
                scrollTop: $("#time-section").offset().top - 80,
            },
            400,
        );
    });
    $(document).on("change", "input[name='date']", function () {
        const date = $(this).val();
        localStorage.setItem("booking_date", date);

        initRealtimeSlots();
    });
    $("#booking-form").one("submit", function (e) {
        e.preventDefault();
        const form = $(this);
        const submitButton = form.find("button[type='submit']");
        const originalText = submitButton.html();
        submitButton.prop("disabled", true).html("Đang đặt...");

        const { serviceId } = getBookingData();
        const contactName = form.find("[name='contact_name']").val();
        const contactPhone = form.find("[name='contact_phone']").val();
        const startTime = form.find("[name='start_time']").val();
        const lockId = form.find("[name='lock_id']").val();
        const description = form.find("[name='description']").val();

        createBooking(
            lockId,
            serviceId,
            contactName,
            contactPhone,
            startTime,
            $("select[name='car_id']").val(),
            description,
        )
            .done(function (res) {
                console.log(res);
                localStorage.clear();
                Swal.fire({
                    icon: "success",
                    title: "Đặt lịch thành công!",
                    didClose: () => {
                        window.location.href = "/booking";
                    },
                });
            })
            .fail(function (xhr) {
                if (xhr.status === 422) {
                    showValidationErrors(xhr.responseJSON.errors, form);
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Rất tiếc...",
                        text:
                            xhr.responseJSON.message ??
                            "Có lỗi xảy ra, vui lòng thử lại.",
                        didClose: () => {
                            $(this).prop("disabled", false).html(originalText);
                        },
                    });
                }
            })
            .always(function () {
                submitButton.prop("disabled", false).html(originalText);
            });
    });
}
