/*-------------------------------------------------------------------------
* Name: keypad.js                                                            *
* Description:  - Scripts to make each of the keys on the keypad functional  *
*               and input each key into the PIN input box.                   *
*               - Scripts to handle alernating between parent and admin tabs.*
---------------------------------------------------------------------------*/

/* global $ */
/* global location */

$('document').ready(function() {

    // Checks if any of the keypad buttons were clicked
    let buttons = $("#keypad button");
    buttons.each(function(index) {
        $(this).click(input);
    });

    // When enter is pressed, check whether it's for the parent or admin screen
    $(document).keypress(function(e) {
        if (e.key === "Enter") {
            // Parent submit
            if ($('#tab-parent').hasClass('selected')) {
                $("#submit-button").click();
            }
            // Admin submit
            else if ($('#tab-admin').hasClass('selected')) {
                $("#admin-submit").click();
            }
            return false;
        }
    });
});

// Adds that key value to the input box
function input() {
    // Current pin value
    let pinVal = document.getElementById("PIN-textbox").value;

    // The back button was clicked
    if (this.innerText == "") {
        pinVal = pinVal.slice(0, -1);
    }
    // The OK button was clicked
    else if (this.innerText == "OK") {
        // PHP file will handle this
    }
    // Any other number on the keypad was clicked
    else {
        let newVal = this.innerText;
        pinVal += newVal;
    }

    $("#PIN-textbox").val(pinVal);
    $('#PIN-textbox').focus();
}

// Shows a message to see admin if user forgot pin
function forgotPIN() {
    var popup = document.getElementById("myPopup");
    popup.classList.toggle("show");
}

// Show the parent container
function ParentTabFocus() {
    document.getElementById('tab-parent').className = "selected";
    document.getElementById('tab-admin').className = "not-selected";
    document.getElementById("parent-form").classList.remove('hide');
    document.getElementById("admin-form").classList.add('hide');

    // Put focus in PIN input box
    $('#PIN-textbox').focus();

    // Hide error messages on admin screen
    $("#admin-error").hide();
    
}

// Show the admin container
function AdminTabFocus() {
    document.getElementById('tab-admin').className = "selected";
    document.getElementById('tab-parent').className = "not-selected";
    document.getElementById("admin-form").classList.remove('hide');
    document.getElementById("parent-form").classList.add('hide');

    // Put focus in admin input box
    $('#username').focus();

    // Hide error messages on parent screen
    $('#incorrect-pin').hide();
    
    // Clear admin fields
    $('#username').val("");
    $('#password').val("");
}

// Validates PIN and logs the parent in using the family ID associated with PIN
function parentLogin() {
    var PIN = $("#PIN-textbox").val();

    // Check if user typed anything in
    if ($('#PIN-textbox').val() == "") {
        $('#incorrect-pin').show();
        $('#incorrect-pin').text("Please enter a PIN number.");
    }
    else {
        // Check if the pin is valid
        $.ajax({
            url: 'login.php',
            type: 'post',
            async: false,
            data: {
                'parentLogin': 1,
                'PIN': PIN,
            },
            success: function(response) {
                // alert(response);
                if (response == 'success') {
                    window.location.href = "../Parent/parent.php";
                }
                else if (response == 'none') {
                    $('#incorrect-pin').text("Incorrect PIN. Please try again.");
                    $('#incorrect-pin').show();
                    $('#PIN-textbox').val("");
                    $('#PIN-textbox').focus();
                }
                else {
                    alert(response);
                }
            }
        });
    }
}

// Validates admin credentials and logs them in
function adminLogin() {
    var username = $("#username").val();
    var password = $("#password").val();

    if (validAdminCredentials(username, password)) {
        // Check if the username and password are valid
        $.ajax({
            url: 'login.php',
            type: 'post',
            data: {
                'adminLogin': 1,
                'username': username,
                'password': password,
            },
            success: function(response) {
                if (response == 'success') {
                    window.location.href = "../Admin/admin.php";
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
