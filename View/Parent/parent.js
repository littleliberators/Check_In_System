/*-------------------------------------------------------------------------
* Name: parent.js                                                            *
* Description:  Scripts to handle changes made to the parent.php screen.     *
                This includes showing/hiding sign in/out screens, handling   *
                checkbox changes, showing error messages, show success       *
                messages, etc.                                               *
---------------------------------------------------------------------------*/

/*global $*/
/*global location*/

var allchecked;

$(function() {
    // Close the form after close button is clicked 
    $('#close-button').on('click', function() {
        $('.log-time-popup').hide();
        $('.overlay').hide();
    })


    // Handle checkbox changes
    CheckBoxChanges();

    // When enter is pressed, click the correct button
    $(document).keypress(function(e) {
        if (e.key === "Enter") {
            enterPressed();
            return false;
        }
    });
});
// Shows an error on the form
function showError(message) {
    $('#error-message').show();
    $('#error-message').removeClass("success");
    $('#error-message').addClass("error");
    $('#error-message').text(message);
}

function addLogForm(type) {
    $('.add-log-popup').show();
    $('.overlay').show();
    $('#edit-button').hide();
    $('#add-log-button').show();
    $("#header").text("Add New Log");
    $("#sign-instructions").text("Create a new time log for a child.");

    // Add all available child options from database to dropdown
    populateChildren(type);
}

// Adds list of children's names to dropdown
function populateChildren(type) {
    $.ajax({
        url: 'info_logs.php',
        type: 'post',
        dataType: "json",
        async: false,
        data: {
            'populateChildren': 1,
            'type': type,
        },
        success: function(children) {
            if (children == "") {
                showError('There are no children saved in the database');
            }
            else {
                $.each(children, function(key, row) {
                    // Retrieve value and text from ajax
                    var html = "<option value=\"" + key + "\" data-childid=\"" + row["Child_ID"] + "\">" + row['First_Name'] + " " + row['Last_Name'] + "</option>";
                    $("#select-child").append(html);
                });
            }
        }
    });
}

// Checks if enter is pressed
function enterPressed() {
    // Time log popup is hidden, 
    if ($('.log-time-popup:visible').length == 0) {
        // Enter was pressed for logout confirmation
        if ($('#dialog:visible').length > 0) {
            if ($("#yes-button").is(":focus")) {
                $("#yes-button").click();
            }
            else if ($("#no-button").is(":focus")) {
                $("#no-button").click();
            }
        }
        // Enter is pressed for the Check In button
        else if ($('#sign-out-btn').hasClass('disabled')) {
            $("#sign-in-btn").click();
        }
        // Enter is pressed for the Check Out button
        else if ($('#sign-in-btn').hasClass('disabled')) {
            $("#sign-out-btn").click();
        }
    }
    // Enter was pressed for the submit button
    else if ($('.log-time-popup:visible').length > 0) {
        $("#submit-log").click();
    }
}

// Shows the sign out form when clicking Check In
function checkInForm() {
    if (validateChecked("checkIn" || validateChecked("sunshine"))) {
        // Remove any error messages
        $("#please-select-in").hide();
        $("#please-select-out").hide();
        $("#please-select-sunshine").hide();

        // Show the log form
        $('.log-time-popup').show();
        $('.overlay').show();

        // Change form titles
        $('#header').text("Check In");

        // Populate time and date fields with current values
        populateDateTime();

        // Put focus in signature box
        $('#e-sign-input').focus();
    }
}

// Shows the sign out form when clicking Check Out
function checkOutForm() {
    if (validateChecked("checkOut") || validateChecked("sunshine")) {
        // Remove any error messages
        $("#please-select-in").hide();
        $("#please-select-out").hide();
        $("#please-select-sunshine").hide();

        // Show the log form
        $('.log-time-popup').show();
        $('.overlay').show();

        // Change form titles
        $('#header').text("Check Out");

        // Populate time and date fields with current values
        populateDateTime();

        // Put focus in signature box
        $('#e-sign-input').focus();
    }
}

function checkOutSunshineForm() {
   // if (validateChecked("checkOut") || validateChecked("sunshine")) {
        // Remove any error messages
        $("#please-select-in").hide();
        $("#please-select-out").hide();
        $("#please-select-sunshine").hide();

        // Show the log form
        $('.log-time-popup').show();
        $('.overlay').show();

        // Change form titles
        $('#header').text("Sunshine");

        // Populate time and date fields with current values
        populateDateTime();

        // Put focus in signature box
        $('#e-sign-input').focus();
   // }
}

