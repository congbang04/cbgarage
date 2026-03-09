import {
    getSlots,
    createBooking,
    createLock,
    releaseLock,
} from "../services/booking-api";
import { showValidationErrors } from "../helpers/validation";

$(document).ready(function () {
    loadSlots();
    $("input[name='date']").on("change", loadSlots);
});
let currentLockId = null;
let timeSlot = null;
function loadSlots() {
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
function getBookingData() {
    return {
        branchId: $("input[name='branch_id']:checked").val(),
        serviceId: $("input[name='service_id']:checked").val(),
        date: $("input[name='date']").val(),
    };
}
$(document).on("click", "input[name='slot_time']", function () {
    releaseCurrentLock();

    const { branchId, serviceId, date } = getBookingData();

    timeSlot = $(this).data("time");
    const startTime = `${date} ${timeSlot}`;

    $("#start-time").val(startTime);

    console.log("Selected slot:", startTime);
    console.log(branchId, serviceId, date, startTime);

    createLock(branchId, serviceId, startTime)
        .done(function (res) {
            console.log(res);

            currentLockId = res.id;

            Swal.fire({
                icon: "success",
                title: "Đã giữ chỗ 5 phút",
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
        });
});
function releaseCurrentLock() {
    if (!currentLockId) return;

    releaseLock(currentLockId)
        .done(function () {
            console.log("Lock released");
            currentLockId = null;
        })
        .fail(function () {
            console.log("Release lock failed");
        });
}
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
    const description = form.find("[name='description']").val();

    createBooking(
        currentLockId,
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
