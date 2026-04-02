import { tableList } from "../services/booking-api";

$(document).ready(function () {
    loadBookings();
    initAdminBookingSearch();
});

function initAdminBookingSearch() {
    let debounceTimer;
    $(document).on("input", 'input[name="keyword"]', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            loadBookings();
        }, 500);
    });

    $(document).on(
        "change",
        'select[name="status"], input[name="date"]',
        function () {
            loadBookings();
        },
    );
}

function loadBookings(url) {
    const filters = {
        keyword: $('input[name="keyword"]').val(),
        status: $('select[name="status"]').val(),
        date: $('input[name="date"]').val(),
    };
    tableList(url, filters)
        .done(function (res) {
            $("#table-data").html(res);
        })
        .fail(function (xhr) {
            console.log(xhr.responseText);
        });
}

$(document).on("click", ".pagination a", function (e) {
    e.preventDefault();
    let url = $(this).attr("href");
    loadBookings(url);
});
