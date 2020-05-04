/*-------------------------------------------------------------------------
* Name: info_parent.js                                                       *
* Description:  Scripts to handle changes made to the UI on the parent info  *
*               page, such as button clicks (Add, Edit, Delete, Back,        *
*               Logout).                                                     *
*               Scripts to pass parent data into the Controller or diplay    *
*               data from the Controller (ajax functions).                   *
---------------------------------------------------------------------------*/

/* global $*/
var familyID = "";
var currentPIN = "";

$('document').ready(function() {

    // Close button for delete popup
    $(".ui-dialog-titlebar-close").on('click', function() {
        $('.overlay').hide();
    });

    // Force click button whenever enter is pressed
    $(document).keypress(function(e) {
        if (e.key === "Enter") {
            enterPressed();
            return false;
        }
    });

    // Hide log popup when clicked outside of form
    $(document).mouseup(function(e) {
        var container = $(".add-parent-popup");

        // If the target of the click isn't the container nor a descendant of the container
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            closeForm();
        }
    });

    // When user clicks Add button
    $('#add-button').on('click', function() {
        add();
    });

    // When user clicks 'Save changes' button
    $('#edit-button').on('click', function() {
        saveChanges();
    });
    
    // Check if enter was pressed in search input
    $("#search-input").on('keyup', function(e) {
        if (e.keyCode == 13) {
            $("#search-button").click();
        }
    });
});

// Checks which button should be force clicked when enter is pressed
function enterPressed() {
    // Add form is open, add new parent
    if ($('#add-button:visible').length > 0) {
        $("#add-button").click();
    }
    // Edit form is open, edit parent
    else if ($('#edit-button:visible').length > 0) {
        $("#edit-button").click();
    }
    // Enter was pressed for delete confirmation
    else if ($('#dialog:visible').length > 0) {
        if ($("#yes-button").is(":focus")) {
            $("#yes-button").click();
        }
        else if ($("#no-button").is(":focus")) {
            $("#no-button").click();
        }
    }
}

// When user clicks the close button in the top right corner of the 'Add Parent' form, close the form
function closeForm() {
    $('.add-parent-popup').hide();
    $('.overlay').hide();
    clearFields();
    $('#pin-message').hide();
}

// Clears all of the fields in popup screen
function clearFields() {
    $('#p1-fn-input').val('');
    $('#p1-ln-input').val('');
    $('#p2-fn-input').val('');
    $('#p2-ln-input').val('');
    $('#PIN').val('');
}

// When Add Parent(s) is clicked
// Displays the add parent form
function addParentForm() {
    $('.add-parent-popup').show();
    $('.overlay').show();
    $('#edit-button').hide();
    $('#add-button').show();
    $("#pin-label").text("* PIN #:")
    $("#header").text("Add Parent(s)");
    $("#sign-instructions").text("Please add parent first and last name(s) for one family.");
    $('#p1-fn-input').focus();
    document.getElementById("PIN").required = true;
}

// Checks if there are any fields left empty
function validateFields(p1_fname, p1_lname, p2_fname, p2_lname) {
    if (p1_fname == "") {
        showError('Please enter a first name for Parent/Guardian 1');
        return false;
    }
    else if (p1_lname == "") {
        showError('Please enter a last name for Parent/Guardian 1');
        return false;
    }
    else if ((p2_fname == "") && !(p2_lname == "")) {
        showError('Please add a first name for Parent/Guardian 2');
        return false;
    }
    else if (!(p2_fname == "") && (p2_lname == "")) {
        showError('Please add a last name for Parent/Guardian 2');
        return false;
    }
    else {
        return true;
    }
}


// When user clicks Add
function add() {
    var p1_fname = $('#p1-fn-input').val();
    var p1_lname = $('#p1-ln-input').val();
    var p2_fname = $('#p2-fn-input').val();
    var p2_lname = $('#p2-ln-input').val();
    var pin = $('#PIN').val();

    if (validateFields(p1_fname, p1_lname, p2_fname, p2_lname)  && validPINentry()) {
        if (validPIN(pin)) {
            $.ajax({
                url: 'info_parent.php',
                type: 'post',
                async: false,
                data: {
                    'save': 1,
                    'p1_fname': p1_fname,
                    'p1_lname': p1_lname,
                    'p2_fname': p2_fname,
                    'p2_lname': p2_lname,
                    'pin': pin,
                },
                success: function(response) {
                    closeForm();
                    $("#parent-table").remove();
                    $("#pagination-info").remove();
                    populateTable();
                    successPopup("Successfully added parent(s)");
                }
            });
        }
    }
}

// When edit button is clicked for any row of families
// Displays the edit form for user to edit information
function editForm(famID) {
    // Show the parent form
    $('.add-parent-popup').show();
    $('.overlay').show();
    $('#add-button').hide();
    $('#edit-button').show();
    $("#header").text("Edit Parent(s)");
    $("#pin-label").text("PIN #:")
    $("#sign-instructions").text("Please make any changes and click Save Changes.");
    document.getElementById("PIN").required = false;

    // Populate with selected family data
    populateParentData(famID);
}

// Pre-populates data whenever edit button is clicked
function populateParentData(famID) {
    familyID = famID;

    $.ajax({
        url: 'info_parent.php',
        type: 'post',
        data: {
            'populate': 1,
            'famID': famID,
        },
        success: function(response) {
            var result = $.parseJSON(response);

            // Populate the fields
            $('#p1-fn-input').val(result[0]);
            $('#p1-ln-input').val(result[1]);
            $('#p2-fn-input').val(result[2]);
            $('#p2-ln-input').val(result[3]);
            //$('#PIN').val(result[4]);

            currentPIN = result[4];
        }
    });
}

