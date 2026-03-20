/**
 * Technician Jobs - App Entry Point
 * Initializes all modules for jobs list page
 */

import { initJobsList } from "./technician-jobs-core.js";

$(document).ready(function () {
    // Initialize all sub-modules
    initJobsList();
});
