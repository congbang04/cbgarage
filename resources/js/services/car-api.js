export function createCar(data) {
    return $.ajax({
        url: window.routes.carStore,
        method: "POST",
        data: data,
    });
}
