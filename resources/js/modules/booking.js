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
        if ($("#slot-warning").length === 0) {
            $("#date-input").after(
                '<p id="slot-warning" class="fs-3 text-center text-warning fw-medium">Vui lòng chọn chi nhánh và dịch vụ để hiện khung giờ.</p>',
            );
        }

        return;
    }

    $("#slot-warning").remove();
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
    $("html, body").animate(
        {
            scrollTop: $("#service-section").offset().top - 80,
        },
        400,
    );
});
$(document).on("change", "input[name='service_id']", function () {
    $(".service-card").removeClass("selected");

    $(this).closest(".service-card").addClass("selected");

    loadSlots();
    $("html, body").animate(
        {
            scrollTop: $("#time-section").offset().top - 80,
        },
        400,
    );
});
