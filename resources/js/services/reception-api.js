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
export function tableList() {
    return $.ajax({
        url: window.routes.tableList,
        method: "GET",
    });
}
