import { createLock, releaseLock } from "../../services/booking-api.js";
import { showValidationErrors } from "../../helpers/validation.js";
import { loadSlots } from "./booking-core.js";
import { getBookingData } from "./booking-state.js";
import { setCurrentLock } from "./booking-state.js";
import { restoreFromLocalStorage } from "./booking-state.js";
export function initBookingLock() {
    let timeSlot = null;
    let { currentLockId } = restoreFromLocalStorage();

    $(document).on("click", "input[name='slot_time']", function () {
        const newTime = $(this).data("time");

        if (timeSlot === newTime) {
            return;
        }
        releaseCurrentLock(currentLockId);

        const { branchId, serviceId, date } = getBookingData();

        timeSlot = $(this).data("time");
        const startTime = `${date} ${timeSlot}`;

        $("#start-time").val(startTime);

        createLock(branchId, serviceId, startTime)
            .done(function (res) {
                $("#lock-id").val(res.id);
                currentLockId = res.id;
                setCurrentLock(res.id, startTime);

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
    function releaseCurrentLock(currentLockId) {
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
}
