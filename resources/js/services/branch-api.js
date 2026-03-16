export function branchList(url = window.routes.branchList) {
    return $.ajax({
        url: url,
        method: "GET",
    });
}

export function branchStore(data) {
    return $.ajax({
        url: window.routes.branchStore,
        method: "POST",
        data: data,
        processData: false,
        contentType: false,
    });
}

export function destroyBranch(branch) {
    return $.ajax({
        url: window.routes.branchDestroy.replace(":branch", branch),
        method: "DELETE",
    });
}

export function updateBranch(branch, data) {
    data.append("_method", "PUT");

    return $.ajax({
        url: window.routes.branchUpdate.replace(":branch", branch),
        method: "POST",
        data: data,
        processData: false,
        contentType: false,
    });
}

export function toggleBranchStatus(branchId, isActived) {
    const data = new FormData();
    data.append("_method", "PUT");
    data.append("is_actived", isActived ? 1 : 0);

    return $.ajax({
        url: window.routes.branchUpdate.replace(":branch", branchId),
        method: "POST",
        data: data,
        processData: false,
        contentType: false,
    });
}
