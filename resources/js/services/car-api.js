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
