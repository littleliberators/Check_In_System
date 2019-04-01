/*-------------------------------------------------------------------------
* Name: parent.js                                                            *
* Description:  Scripts to handle changes made to the parent.php screen.     *
                This includes showing/hiding sign in/out screens, handling   *
                checkbox changes, showing error messages, show success       *
                messages, etc.                                               *
---------------------------------------------------------------------------*/

/* global $*/
var allchecked;

$(function() {
    // Close the form after close button is clicked 
    $('#close-button').on('click', function() {
        $('.log-time-popup').hide();
        $('.overlay').hide();
    })

    CheckBoxChanges();
});

function signInForm() {
    // Show the log form
    $('.log-time-popup').show();
    $('.overlay').show();

    // Change form titles
    $('#header').text("Sign In");

    // Populate time and date fields with current values
    populateDateTime();

    // Put focus in signature box
    $('#e-sign-input').focus();
}

function signOutForm() {
    // Show the log form
    $('.log-time-popup').show();
    $('.overlay').show();

    // Change form titles
    $('#header').text("Sign Out");

    // Populate time and date fields with current values
    populateDateTime();

    // Put focus in signature box
    $('#e-sign-input').focus();
}

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

// Show error message 
function showError(message) {
    var fieldNameElement = document.getElementById('please-select');
    fieldNameElement.innerHTML = message;
    $('#please-select').show();
}

// Show success message
function showSuccessMessage(message) {
    var fieldNameElement = document.getElementById('success');
    fieldNameElement.innerHTML = message;
    $('#success').show();
}

function CheckBoxChanges() {
    // Check all boxes if "Select All" is clicked
    $('#select-all').click(function(event) {
        if (this.checked) {
            $(':checkbox').each(function() {
                $(this).prop('checked', true);
            });
        }
        else {
            $(':checkbox').each(function() {
                $(this).prop('checked', false);
            });
        }
    });

    // Uncheck "Select All" if any other checkbox is unchecked  
    $('.check').click(function(event) {
        $('.check').each(function() {
            if (this.checked == false) {
                $('#select-all').prop('checked', false);

            }
        });
    });

    // Check "Select All" if all other checkboxes are checked
    $('.check').change(function() {
        allchecked = true;
        $('.check').each(function() {
            if (this.checked == false) {
                allchecked = false;
            }
        });
        if (allchecked == true) {
            $('#select-all').prop('checked', true);
        }
        allchecked = "";
    });
}