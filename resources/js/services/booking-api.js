export function getSlots(date, serviceId, branchId) {
    return $.ajax({
        url: window.routes.slotsIndex,
        method: "GET",
        data: {
            date: date,
            service_id: serviceId,
            branch_id: branchId,
        },
    });
}
