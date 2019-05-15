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
    $(sched).find("td.second-party").remove();
    if (!editMode) {
        $("input#startTime").val("");
        $("input#endTime").val("");
    }
    $(sched).find("th.second-party-hdr").html("Please select a party...");
    checkSecondParty();
}

function setupSlots() {
    $("td.time-slot").off();
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
                group++;
            }  
            var spanValues = [span - 1];
            $(this).find(".booked").each( function () {
                spanValues.push(parseInt($(this).attr("rowspan")) - 1);
            });
            span = Math.max.apply(null, spanValues);
        }
    });
}

function slotClickHandler() {
    var clickedTime = new Date(dayString + " " + $(this).html());
    if (clickedTime < new Date()) {
        alert("You are trying to select a time slot in the past; you need a time machine.");
        return;
    }
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
        if ($(this).html() === $(startTimeSlot).html()) {
            select = true;
        }
        if (select) {
            $(this).addClass("selected");
        }
        if ($(this).html() === $(endTimeSlot).html()) {
            select = false;
        }
    });
    $("input#startTime").val(getTimeString(startTime));
    $("input#endTime").val(getTimeString(endTime));
}

function getSlotStart(date) {
    var minutes = 29;
    var interval = (minutes * 60) * 1000;
    return new Date(date.getTime() - interval);
}

function getSlotEnd(date) {
    var minutes = 29;
    var interval = (minutes * 60) * 1000;
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

function checkSecondParty() {
    if ($("#int_party").val() === "") {
    return;
    }
    $.post({
        url: "/appointments/availability",
        dataType: 'text',
        type: 'post',
        contentType: 'application/x-www-form-urlencoded',
        data: {"day": dayString, "user_id": $("#int_party").val()},
        success: updateSecondParty,
        error: function (xhr, status, error) {
            console.log(status + ": " + error);
        }
    });
}

function updateSecondParty(data, status, xhr) {
    data = JSON.parse(data);
    var slots = $(sched).find("tbody tr");
    var span = 0;
    var content;
    $(sched).find("th.second-party-hdr").html($("#party_name").val());
    for (var i = 0; i < data.length; i++) {
        if (span === 0) {
            if (data[i]["booked"]) {
                content = "<td class=\"second-party booked\" rowspan=\"" + data[i]["slots"] + "\">" + data[i]["title"] + "</td>";
                span = data[i]["slots"] - 1;
            } else {
                content = "<td class=\"second-party free\"></td>";
            }
            $(slots[i]).append(content);
        } else {
            span--;
        }
    }
    setupSlots();
}

$(document).ready( function () {
    resetScheduler();
    setupSlots();
});

