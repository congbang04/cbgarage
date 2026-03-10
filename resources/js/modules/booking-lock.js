import { createLock, releaseLock } from "../services/booking-api";
import { showValidationErrors } from "../helpers/validation";
import { getBookingData } from "./booking.js";
$(document).ready(function () {
    const savedStartTime = localStorage.getItem("booking_start_time");

    if (savedLockId && savedStartTime) {
        currentLockId = savedLockId;
    }
});
let currentLockId = null;
let timeSlot = null;

$(document).on("click", "input[name='slot_time']", function () {
    const newTime = $(this).data("time");

    if (timeSlot === newTime) {
        return;
    }
    releaseCurrentLock();

    const { branchId, serviceId, date } = getBookingData();

    timeSlot = $(this).data("time");
    const startTime = `${date} ${timeSlot}`;

    $("#start-time").val(startTime);

    createLock(branchId, serviceId, startTime)
        .done(function (res) {
            currentLockId = res.id;
            $("#lock-id").val(res.id);

            localStorage.setItem("booking_lock_id", res.id);
            localStorage.setItem("booking_start_time", startTime);

            Swal.fire({
                icon: "success",
                title: "Đang giữ khung giờ " + timeSlot + " trong 5 phút",
            });
            loadSlots();
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
