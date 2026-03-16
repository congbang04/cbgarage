import {
    branchList,
    branchStore,
    destroyBranch,
    updateBranch,
    toggleBranchStatus,
} from "../services/branch-api";
import { confirmDelete } from "../helpers/sweetalert";
import { showValidationErrors } from "../helpers/validation";

$(document).ready(function () {
    loadBranches();
});

function loadBranches(url = window.routes.branchList) {
    branchList(url)
        .done(function (res) {
            $("#branch-cards-container").html(res);
        })
        .fail(function (xhr) {
            console.log(xhr.responseText);
        });
}

// Handle search form submission
$(document).on("submit", "#form_search_branch", function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    const params = new URLSearchParams(formData);
    const searchUrl = window.routes.branchList + "?" + params.toString();
    loadBranches(searchUrl);
});

// Reset search filters
$(document).on("click", "#btn_reset_search", function () {
    const form = $("#form_search_branch");

    form.find("input, select").val("");
    setTimeout(() => {
        form.trigger("submit");
    }, 50);
});

// Handle form submission (create/update)
$(document).on("submit", "#form_create_branch", function (e) {
    e.preventDefault();
    const form = $(this);
    const formData = new FormData(this);
    const id = form.find("input[name='id']").val();

    if (id) {
        updateBranch(id, formData)
            .done(function (response) {
                Swal.fire({
                    icon: "success",
                    title: "Cập nhật thành công!",
                    text: response.message,
                    confirmButtonText: "Xác nhận!",
                });
                $("#create-branch").modal("hide");
                loadBranches();
            })
            .fail(function (xhr) {
                if (xhr.status === 422) {
                    showValidationErrors(xhr.responseJSON.errors, form);
                } else {
                    Swal.fire("Lỗi!", "Không thể cập nhật chi nhánh.", "error");
                }
            });
    } else {
        branchStore(formData)
            .done(function (response) {
                Swal.fire({
                    icon: "success",
                    title: "Thành công!",
                    text: response.message,
                    confirmButtonText: "Xác nhận!",
                });
                $("#create-branch").modal("hide");
                loadBranches();
            })
            .fail(function (xhr) {
                if (xhr.status === 422) {
                    showValidationErrors(xhr.responseJSON.errors, form);
                } else {
                    Swal.fire("Lỗi!", "Không thể thêm chi nhánh.", "error");
                }
            });
    }
});

// Handle delete branch
$(document).on("click", ".delete-branch", function (e) {
    e.preventDefault();
    const id = $(this).data("id");
    confirmDelete({
        text: "Chi nhánh sẽ bị xóa vĩnh viễn!",
        onConfirm: () => destroyBranch(id),
        reload: () => loadBranches(),
    });
});

// Handle edit branch
$(document).on("click", ".edit-branch", function (e) {
    e.preventDefault();
    const btn = $(this);
    $(".branch-modal-title").text("Cập nhật chi nhánh");

    $("#create-branch input[name='id']").val(btn.data("id"));
    $("#create-branch input[name='name']").val(btn.data("name"));
    $("#create-branch input[name='address']").val(btn.data("address"));
    $("#create-branch input[name='phone']").val(btn.data("phone"));
    $("#create-branch input[name='coordinates']").val(btn.data("coordinates"));

    const isCentralWarehouse = btn.data("is_central_warehouse");
    $("#check-central-warehouse").prop("checked", isCentralWarehouse == 1);
});

// Handle add new branch button
$(document).on("click", ".add-branch", function () {
    $("#form_create_branch")[0].reset();
    $("#create-branch input[name='id']").val("");
    $("#check-central-warehouse").prop("checked", false);
    $(".branch-modal-title").text("Thêm chi nhánh");
});

// Handle pagination
$(document).on("click", ".pagination-container a", function (e) {
    e.preventDefault();
    let url = $(this).attr("href");

    // Add current search filters to pagination URL
    const searchParams = new URLSearchParams();
    $("#form_search_branch input, #form_search_branch select").each(
        function () {
            const value = $(this).val();
            if (value) {
                searchParams.append($(this).attr("name"), value);
            }
        },
    );

    const separator = url.includes("?") ? "&" : "?";
    if (searchParams.toString()) {
        url = url + separator + searchParams.toString();
    }

    loadBranches(url);
});

// Handle toggle branch status (enabled/disabled)
$(document).on("change", ".toggle-branch-status", function () {
    const branchId = $(this).data("id");
    const isActived = $(this).is(":checked");

    toggleBranchStatus(branchId, isActived)
        .done(function (response) {
            Swal.fire({
                icon: "success",
                title: "Thành công!",
                text: response.message,
                confirmButtonText: "Xác nhận!",
            });
            loadBranches();
        })
        .fail(function (xhr) {
            Swal.fire(
                "Lỗi!",
                "Không thể cập nhật trạng thái chi nhánh.",
                "error",
            );
            loadBranches();
        });
});
