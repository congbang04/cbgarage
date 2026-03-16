export function serviceList(url = window.routes.serviceList) {
    return $.ajax({
        url: url,
        method: "GET",
    });
}
export function serviceStore(data) {
    return $.ajax({
        url: window.routes.serviceStore,
        method: "POST",
        data: data,
        processData: false,
        contentType: false,
    });
}
export function destroyService(service) {
    return $.ajax({
        url: window.routes.serviceDestroy.replace(":service", service),
        method: "DELETE",
    });
}
export function updateService(service, data) {
    data.append("_method", "PUT");

    return $.ajax({
        url: window.routes.serviceUpdate.replace(":service", service),
        method: "POST",
        data: data,
        processData: false,
        contentType: false,
    });
}
