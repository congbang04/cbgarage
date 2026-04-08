import {
    fetchDashboardStats,
    fetchDailyRevenue,
} from "../services/admin-dashboard-api";

$(document).ready(function () {
    const $dateInput = $('input[type="date"]');
    const $inWorkshopCount = $("#in-workshop-count");
    const $waitingCount = $("#waiting-count");
    const $btnPrevDay = $("#btn-prev-day");
    const $btnNextDay = $("#btn-next-day");
    const $dailyRevenueAmount = $("#daily-revenue-amount");

    // Hàm hỗ trợ định dạng Date object thành chuỗi YYYY-MM-DD
    function formatDate(date) {
        return date.toISOString().split("T")[0];
    }

    // Format tiền tệ Việt Nam
    function formatCurrency(amount) {
        return new Intl.NumberFormat("vi-VN", {
            style: "currency",
            currency: "VND",
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }).format(amount);
    }

    function loadDashboard(date) {
        if (!date) return;

        // Hiệu ứng chờ
        $inWorkshopCount.text("...");
        $waitingCount.text("...");
        $dailyRevenueAmount.text("...");

        // Gọi stats API
        fetchDashboardStats(date)
            .done(function (res) {
                console.log(res);

                if (res.carStats) {
                    $inWorkshopCount.text(`${res.carStats.in_workshop} xe`);
                    $waitingCount.text(`${res.carStats.waiting} xe đang chờ`);
                    $("#pending-bookings-count").text(
                        `${res.carStats.pending_bookings} lịch`,
                    );
                    $("#low-stock-parts-count").text(
                        `${res.carStats.low_stock_parts} mã`,
                    );
                }
            })
            .fail(function (xhr) {
                console.error("Lỗi API:", xhr);
            });

        // Gọi daily revenue API
        fetchDailyRevenue(date)
            .done(function (res) {
                if (res.dailyRevenue !== undefined) {
                    $dailyRevenueAmount.text(formatCurrency(res.dailyRevenue));
                }
            })
            .fail(function (xhr) {
                console.error("Lỗi khi lấy doanh thu:", xhr);
            });
    }

    // Sự kiện khi thay đổi input date trực tiếp
    $dateInput.on("change", function () {
        loadDashboard($(this).val());
    });

    // Xử lý nút Ngày trước đó
    $btnPrevDay.on("click", function () {
        let date = new Date($dateInput.val());
        date.setDate(date.getDate() - 1);

        const newDateStr = formatDate(date);
        $dateInput.val(newDateStr); // Cập nhật lại input
        loadDashboard(newDateStr);
    });

    // Xử lý nút Ngày kế tiếp
    $btnNextDay.on("click", function () {
        let date = new Date($dateInput.val());
        date.setDate(date.getDate() + 1);

        const newDateStr = formatDate(date);
        $dateInput.val(newDateStr); // Cập nhật lại input
        loadDashboard(newDateStr);
    });

    // Load lần đầu khi trang vừa tải xong
    loadDashboard($dateInput.val());
});
