/* global $*/

$(function() {

    // Show the time log form after button 'Sign In' is clicked
    $('#sign-in-btn').on('click', function() {
        $('.log-time-popup').show();
        $('.overlay').show();
        $('#header').text("Sign In")
        // Populate time and date fields with current values
        populateDateTime();
    })
    
    // Show the time log form after button 'Sign In' is clicked
    $('#sign-out-btn').on('click', function() {
        $('.log-time-popup').show();
        $('.overlay').show();
        $('#header').text("Sign Out")
        // Populate time and date fields with current values
        populateDateTime();
    })
    
    // Close the form after close button is clicked 
    $('#close-button').on('click', function() {
        $('.log-time-popup').hide();
        $('.overlay').hide();
    })
});

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
