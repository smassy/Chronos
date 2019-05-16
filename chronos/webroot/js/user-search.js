/*
 * This file contains the code to handle live search via AJAX.
*/
'use strict';
var input = $("#live-search-input");
var div = $("#live-search-results-div");
var ul = $("#live-search-results-ul");
var liveSearchClickHandler = nullHandler;
$(div).hide();

function performQuery(query) {
    $.post({
        url: "/user-details/search",
        dataType: "text",
        type: "post",
        contentType: 'application/x-www-form-urlencoded',
        data: { "searchFor": query, "last_name": 1, "first_name": 1},
        success: function (data, status, xhr) {
            updateResults(JSON.parse(data));
        },
        error: function (xhr, status, error) {
        }
    });
}

function updateResults(results) {
    $(div).remove(".live-search-no-results");
    $(ul).empty();
    $(div).show();
    if (results.length > 0) {
        for (var i = 0; i < results.length && i < 4; i++) {
            var text = "<li class=\"live-search-result\" id=\"user-" + results[i]["user_id"] + "\">" + results[i]["first_name"] + " " + results[i]["last_name"] + " (" + results[i]["department"]["name"].toString() + ")</li>"; 
            $(ul).append(text);
        }
        ul.children().click(liveSearchClickHandler);
    } else {
        $(div).append("<p class=\"live-search-no-results\'>No match found.</p>");
    }
}

function nullHandler() {
    alert("I'm a null click handler; replace me with something useful.");
}
    
$(document).ready(function () {
    $(input).keyup(function () {
        if ($(this).val().length > 0) {
            performQuery($(this).val());
        } else {
            $(div).hide();
        }
    });
    $("#live-search-input-clear").click(function () {
        $(input).val("");
        $(div).hide();
    });
});

