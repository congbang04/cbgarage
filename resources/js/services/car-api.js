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
