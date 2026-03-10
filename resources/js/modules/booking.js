import { getSlots, createBooking } from "../services/booking-api";
import { showValidationErrors } from "../helpers/validation";
import { initRealtimeSlots } from "./booking-realtime.js";

$(document).ready(function () {
    const savedLockId = localStorage.getItem("booking_lock_id");
    const savedStartTime = localStorage.getItem("booking_start_time");

    if (savedLockId && savedStartTime) {
        currentLockId = savedLockId;
        $("#lock-id").val(savedLockId);
        $("#start-time").val(savedStartTime);
    }

    const branchId = localStorage.getItem("booking_branch_id");
    const serviceId = localStorage.getItem("booking_service_id");
    const date = localStorage.getItem("booking_date");

    // restore branch
    if (branchId) {
        const $branch = $(`input[name="branch_id"][value="${branchId}"]`);
        $branch.prop("checked", true);

        $branch.closest(".card").addClass("border-primary bg-primary-subtle");
    }

    // restore service
    if (serviceId) {
        const $service = $(`input[name="service_id"][value="${serviceId}"]`);
        $service.prop("checked", true);

        $service.closest(".service-card").addClass("selected");
    }

    // restore date
    if (date) {
        $("input[name='date']").val(date);
    }

    // 🔥 gọi sau khi restore xong
    initRealtimeSlots();
});
let currentLockId = null;
export function loadSlots() {
    const { branchId, serviceId, date } = getBookingData();
    console.log(branchId, serviceId, date);

    if (!branchId || !serviceId || !date) {
        if ($("#slot-warning").length === 0) {
            $("#date-input").after(
                '<p id="slot-warning" class="fs-3 text-center text-warning fw-medium">Vui lòng chọn chi nhánh và dịch vụ để hiện khung giờ.</p>',
            );
        }
        return;
    }
    $("#slot-warning").remove();

    getSlots(branchId, serviceId, date)
        .done(function (resHtml) {
            $("#slot-list").html(resHtml);
        })
        .fail(function () {
            alert("Có lỗi xảy ra khi tải dữ liệu khung giờ!");
        });
}
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

$(document).on("submit", "#booking-form", function (e) {
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
export function getBookingData() {
    return {
        branchId: $("input[name='branch_id']:checked").val(),
        serviceId: $("input[name='service_id']:checked").val(),
        date: $("input[name='date']").val(),
    };
}
