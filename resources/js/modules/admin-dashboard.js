import { fetchDashboardStats } from "../services/admin-dashboard-api";

$(document).ready(function () {
    const $dateInput = $('input[type="date"]');
    const $inWorkshopCount = $("#in-workshop-count");
    const $waitingCount = $("#waiting-count");
    const $btnPrevDay = $("#btn-prev-day");
    const $btnNextDay = $("#btn-next-day");

    // Hàm hỗ trợ định dạng Date object thành chuỗi YYYY-MM-DD
    function formatDate(date) {
        return date.toISOString().split("T")[0];
    }

    function loadDashboard(date) {
        if (!date) return;

        // Hiệu ứng chờ
        $inWorkshopCount.text("...");
        $waitingCount.text("...");

        fetchDashboardStats(date)
            .done(function (response) {
                if (response.carStats) {
                    $inWorkshopCount.text(
                        `${response.carStats.in_workshop} xe`,
                    );
                    $waitingCount.text(
                        `${response.carStats.waiting} xe đang chờ`,
                    );
                }
                updateNavButtons(date);
            })
            .fail(function (xhr) {
                console.error("Lỗi API:", xhr);
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
