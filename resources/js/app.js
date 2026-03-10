import "./bootstrap";
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

import "./echo";

import "./modules/profile";
import "./modules/car";
import "./modules/booking";
