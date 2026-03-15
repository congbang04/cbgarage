export function customerSearch(phone) {
    return $.ajax({
        url: window.routes.customerSearch,
        method: "GET",
        data: { phone: phone },
    });
}
export function receptionStore(data) {
    return $.ajax({
        url: window.routes.receptionStore,
        method: "POST",
        data: data,
    });
}
export function tableList(url = window.routes.tableList) {
    return $.ajax({
        url: url,
        method: "GET",
    });
}
