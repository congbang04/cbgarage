export function updateCustomer(data) {
    return $.ajax({
        url: window.routes.customerUpdated,
        method: "PUT",
        data: data,
    });
}
