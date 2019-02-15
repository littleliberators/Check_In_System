/* global $*/
$(function() {
    
});

function addParentForm() {
    // Show the log form
    $('.add-parent-popup').show();
    $('.overlay').show();

    // Put focus in signature box
    $('#p1-fn-input').focus();
}

function closeForm() {
    // Close the form after close button is clicked 
    $('#close-button').on('click', function() {
        $('.add-parent-popup').hide();
        $('.overlay').hide();
    })
}

function deleteTable() {
    $("#parent-table").remove();
}