/* global $*/

$(function() {
    ParentTabFocus();
    AdminTabFocus();
});

function ParentTabFocus() {
    // If the parent tab gets clicked
    $('#tab-parent').click(function(event) {
        document.getElementById('tab-parent').className = "selected";
        document.getElementById('tab-admin').className = "not-selected";
        document.getElementById("parent-form").classList.remove('hide');
        document.getElementById("admin-form").classList.add('hide');
    });
}

function AdminTabFocus() {
    // If the admin tab gets clicked
    $('#tab-admin').click(function(event) {
        document.getElementById('tab-admin').className = "selected";
        document.getElementById('tab-parent').className = "not-selected";
        document.getElementById("admin-form").classList.remove('hide');
        document.getElementById("parent-form").classList.add('hide');
    });
}