// When user clicks 'Save changes' button
function saveChanges() {
    // User input variables
    var p1_fname = $('#p1-fn-input').val();
    var p1_lname = $('#p1-ln-input').val();
    var p2_fname = $('#p2-fn-input').val();
    var p2_lname = $('#p2-ln-input').val();
    var pin = $('#PIN').val();

    if (validateFields(p1_fname, p1_lname, p2_fname, p2_lname)) {

        // Check if PIN was changed. If it was, make sure it is available.
        if ((pin == currentPIN) || (pin == '')) {
            submitEditForm(p1_fname, p1_lname, p2_fname, p2_lname, pin);
        }
        else {
            if (validPIN(pin) && validPINentry()) {
                submitEditForm(p1_fname, p1_lname, p2_fname, p2_lname, pin);
            }
        }
    }
}

// Check if the PIN already exists in the database
function validPIN(pin) {
    var pinNumber_state;

    $.ajax({
        url: 'info_parent.php',
        type: 'post',
        async: false,
        data: {
            'pinNumber_check': 1,
            'pinNum': pin,
            'famID': familyID,
        },
        success: function(response) {
            if (response == 'taken') {
                pinNumber_state = false;
                showError('PIN is already taken');
            }
            else if (response == 'not_taken') {
                pinNumber_state = true;
                showSuccess('PIN is available');
            }
        }
    });

    return pinNumber_state;
}

// Make any necessary changes to the database for family
function submitEditForm(p1_fname, p1_lname, p2_fname, p2_lname, pin) {
        $.ajax({
        url: 'info_parent.php',
        type: 'post',
        data: {
            'update': 1,
            'p1_fname': p1_fname,
            'p1_lname': p1_lname,
            'p2_fname': p2_fname,
            'p2_lname': p2_lname,
            'pin': pin,
            'famID': familyID,
        },
        success: function(response) {
            if (response == 'done') {
                closeForm();
                $("#parent-table").remove();
                $("#pagination-info").remove();
                populateTable();
                successPopup("Successfully edited parent(s)");
            }
            else {
                showError(response);
            }
        }
    });
    
}

// Validates the entered pin has the following criteria
// 1. Not empty
// 2. At least 4 digits long
// 3. Numeric
function validPINentry() {
    var pin = $('#PIN').val();
    if (pin == '') {
        showError('Please enter a PIN');
        return false;
    }
    else if (pin.length < 4) {
        showError('PIN needs to be at least 4 digits');
        return false;
    }
    else if (!(/^\d+$/.test(pin))) {
        showError('PIN needs to be a number');
        return false;
    }
    else {
        return true;
    }
}

// Allows user to verify deletion
function deleteParentPopup(famID) {
    $("#dialog").dialog({
        minWidth: 400,
        minHeight: 'auto',
        autoOpen: false,
        buttons: {
            "Yes": {
                text: "Yes",
                id: "yes-button",
                click: function() {
                    deleteParents(famID);
                }
            },
            "No": {
                text: "No",
                id: "no-button",
                click: function() {
                    $(this).dialog("close");
                    $('.overlay').hide();
                }
            }
        },
        close: function(ev, ui) {
            $('.overlay').hide();
        }
    });

    $("#dialog").dialog("open");
    $('.overlay').show();
}

// Deletes all parents for family when user clicks yes
function deleteParents(famID) {
    $.ajax({
        url: 'info_parent.php',
        type: 'post',
        data: {
            'delete': 1,
            'famID': famID,
        },
        success: function(response) {
            if (!(response == "success")) {
                alert(response);
            }
            else {
                $(".ui-dialog-titlebar-close").click();
                $("#parent-table").remove();
                $("#pagination-info").remove();
                populateTable();
                successPopup("Successfully removed parent(s)");
            }
        }
    });
}

// Adds table on the parent page
function populateTable() {
    $.ajax({
        url: 'info_parent.php',
        type: 'post',
        async: false,
        data: {
            'populateTable': 1,
        },
        success: function(response) {
            $('.table-container').html(response);
        },
        error: function(response) {
            alert("ERROR: " + response);
        }
    });
}

// Update table to show results of search value
function search() {
    var value = $("#search-input").val();

    $("#parent-table").remove();
    $("#pagination-info").remove();
    saveSearchString(value);
    populateTable();
    $('#search-input').val(value);
}

// Clear the search field and show fresh table
function clearSearch() {
    $("#search-input").val("");
    var value = $("#search-input").val();

    $("#parent-table").remove();
    $("#pagination-info").remove();
    saveSearchString(value);
    populateTable();
    $('#search-input').val(value);
}

// Saves the search string in the Controller as a global variable
function saveSearchString(value) {
    $.ajax({
        url: 'info_parent.php',
        type: 'post',
        async: false,
        data: {
            'saveSearch': 1,
            'search_value': value,
        },
        success: function(response) {
        },
        error: function(response) {
            alert("ERROR: " + response);
        }
    });
}

// Shows an error on the form
function showError(message) {
    $('#pin-message').show();
    $('#pin-message').removeClass("success");
    $('#pin-message').addClass("error");
    $('#pin-message').text(message);
}

// Shows success message on the form
function showSuccess(message){
    $('#pin-message').show();
    $('#pin-message').removeClass("error");
    $('#pin-message').addClass("success");
    $('#pin-message').text(message);
}

// Shows a success popup after adding/editing/deleting
function successPopup(message) {
    $("#success").text(message);
    $("#success").show().delay(3000).hide(1);
}