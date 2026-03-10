/* =========================
   GLOBAL SLOT CHECKER
========================= */
import { loadSlots } from "./booking.js";

$(document).ready(function () {
    startGlobalSlotChecker();
});

function startGlobalSlotChecker() {
    setInterval(() => {
        const now = Date.now();
        let needsReload = false;

        $(".time-slot-btn.locked").each(function () {
            const expires = $(this)
                .find("input[name='slot_time']")
                .data("expires");

            if (!expires) return;

            const expiresAt = new Date(expires).getTime();

            if (expiresAt <= now) {
                needsReload = true;
                return false;
            }
        });
        if (needsReload) {
            loadSlots();
        }
    }, 1000);
}

/* =========================
   TOOLTIP LOCK COUNTDOWN
========================= */

let tooltipTimer = null;

$(document).on("mouseenter", ".time-slot-btn.locked", function () {
    const $slot = $(this);
    const $input = $slot.find("input[name='slot_time']");

    const expires = $input.data("expires");
    if (!expires) return;

    const expiresAt = new Date(expires).getTime();

    const tooltip = $('<div class="slot-tooltip"></div>');
    $("#slot-list").append(tooltip);

    function updateTooltip() {
        const now = Date.now();
        const distance = expiresAt - now;

        if (distance <= 0) {
            tooltip.remove();
            clearInterval(tooltipTimer);
            return;
        }

        const m = Math.floor(distance / 60000);
        const s = Math.floor((distance % 60000) / 1000);

        tooltip.html(
            '<i class="fa-solid fa-unlock" style="color: rgb(255, 255, 255);"></i>  ' +
                `Mở khóa sau: ${m.toString().padStart(2, "0")}:${s.toString().padStart(2, "0")}`,
        );

        const offset = $slot.position();

        tooltip.css({
            top: offset.top - 10,
            left: offset.left + $slot.outerWidth() / 2,
        });
    }

    tooltipTimer = setInterval(updateTooltip, 1000);
    updateTooltip();
});

$(document).on("mouseleave", ".time-slot-btn.locked", function () {
    clearInterval(tooltipTimer);
    $(".slot-tooltip").remove();
});
