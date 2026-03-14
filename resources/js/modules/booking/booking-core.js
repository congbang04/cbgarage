import { getSlots } from "../../services/booking-api.js";
import { getBookingData } from "./booking-state.js";
export function loadSlots() {
    const { branchId, serviceId, date } = getBookingData();
    console.log(branchId, serviceId, date);

    if (!branchId || !serviceId || !date) {
        if ($("#slot-warning").length === 0) {
            $("#date-input").after(
                '<p id="slot-warning" class="fs-3 text-center text-warning fw-medium">Vui lòng chọn chi nhánh và dịch vụ để hiện khung giờ.</p>',
            );
        }
        return;
    }
    $("#slot-warning").remove();

    getSlots(branchId, serviceId, date)
        .done(function (resHtml) {
            $("#slot-list").html(resHtml);
        })
        .fail(function () {
            alert("Có lỗi xảy ra khi tải dữ liệu khung giờ!");
        });
}
