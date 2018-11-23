/* global $*/

$(function() {
    //Populate time and date fields with current values
    populateDateTime();
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
    $('#time_input').val(now);
    $('#date_input').val(today);
}
