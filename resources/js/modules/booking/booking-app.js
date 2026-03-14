import { restoreFromLocalStorage } from "./booking-state";
import { startGlobalSlotChecker, initTooltipEvent } from "./booking-tooltip";
import { initBooking } from "./booking";
import { initBookingLock } from "./booking-lock";
import { initRealtimeSlots } from "./booking-realtime";
$(document).ready(function () {
    const { currentLockId, currentStartTime } = restoreFromLocalStorage();
    if (currentLockId && currentStartTime) {
        $("#lock-id").val(currentLockId);
        $("#start-time").val(currentStartTime);
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

    initRealtimeSlots();
    startGlobalSlotChecker();
    initBooking();
    initBookingLock();
    initTooltipEvent();
});
