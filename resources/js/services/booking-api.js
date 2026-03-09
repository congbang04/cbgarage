export function getSlots(branchId, serviceId, date) {
    return $.ajax({
        url: window.routes.slotsIndex,
        method: "GET",
        data: {
            branch_id: branchId,
            service_id: serviceId,
            date: date,
        },
    });
}
export function createBooking(
    lockId,
    serviceId,
    contactName,
    contactPhone,
    startTime,
    carId,
    description = null,
) {
    return $.ajax({
        url: window.routes.createBooking,
        method: "POST",
        data: {
            lock_id: lockId,
            service_id: serviceId,
            contact_name: contactName,
            contact_phone: contactPhone,
            start_time: startTime,
            car_id: carId,
            description: description,
        },
    });
}
export function createLock(branchId, serviceId, startTime) {
    return $.ajax({
        url: window.routes.createLock,
        method: "POST",
        data: {
            branch_id: branchId,
            service_id: serviceId,
            start_time: startTime,
        },
    });
}
export function releaseLock(lockId) {
    return $.ajax({
        url: window.routes.releaseLock,
        method: "POST",
        data: {
            lock_id: lockId,
        },
    });
}
