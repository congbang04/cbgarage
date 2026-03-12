export function customerSearch(phone) {
    return $.ajax({
        url: window.routes.customerSearch,
        method: "GET",
        data: { phone: phone },
    });
}
