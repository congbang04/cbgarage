export function confirmDelete({
    text = "Dữ liệu sẽ bị xóa vĩnh viễn!",
    onConfirm,
    successMessage = "Đã xóa thành công!",
    reload = null,
}) {
    Swal.fire({
        title: "Bạn chắc chắn?",
        text: text,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Xóa",
        cancelButtonText: "Hủy",
    }).then((result) => {
        if (!result.isConfirmed) return;

        onConfirm()
            .done(function (res) {
                Swal.fire("Đã xóa!", res.message ?? successMessage, "success");
                if (reload) reload();
            })
            .fail(function (xhr) {
                if (xhr.status === 422) {
                    showValidationErrors(xhr.responseJSON.errors);
                } else {
                    Swal.fire("Lỗi!", "Không thể xóa dữ liệu.", "error");
                }
            });
    });
}
