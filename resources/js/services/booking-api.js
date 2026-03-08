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
