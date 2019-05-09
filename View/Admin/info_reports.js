/*-------------------------------------------------------------------------
* Name: info_reports.js                                                      *
* Description:  Scripts to handle changes in the reports page, such as       *
*               date picker, etc.                                            *
---------------------------------------------------------------------------*/

/* global $*/

$('document').ready(function() {
    // Change the selected date range of that picker
    $('input[name="daterange"]').daterangepicker({
        opens: 'center'
    }, function(start, end, label) {
    });

    // Populate select box with options of children
    populateChildren();

    // Force click Create PDF button whenever enter is pressed
    $(document).keypress(function(e) {
        if (e.key === "Enter") {
            $('#button_validate').click();
            return false;
        }
    });
    
    // Add a select all option
    $('#select-child').multiselect({
        includeSelectAllOption: true
    });

    // When user clicks Create PDF, validate the fields and then
    // force click the hidden button that will POST the data.
    $('#button_validate').click(function() {
        if (validateFields()){
            $("#generate").click();
        }
    });
});

// Automatically selects today's date in date input box
function getTodaysDate() {
    var d = new Date(),
        day = d.getDate(),
        month = d.getMonth() + 1,
        year = d.getFullYear();
    var today = month + "/" + day + "/" + year;
    var dateRange = today + " - " + today;
    return dateRange;
}

// Creates a list of children to select from
function populateChildren() {
    $.ajax({
        url: 'info_reports.php',
        type: 'post',
        dataType: "json",
        async: false,
        data: {
            'populateChildren': 1,
        },
        success: function(children) {
            if (children == "") {
                $('#error-message').show();
                $('#error-message').addClass("error");
                $('#error-message').text('There are no children saved in the database.');
            }
            else {
                $.each(children, function(key, row) {
                    var html = "<option value=\"" + row["Child_ID"] + "\">" + row['First_Name'] + " " + row['Last_Name'] + "</option>";
                    $("#select-child").append(html);
                });
            }
        }
    });
}

// Checks if all the required fields are chosen
function validateFields() {
    var selected = $('#select-child').val();

    if (selected.length == 0) {
        $('#error-message').removeClass("hide");
        $('#error-message').addClass("error");
        $('#error-message').text('Please select at least 1 child.');
        return false;
    }
    else {
        $('#error-message').addClass("hide");
        return true;
    }
}
