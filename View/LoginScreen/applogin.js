/*-------------------------------------------------------------------------
* Name: keypad.js                                                            *
* Description:  - Scripts to make each of the keys on the keypad functional  *
*               and input each key into the PIN input box.                   *
*               - Scripts to handle alernating between parent and admin tabs.*
---------------------------------------------------------------------------*/

/* global $ */

$('document').ready(function() {
    // When enter is pressed, check whether it's for the parent or admin screen
    $(document).keypress(function(e) {
        if (e.key === "Enter") {
                $("#admin-submit").click();
        }
    });
});

// Validates admin credentials and logs them in
function adminLogin() {
    var username = $("#username").val();
    var password = $("#password").val();

    // Check if the username and password are valid
    if (validAdminCredentials(username, password)) {
        $.ajax({
            url: 'applogin.php',
            type: 'post',
            data: {
                'adminLogin': 1,
                'username': username,
                'password': password,
            },
            success: function(response) {
                if (response == 'success') {
                    window.location.href = "login.php";
                }
                else if (response == 'none') {
                    document.getElementById("admin-error").innerHTML = "The username and password do not match.<br>Please try again.";
                    $('#admin-error').show();
                    $('#password').val("");
                    $('#password').focus();
                }
                else {
                    alert(response);
                }
            }
        });
    }
}

// Validates admin credentials are entered
function validAdminCredentials(username, password) {
    if (username == "") {
        $("#admin-error").show();
        $("#admin-error").text("Please enter a username.");
        return false;
    }
    else if (password == "") {
        $("#admin-error").show();
        $("#admin-error").text("Please enter a password.");
        return false;
    }
    else {
        return true;
    }
}
