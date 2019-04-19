/*-------------------------------------------------------------------------
* Name: info_reports.js                                                      *
* Description:  Scripts to handle changes in the reports page, such as       *
*               date picker, etc.                                            *
---------------------------------------------------------------------------*/

/* global $*/

$('document').ready(function() {
    //change the selected date range of that picker
    $('input[name="daterange"]').daterangepicker({
        opens: 'center'
    }, function(start, end, label) {
        console.log("A new date selection was made: " + start.format('MM/DD/YYYY') + ' to ' + end.format('YYYY-MM-DD'));
    });

    // If all children selected, disable select specific child
    // If all children de-selected, enable select specific child
    $("#select-all").change(function() {
        if ($(this).prop('checked')) {
            $("#select-child").prop("disabled", true);
        }
        else {
            $("#select-child").prop("disabled", false);
        }
    });

    // Populate select box with options of children
    populateChildren();

    // When user clicks Create PDF, validate the fields and then
    // force click the hidden button that will POST the data.
    $('#button_validate').on('click', function() {
        if (validateFields()) {
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
                    // Retrieve value and text from ajax
                    var html = "<option value=\"" + row["Child_ID"] + "\">" + row['First_Name'] + " " + row['Last_Name'] + "</option>";
                    $("#select-child").append(html);
                });
            }
        }
    });
}

// Creates the report based on user choices
function generateReport() {
    if (validateFields()) {
        // Create the pdf
        $.ajax({
            url: '../../Controller/Admin/generateReport.php',
            type: 'post',
            async: false,
            data: {
                'generatePDF': 1,
            },
            success: function(response) {
                // alert(response);
                var a = document.createElement('a');
                a.href = "data:application/octet-stream;base64," + response;
                a.target = '_blank';
                a.download = 'report.pdf';
                a.click();
            }
        });
    }
}

// Checks if all the required fields are chosen
function validateFields() {
    var name = $('#select-child').val();
    var all = $('#select-all').prop('checked');

    if (!all && name == "select") {
        $('#error-message').show();
        $('#error-message').addClass("error");
        $('#error-message').text('Please select who you want to be shown on the report.');
        return false;
    }
    else {
        $('#error-message').hide();
        return true;
    }
}
