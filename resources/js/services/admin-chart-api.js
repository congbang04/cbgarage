/**
 * API calls for admin dashboard charts
 */

/**
 * Lấy doanh thu tuần cho biểu đồ cột
 * @param {string} date Định dạng YYYY-MM-DD
 */
export function fetchWeeklyRevenue(date) {
    return $.ajax({
        url: window.routes.adminChartRevenueApi,
        type: "GET",
        data: { date: date },
        dataType: "json",
    });
}

/**
 * Lấy phân bổ dịch vụ cho biểu đồ tròn
 * @param {string} date Định dạng YYYY-MM-DD
 */
export function fetchServiceDistribution(date) {
    return $.ajax({
        url: window.routes.adminChartServiceDistributionApi,
        type: "GET",
        data: { date: date },
        dataType: "json",
    });
}
