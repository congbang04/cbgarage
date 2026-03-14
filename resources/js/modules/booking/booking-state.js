export let currentLockId = null;
export let currentStartTime = null;

export function setCurrentLock(id, startTime) {
    currentLockId = id;
    currentStartTime = startTime;
    localStorage.setItem("booking_lock_id", id);
    localStorage.setItem("booking_start_time", startTime);
}

export function clearCurrentLock() {
    currentLockId = null;
    currentStartTime = null;
    localStorage.removeItem("booking_lock_id");
    localStorage.removeItem("booking_start_time");
}

export function restoreFromLocalStorage() {
    const savedId = localStorage.getItem("booking_lock_id");
    const savedTime = localStorage.getItem("booking_start_time");
    if (savedId && savedTime) {
        currentLockId = savedId;
        currentStartTime = savedTime;
    }
    return { currentLockId, currentStartTime };
}
export function getBookingData() {
    return {
        branchId: $("input[name='branch_id']:checked").val(),
        serviceId: $("input[name='service_id']:checked").val(),
        date: $("input[name='date']").val(),
    };
}
