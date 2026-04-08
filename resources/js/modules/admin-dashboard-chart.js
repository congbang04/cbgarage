import {
    fetchWeeklyRevenue,
    fetchServiceDistribution,
} from "../services/admin-chart-api";

// Flag để track chart instances
let revenueChart = null;
let serviceChart = null;

$(document).ready(function () {
    const $dateInput = $('input[type="date"]');

    // Tracking tuần hiện tại để detect week change
    let currentWeekStart = null;

    /**
     * Lấy Monday của tuần (start of week)
     */
    function getStartOfWeek(dateStr) {
        const date = new Date(dateStr + "T00:00:00");
        const day = date.getDay();
        const diff = date.getDate() - day + (day === 0 ? -6 : 1); // Monday
        return new Date(date.setDate(diff)).toISOString().split("T")[0];
    }

    /**
     * Format ngày thành label (Mon, Tue, ...)
     */
    function getWeekdayLabel(dateStr) {
        const days = ["T2", "T3", "T4", "T5", "T6", "T7", "CN"];
        const date = new Date(dateStr + "T00:00:00");
        return days[date.getDay() === 0 ? 6 : date.getDay() - 1];
    }

    /**
     * Format tiền tệ Việt Nam
     */
    function formatCurrency(amount) {
        return new Intl.NumberFormat("vi-VN", {
            style: "currency",
            currency: "VND",
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }).format(amount);
    }

    /**
     * Tạo biểu đồ doanh thu tuần
     */
    function loadRevenueChart(date) {
        if (!date) return;

        const $chartContainer = $("#revenue-chart");
        if ($chartContainer.length === 0) return;

        // Hiệu ứng chờ
        $chartContainer.html(
            '<div class="text-center p-5"><i class="fas fa-spinner fa-spin"></i></div>',
        );

        fetchWeeklyRevenue(date)
            .done(function (res) {
                if (!res.weeklyRevenue) return;

                const labels = Object.keys(res.weeklyRevenue).map(
                    getWeekdayLabel,
                );
                const data = Object.values(res.weeklyRevenue);

                // Xóa chart cũ nếu có
                if (revenueChart) {
                    revenueChart.destroy();
                }

                const ctx = $chartContainer[0].getContext("2d");
                revenueChart = new Chart(ctx, {
                    type: "line",
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: "Doanh thu",
                                data: data,
                                borderColor: "#28a745",
                                backgroundColor: "rgba(40, 167, 69, 0.1)",
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 5,
                                pointBackgroundColor: "#28a745",
                                pointBorderColor: "#fff",
                                pointBorderWidth: 2,
                                pointHoverRadius: 7,
                                pointHoverBackgroundColor: "#228f3e",
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: false,
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        return formatCurrency(context.raw);
                                    },
                                },
                            },
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function (value) {
                                        return (
                                            (value / 1000000).toFixed(1) + "tr"
                                        );
                                    },
                                },
                            },
                        },
                    },
                });
            })
            .fail(function (xhr) {
                console.error("Lỗi khi lấy doanh thu:", xhr);
                $chartContainer.html(
                    '<div class="alert alert-danger">Lỗi khi tải biểu đồ</div>',
                );
            });
    }

    /**
     * Tạo biểu đồ tròn phân bổ dịch vụ
     */
    function loadServiceChart(date) {
        if (!date) return;

        const $chartContainer = $("#service-chart");
        if ($chartContainer.length === 0) return;

        // Hiệu ứng chờ
        $chartContainer.html(
            '<div class="text-center p-5"><i class="fas fa-spinner fa-spin"></i></div>',
        );

        fetchServiceDistribution(date)
            .done(function (res) {
                if (!res.services || res.services.length === 0) {
                    $chartContainer.html(
                        '<div class="alert alert-info">Không có dữ liệu dịch vụ trong tuần này</div>',
                    );
                    return;
                }

                const labels = res.services.map((s) => s.name);
                const data = res.services.map((s) => s.total);

                // Định nghĩa màu sắc
                const colors = [
                    "#FF6384",
                    "#36A2EB",
                    "#FFCE56",
                    "#4BC0C0",
                    "#9966FF",
                    "#FF9F40",
                    "#FF6384",
                    "#C9CBCF",
                ];

                // Xóa chart cũ nếu có
                if (serviceChart) {
                    serviceChart.destroy();
                }

                const ctx = $chartContainer[0].getContext("2d");
                serviceChart = new Chart(ctx, {
                    type: "doughnut",
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                data: data,
                                backgroundColor: colors.slice(0, labels.length),
                                borderColor: "#fff",
                                borderWidth: 2,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: "right",
                                labels: {
                                    padding: 15,
                                    font: {
                                        size: 12,
                                    },
                                },
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        const total =
                                            context.dataset.data.reduce(
                                                (a, b) => a + b,
                                                0,
                                            );
                                        const percentage = (
                                            (context.raw / total) *
                                            100
                                        ).toFixed(1);
                                        return (
                                            formatCurrency(context.raw) +
                                            " (" +
                                            percentage +
                                            "%)"
                                        );
                                    },
                                },
                            },
                        },
                    },
                });
            })
            .fail(function (xhr) {
                console.error("Lỗi khi lấy phân bổ dịch vụ:", xhr);
                $chartContainer.html(
                    '<div class="alert alert-danger">Lỗi khi tải biểu đồ</div>',
                );
            });
    }

    /**
     * Tải tất cả charts
     */
    function loadCharts(date) {
        if (!date) return;
        loadRevenueChart(date);
        loadServiceChart(date);
    }

    // Sự kiện khi thay đổi input date
    $dateInput.on("change", function () {
        const newDate = $(this).val();
        const newWeekStart = getStartOfWeek(newDate);

        // Kiểm tra xem có nhảy qua tuần mới không
        if (currentWeekStart === null || currentWeekStart !== newWeekStart) {
            console.log(`Tuần thay đổi: ${currentWeekStart} → ${newWeekStart}`);
            currentWeekStart = newWeekStart;
            // Reload charts nếu nhảy qua tuần
            loadCharts(newDate);
        }
    });

    // Load lần đầu
    const initialDate = $dateInput.val();
    currentWeekStart = getStartOfWeek(initialDate);
    loadCharts(initialDate);
});
