// Ensure the confirm password matches the entered password before submitting form.
'use strict';

var pwConfirm = document.getElementById("pwConfirm");
function checkValidity() {
    pwConfirm.setCustomValidity("");
    if ($("#password").val() !== $("#pwConfirm").val()) {
        pwConfirm.setCustomValidity("Passwords do not match.");
    }
}

$("#password").change(checkValidity);
$("#pwConfirm").change(checkValidity);
