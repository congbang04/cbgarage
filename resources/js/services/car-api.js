export function createCar(data) {
    return $.ajax({
        url: window.routes.carStore,
        method: "POST",
        data: data,
    });
}
export function deleteCar(carId) {
    return $.ajax({
        url: window.routes.carDestroy.replace(":id", carId),
        method: "DELETE",
    });
}
export function updateCar(carId, data) {
    return $.ajax({
        url: window.routes.carUpdate.replace(":id", carId),
        method: "PUT",
        data: data,
    });
}
export function carOrders(carId, url = window.routes.carOrders) {
    return $.ajax({
        url: url.replace(":id", carId),
        method: "GET",
    });
}
export function getCarQuotationItems(orderId) {
    return $.ajax({
        url: window.routes.carQuotationItems.replace(":orderId", orderId),
        type: "GET",
    });
}
