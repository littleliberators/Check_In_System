/*-------------------------------------------------------------------------
* Name: info_logs.js                                                         *
* Description:  Scripts to handle changes made to the UI on the log info     *
                page, such as button clicks (Add, Edit, Delete, Back,        *
                Logout).                                                     *
---------------------------------------------------------------------------*/

/*global $*/
/*global location*/
var currentLogID = "";

$('document').ready(function() {
    // Whenever user searches
    $("#search-input").keyup(function() {
        var value = this.value.toLowerCase().trim();

        $("table tr").each(function(index) {
            if (!index) return;
            $(this).find("td").each(function() {
                var id = $(this).text().toLowerCase().trim();
                var not_found = (id.indexOf(value) == -1);
                $(this).closest('tr').toggle(!not_found);
                return not_found;
            });
        });
    });

    // Hide log popup when clicked outside of form
    $(document).mouseup(function(e) {
        var container = $(".add-log-popup");

        // If the target of the click isn't the container nor a descendant of the container
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            closeForm();
        }
    });

    // When user clicks Add 
    $('#add-log-button').on('click', function() {
        addLog();
    });
    
    // When user clicks Save changes
    $('#edit-button').on('click', function() {
        saveChanges();
    });
});

// When Add New Log is clicked
// Displays the add log form
function addLogForm() {
    $('.add-log-popup').show();
    $('.overlay').show();
    $('#edit-button').hide();
    $('#add-log-button').show();

    $("#header").text("Add New Log");

    populateChildren();
}

// When user clicks the close button in the top right corner of the 'Add Parent' form
function closeForm() {
    $('.add-log-popup').hide();
    $('.overlay').hide();
    clearFields();
}

// Clears all of the fields in popup screen
function clearFields() {
    $('#date-input').val('');
    $('#select-child').children().remove().end().append('<option selected value="select">-- Select Child --</option>');
    $('#sign-in-time').val('');
    $('#sign-in-signature').val('');
    $('#sign-out-time').val('');
    $('#sign-out-signature').val('');
    $('#error-message').hide();
}

// Adds list of children's names to dropdown
function populateChildren() {
    $.ajax({
        url: 'info_logs.php',
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
                $('#error-message').text('There are no children saved in the database');
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

// Add a new time log to the database
function addLog() {
    if (validateFields()) {
        var date = $('#date-input').val();
        var childID = $('#select-child').find('option:selected').attr("data-childid");
        var signInTime = $('#sign-in-time').val();
        var signInSign = $('#sign-in-signature').val();
        var signOutTime = $('#sign-out-time').val();
        var signOutSign = $('#sign-out-signature').val();

        // Proceed with form submission
        $.ajax({
            url: 'info_logs.php',
            type: 'post',
            async: false,
            data: {
                'addLog': 1,
                'date': date,
                'childID': childID,
                'signInTime': signInTime,
                'signInSign': signInSign,
                'signOutTime': signOutTime,
                'signOutSign': signOutSign,
            },
            success: function(response) {
                if (response == "success") {
                    location.reload();
                }
                else {
                    alert("Unable to save log. " + response);
                    location.reload();
                }
            }
        });
    }
}

// Validate user filled in required fields
function validateFields() {
    var date = $('#date-input').val();
    var name = $('#select-child').val();

    if (date == "") {
        $('#error-message').show();
        $('#error-message').addClass("error");
        $('#error-message').text('Please select a date');
        return false;
    }
    else if (name == "select") {
        $('#error-message').show();
        $('#error-message').addClass("error");
        $('#error-message').text('Please select a child');
        return false;
    }
    else {
        return true;
    }
}

// When edit button is clicked for a log
// Displays the edit form for user to edit log information
function editForm(logID) {
    currentLogID = logID;
    
    // Show log form
    addLogForm();
    $('#add-log-button').hide();
    $('#edit-button').show();

    // Change text for edit form
    $("#header").text("Edit Log");

    // Populate with selected log data
    populateLogData(logID);
}

// Pre-populates data whenever edit button is clicked
function populateLogData(logID) {
    $.ajax({
        url: 'info_logs.php',
        type: 'post',
        async: false,
        data: {
            'populate': 1,
            'logID': logID,
        },
        success: function(response) {
            // Get the data from the server
            var result = $.parseJSON(response);
            if (result[0] === "error") {
                alert("There was a problem with the child information.");
            }
            else {
                populateLogInfo(result);
            }
        }
    });
}

// Populate fields with existing values
function populateLogInfo(info) {
    $('#date-input').val(info[1]);
    $('#select-child option[data-childid="'+ info[0]+'"]').prop('selected', true);
    $('#sign-in-time').val(info[2]);
    $('#sign-in-signature').val(info[3]);
    $('#sign-out-time').val(info[4]);
    $('#sign-out-signature').val(info[5]);
}

// Save any changes that user made to log information
function saveChanges() {
    if (validateFields()) {
        var date = $('#date-input').val();
        var childID = $('#select-child').find('option:selected').attr("data-childid");
        var signInTime = $('#sign-in-time').val();
        var signInSign = $('#sign-in-signature').val();
        var signOutTime = $('#sign-out-time').val();
        var signOutSign = $('#sign-out-signature').val();
        
        // Proceed with form submission
        $.ajax({
            url: 'info_logs.php',
            type: 'post',
            async: false,
            data: {
                'update': 1,
                'logID': currentLogID,
                'date': date,
                'childID': childID,
                'signInTime': signInTime,
                'signInSign': signInSign,
                'signOutTime': signOutTime,
                'signOutSign': signOutSign,
            },
            success: function(response) {
                if (response == "success") {
                    location.reload();
                }
                else {
                    alert("Unable to save log. " + response);
                    location.reload();
                }
            }
        });
    }
}

// Allows user to verify deletion
function deleteLogPopup(logID) {
    //Set up the dialog box
    $("#dialog").dialog({
        minWidth: 400,
        minHeight: 'auto',
        autoOpen: false,
        buttons: {
            "Yes": function() {
                deleteLog(logID);
                $("#dialog").dialog("close");
                $('.overlay').hide();
            },
            "No": function() {
                $(this).dialog("close");
                $('.overlay').hide();
            }
        },
        close: function(ev, ui) {
            $('.overlay').hide();
        }
    });

    $("#dialog").dialog("open");
    $('.overlay').show();
}

// Deletes log when user clicks yes
function deleteLog(logID) {
    $.ajax({
        url: 'info_logs.php',
        type: 'post',
        data: {
            'delete': 1,
            'logID': logID,
        },
        success: function(response) {
            if (!(response == "success")) {
                alert(response);
            }
            else {
                location.reload();
            }
        }
    });
}
