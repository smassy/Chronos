'use strict';


var sched = $("table#scheduler");

var startTime = null;
var startTimeSlot;
var endTime = null;
var endTimeSlot;
var currentGroup = null;
var dayString = $(sched).find("tbody").attr("id");

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
            } else { 
                span--;
            }
        }
    });
}

function slotClickHandler() {
    var clickedTime = new Date(dayString + " " + $(this).html());
    var group = $(this).get(0).className.match(/group-\d+/).toString();
    if (!startTime || group != currentGroup || (clickedTime >= startTime && clickedTime < endTime)) {
        startTime = clickedTime;
        startTimeSlot = this;
        endTime = getSlotEnd(startTime);
        endTimeSlot = this;
        currentGroup = group;
    } else if (clickedTime < startTime) {
        startTime = clickedTime;
        startTimeSlot = this;
    } else if (clickedTime > endTime) {
        endTime = getSlotEnd(clickedTime);
        endTimeSlot = this;
    }
    refreshSelection();
}

function refreshSelection() {
    var slots = $("td.time-slot");
    var select = false;
    $(slots).removeClass("selected");
    $(slots).each(function () {
        if (this === startTimeSlot) {
            select = true;
        }
        if (select) {
            $(this).addClass("selected");
        }
        if (this === endTimeSlot) {
            select = false;
        }
    });
//    $(startTimeSlot).addClass("selected");
//    if (startTimeSlot != endTimeSlot) {
//        $(startTimeSlot).nextUntil(endTimeSlot, ".time_slot").addClass("selected");
//        $(endTimeSlot).addClass("selected");
//    }
    $(sched).find("caption").html("Start Time " + startTime.toString() + " and end time " + endTime.toString());
}

function getSlotEnd(date) {
    var minutes = 29;
    var seconds = 59;
    var interval = (minutes * 60 + seconds) * 1000;
    return new Date(date.getTime() + interval);
}

function getTimeString(date) {
    var hours = date.getHours();
    if (hours < 10) {
        hours = "0" + hours;
    }
    var minutes = date.getMinutes();
    if (minutes < 10) {
        minutes = "0" + minutes;
    }
    return hours + ":" + minutes;
}

$(document).ready( function () {
    resetScheduler();
});

