export function updateCustomer(data) {
    return $.ajax({
        url: window.routes.customerUpdated,
        method: "POST",
        data: data,
        processData: false,
        contentType: false,
    });
}
