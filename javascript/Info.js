/* global $*/

$(function() {
    /*
        // If the sign in/out buttons get clicked
        $('.sign-btn').on('click', function() {
            // Show the log form
            $('.log-time-popup').show();
            $('.overlay').show();
            
            // Change form titles
            $('#header').text($(this).text());
            
            // Populate time and date fields with current values
            populateDateTime();
            
            // Put focus in signature box
            $('#e-sign-input').focus()
        })*/

    // Close the form after close button is clicked 
    $('#close-button').on('click', function() {
        $('.log-time-popup').hide();
        $('.overlay').hide();
    })
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

// Show error message 'Please select one child'
function showSelectChild(){
    $('#please-select').show();
}
