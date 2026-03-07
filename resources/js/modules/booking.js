import { getSlots } from "../services/booking-api";

$(document).ready(function () {
    loadSlots();

    // khi đổi ngày
    $("input[name='date']").on("change", loadSlots);
});

function loadSlots() {
    const selectedDate = $("input[name='date']").val();
    const selectedServiceId = $("input[name='service_id']:checked").val();
    const selectedBranchId = $("input[name='branch_id']:checked").val();
    if (!selectedDate || !selectedServiceId || !selectedBranchId) {
        $("#slot-list").html(
            '<div class="text-muted">Vui lòng chọn chi nhánh và dịch vụ</div>',
        );
        return;
    }
    getSlots(selectedDate, selectedServiceId, selectedBranchId)
        .done(function (resHtml) {
            $("#slot-list").html(resHtml);
        })
        .fail(function () {
            alert("Có lỗi xảy ra khi tải dữ liệu khung giờ!");
        });
}

$(document).on("change", "input[name='branch_id']", function () {
    $("input[name='branch_id']")
        .closest(".card")
        .removeClass("border-primary bg-primary-subtle");

    $(this).closest(".card").addClass("border-primary bg-primary-subtle");
    loadSlots();
});
$(document).on("change", ".service-card input[type='checkbox']", function () {
    $(this).closest(".service-card").toggleClass("selected", this.checked);
    loadSlots();
});