// Validate whether or not any checkboxes are checked before continuing with checking in/out
function validateChecked(form) {
    if (form == "checkIn") {
        if ($("#form-out input:checkbox:checked").length) return true;
        else {
            $("#please-select-out").show();
            return false;
        }
    }
    
    else if (form == "checkOut") {
        if ($("#form-in input:checkbox:checked").length) return true;
        else {
            $("#please-select-in").show();
            return false;
        }
    }
    
    else if(form == "sunshine") {
         if ($("#sunshine input:checkbox:checked").length) return true;
        else {
            $("#please-select-in").show();
            return false;
        }
    }
}

// Show current date and time
function populateDateTime() {
    var d = new Date(),
        h = d.getHours(),
        m = d.getMinutes(),
        day = d.getDate(),
        month = d.getMonth() + 1,
        year = d.getFullYear();
    if (h < 10) h = '0' + h;
    if (m < 10) m = '0' + m;
    if (day < 10) day = '0' + day;
    if (month < 10) month = '0' + month;

    var now = h + ':' + m;
    var today = year + "-" + month + "-" + day;
    $('#time-input').val(now);
    $('#date-input').val(today);
}

// Show success message
function showSuccessMessage(message) {
    var fieldNameElement = document.getElementById('success');
    fieldNameElement.innerHTML = message;
    $('#success').show();
}

// Resizes both Sign in and Sign out boxes to be the same size
function resizeContainers() {
    var signOutHeight = $("#child-container-out").height();
    var signInHeight = $("#child-container-in").height();

    if (signOutHeight > signInHeight) {
        $("#child-container-in").height($("#child-container-out").height());
    }
    else {
        $("#child-container-out").height($("#child-container-in").height());
    }
}

// Remove the select all option and disable button if there are no options available 
function removeSelectAll() {
    if (!$(".check-in").length) {
        $('#check-all-row-in').hide();
        $("#sign-out-btn").attr('disabled', 'disabled');
        $("#sign-out-btn").addClass('disabled');
    }

    if (!$(".check-out").length) {
        $('#check-all-row-out').hide();
        $("#sign-in-btn").attr('disabled', 'disabled');
        $("#sign-in-btn").addClass('disabled');
    }
}

// Handles any checkbox changes
function CheckBoxChanges() {
    // Check all boxes if "Select All" is clicked in Checked In
    $('#select-all-in').click(function(event) {
        if (this.checked) {
            $('#checkboxes-in').find('input:not([type=button])').prop('checked', true);
        }
        else {
            $('#checkboxes-in').find('input:not([type=button])').prop('checked', false);
        }
    });

    // Check all boxes if "Select All" is clicked in Checked Out
    $('#select-all-out').click(function(event) {
        if (this.checked) {
            $('#checkboxes-out').find('input:not([type=button])').prop('checked', true);
        }
        else {
            $('#checkboxes-out').find('input:not([type=button])').prop('checked', false);
        }
    });

    // Uncheck "Select All" if any other checkbox in Checked In is unchecked  
    $('.check-in').click(function(event) {
        $('.check-in').each(function() {
            if (this.checked == false) {
                $('#select-all-in').prop('checked', false);
            }
        });
    });

    // Uncheck "Select All" if any other checkbox in Checked Out is unchecked  
    $('.check-out').click(function(event) {
        $('.check-out').each(function() {
            if (this.checked == false) {
                $('#select-all-out').prop('checked', false);
            }
        });
    });

    // Check "Select All" if all other checkboxes in Checked In are checked
    $('.check-in').change(function() {
        allchecked = true;
        $('.check-in').each(function() {
            if (this.checked == false) {
                allchecked = false;
            }
        });
        if (allchecked == true) {
            $('#select-all-in').prop('checked', true);
        }
        allchecked = "";
    });

    // Check "Select All" if all other checkboxes in Checked Out are checked
    $('.check-out').change(function() {
        allchecked = true;
        $('.check-out').each(function() {
            if (this.checked == false) {
                allchecked = false;
            }
        });
        if (allchecked == true) {
            $('#select-all-out').prop('checked', true);
        }
        allchecked = "";
    });
}

