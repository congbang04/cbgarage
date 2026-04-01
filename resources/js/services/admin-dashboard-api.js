/**
 * Gọi API lấy thông số dashboard qua jQuery AJAX
 * @param {string} date Định dạng YYYY-MM-DD
 */
export function fetchDashboardStats(date) {
    return $.ajax({
        url: window.routes.adminStatsApi,
        type: "GET",
        data: { date: date },
        dataType: "json",
    });
}
