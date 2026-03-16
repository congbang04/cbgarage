export function partList(url = window.routes.partList) {
    return $.ajax({
        url: url,
        method: "GET",
    });
}

export function partStore(data) {
    return $.ajax({
        url: window.routes.partStore,
        method: "POST",
        data: data,
        processData: false,
        contentType: false,
    });
}

export function destroyPart(part) {
    return $.ajax({
        url: window.routes.partDestroy.replace(":part", part),
        method: "DELETE",
    });
}

export function updatePart(part, data) {
    data.append("_method", "PUT");

    return $.ajax({
        url: window.routes.partUpdate.replace(":part", part),
        method: "POST",
        data: data,
        processData: false,
        contentType: false,
    });
}
