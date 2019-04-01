/*-------------------------------------------------------------------------
* Name: loginTabs.js                                                         *
* Description:  Scripts to handle alernating between parent and admin tabs.  *
---------------------------------------------------------------------------*/
/* global $*/

$(function() {
    ParentTabFocus();
    AdminTabFocus();
});

function ParentTabFocus() {
    // Show the parent container
    $('#tab-parent').click(function(event) {
        document.getElementById('tab-parent').className = "selected";
        document.getElementById('tab-admin').className = "not-selected";
        document.getElementById("parent-form").classList.remove('hide');
        document.getElementById("admin-form").classList.add('hide');
    });
}

function AdminTabFocus() {
    // Show the admin container
    $('#tab-admin').click(function(event) {
        document.getElementById('tab-admin').className = "selected";
        document.getElementById('tab-parent').className = "not-selected";
        document.getElementById("admin-form").classList.remove('hide');
        document.getElementById("parent-form").classList.add('hide');
    });
}
