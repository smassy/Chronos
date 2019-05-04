'use strict';

var sched = $("table#scheduler");

function resetScheduler() {
    $(sched).find("td:first-child").each(function () {
        $(this).removeClass();
        $(this).off();
        $(this).attr("role", "");
    });
    $(sched).find("td:last-child").each( function () {
        $(this).removeClass("booked free");
    });
    setupSlots();
}

function setupSlots() {
    var span = 0;
    var group = 0;
    $(sched).find("tbody tr").each( function () {
        var row = this;
        var timeSlot = $(this).find("td:first-child");
        if ($(this).find(".booked").length === 0 && (span === 0 || !span)) {
            $(timeSlot).addClass("time-slot available group-" + group);
            $(timeSlot).click(slotClickHandler);
            $(timeSlot).attr("role", "button"); // Required for accessibility
        } else {
            $(timeSlot).addClass("time-slot unavailable");
            if (span === 0 || !span) {
                span = parseInt($(row).find(".first-party").attr("rowspan")) - 1;
                group++;
                console.log("span is " + span);
            } else { 
                span--;
            }
        }
    });
}

function slotClickHandler() {
    alert("you clicked " + $(this).html());
}

$(document).ready( function () {
    resetScheduler();
});

