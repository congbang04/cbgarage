/**
 * Technician Jobs Core Module
 * Handles jobs list, filtering, searching, pagination
 */

import * as TechnicianJobsAPI from "../../services/technician-jobs-api.js";
import {
    showValidationErrors,
    handleAjaxError,
} from "../../helpers/validation.js";

let currentFilters = {};
let debounceTimer;

export function initJobsList() {
    // Load initial jobs list
    loadJobs();

    // Bind events
    bindTabEvents();
    bindSearchEvent();
    bindFilterEvents();
    bindRefreshEvent();
    bindPaginationEvents();
}

/**
 * Load jobs table with current filters
 */
function loadJobs(url = null) {
    const url_to_use = url || window.routes.technicianJobsTable;

    TechnicianJobsAPI.getJobsList(currentFilters)
        .done(function (html) {
            $("#jobs-table-container").html(html);
            bindPaginationEvents();
        })
        .fail(function (xhr) {
            handleAjaxError(xhr, "Lỗi tải danh sách công việc");
        });
}

/**
 * Tab navigation for status filtering
 */
function bindTabEvents() {
    $(".nav-pills .nav-link").on("click", function (e) {
        e.preventDefault();

        const tab = $(this).data("tab");

        // Update active tab
        $(".nav-pills .nav-link").removeClass("active");
        $(this).addClass("active");

        // Reset filters and apply selected tab
        currentFilters = {};
        if (tab) {
            currentFilters.status = tab;
            $("#statusFilter").val(tab);
        } else {
            $("#statusFilter").val("");
        }

        loadJobs();
    });
}

/**
 * Search with debounce (500ms)
 */
function bindSearchEvent() {
    $('input[name="keyword"]').on("keyup", function () {
        clearTimeout(debounceTimer);
        const keyword = $(this).val();

        debounceTimer = setTimeout(function () {
            currentFilters.keyword = keyword || null;
            loadJobs();
        }, 500);
    });
}

/**
 * Status and Date filters
 */
function bindFilterEvents() {
    $("#statusFilter").on("change", function () {
        const status = $(this).val();
        if (status) {
            currentFilters.status = status;
            // Update active tab
            $(".nav-pills .nav-link").removeClass("active");
            $(`.nav-pills .nav-link[data-tab="${status}"]`).addClass("active");
        } else {
            delete currentFilters.status;
            $(".nav-pills .nav-link").removeClass("active");
            $(".nav-pills .nav-link:first").addClass("active");
        }
        loadJobs();
    });

    $("#dateFilter").on("change", function () {
        const date = $(this).val();
        currentFilters.date = date || null;
        loadJobs();
    });
}

/**
 * Refresh button
 */
function bindRefreshEvent() {
    $("#refreshBtn").on("click", function () {
        $(this).find("i").addClass("fa-spin");
        loadJobs();
        setTimeout(() => {
            $(this).find("i").removeClass("fa-spin");
        }, 1000);
    });
}

/**
 * Pagination link click
 */
function bindPaginationEvents() {
    $(".pagination a").on("click", function (e) {
        e.preventDefault();

        const url = $(this).attr("href");
        if (!url) return;

        // Extract filters from URL and reload
        const urlParams = new URL(url, window.location.origin).searchParams;
        currentFilters.page = urlParams.get("page");

        loadJobs(url);
        window.scrollTo(0, 0);
    });
}