// Takes the date, time, and signature and adds them to the database.
function submitForm() {
    var form = $("#header").text();
    if (validSignature()) {
        var date = $('#date-input').val();
        var time = $('#time-input').val();
        var signature = $('#e-sign-input').val();
       // var sunshine = 

        // User is checking in, creates a brand new log. 
        if (form == "Check In") {
           // if(sunshine = 0){
            checkInChildren(date, time, signature);
           // }
          //  else{
          //      checkOutSunshine(date, "08:00", time, signature);
          //  }
        }
        // User is checking out, updates an existing log.
        else if (form == "Check Out") {
          //  if(sunsine = 0){
            checkOutChildren(date, time, signature);
          //  }
          //  else{
          //      checkOutSunshine(date, "11:00", time, signature);
          //  }
        }
    }
}

// Validate the electronic signature field is not empty
function validSignature() {
    if ($("#e-sign-input").val() == "") {
        $("#form-error").show();
        return false;
    }
    else return true;
}

//----------------------------------------------------------------------------------------------------------------------------------------------------------
// Need to figure out how to isolate sunshine kids when checking in/out
//----------------------------------------------------------------------------------------------------------------------------------------------------------
// Creates a new log in the database
function checkInChildren(date, time, signature) {
    // Create an array with selected Child Id's
    var arrayChildID = [];
    $("input:checked[name=Name-Out]").each(function() {
        arrayChildID.push($(this).val());
    });

    // Encode data into JSON string so it can later be used in the Controller
    var jsonArray = JSON.stringify(arrayChildID);

    $.ajax({
        url: 'parent.php',
        type: 'post',
        async: false,
        data: {
            'checkIn': 1,
            'date': date,
            'time': time,
            'signature': signature,
            'childID_array': jsonArray,
        },
        success: function(response) {
            if (!(response == "success")) {
                alert(response);
                location.reload();
            }
            else {
                $('#close-button').click();
                logoutConfirmation("Successfully checked in.");
            }
        }
    });
}

// Updates existing logs in database
function checkOutChildren(date, time, signature) {
    // Create an array with selected Child Id's
    var arrayChildID = [];
    $("input:checked[name=Name-In]").each(function() {
        arrayChildID.push($(this).val());
    });

    // Encode data into JSON string so it can later be used in the Controller
    var jsonArray = JSON.stringify(arrayChildID);

    $.ajax({
        url: 'parent.php',
        type: 'post',
        async: false,
        data: {
            'checkOut': 1,
            'date': date,
            'time': time,
            'signature': signature,
            'childID_array': jsonArray,
        },
        success: function(response) {
            if (!(response == "success")) {
                alert(response);
                location.reload();
            }
            else {
                $('#close-button').click();
                logoutConfirmation("Successfully checked out.");
            }
        }
    });
}
//----------------------------------------------------------------------------------------------------------------------------------------------------------
// Inserts new log with both in and out
function checkOutSunshine(date, time1, time2, signature) {
    // Create an array with selected Child Id's
    var arrayChildID = [];
    $("input:checked[name=Name-Sunshine]").each(function() {
        arrayChildID.push($(this).val());
    });

    // Encode data into JSON string so it can later be used in the Controller
    var jsonArray = JSON.stringify(arrayChildID);

    $.ajax({
        url: 'parent.php',
        type: 'post',
        async: false,
        data: {
            'checkIn': 1,
            'date': date,
            'time': time1,
            'signature': null,
            'childID_array': jsonArray
        }
    });
    $.ajax({
        url: 'parent.php',
        type: 'post',
        async: false,
        data: {
            'checkOut': 1,
            'date': date,
            'time': time2,
            'signature': signature,
            'childID_array': jsonArray,
        },
        success: function(response) {
            if (!(response == "success")) {
                alert(response);
                location.reload();
            }
            else {
                $('#close-button').click();
                logoutConfirmation("Successfully checked out.");
            }
        }
    });
}

function closeForm(){
    $('.reminder-popup').hide();
    $('.overlay').hide();
}

function showOverlay(){
    $('.overlay').show();
}

// Ask user if they want to logout after checkin in/out
function logoutConfirmation(message) {
    $("#successMessage").text(message + " Would you like to logout?");

    //Set up the dialog box
    $("#dialog").dialog({
        minWidth: 400,
        minHeight: 'auto',
        autoOpen: false,
        buttons: {
            "Yes": {
                text: "Yes",
                id: "yes-button",
                click: function() {
                    $('#sign-out').click();
                }
            },
            "No": {
                text: "No",
                id: "no-button",
                click: function() {
                    location.reload();
                }
            }
        },
        close: function(ev, ui) {
            location.reload();
        }
    });

    $("#dialog").dialog("open");
    $('.overlay').show();
}
