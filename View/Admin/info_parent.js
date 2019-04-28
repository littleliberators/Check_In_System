/*-------------------------------------------------------------------------
* Name: info_parent.js                                                       *
* Description:  Scripts to handle changes made to the UI on the parent info  *
                page, such as button clicks (Add, Edit, Delete, Back,        *
                Logout).                                                     *
---------------------------------------------------------------------------*/

/* global $*/
/* global location */
var familyID = "";
var currentPIN = "";

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

    // When user clicks Add 
    $('#add-button').on('click', function() {
        add();
    });

    // When user clicks 'Save changes' button
    $('#edit-button').on('click', function() {
        saveChanges();
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

// When user clicks Add
function add() {
    // User input variables
    var p1_fname = $('#p1-fn-input').val();
    var p1_lname = $('#p1-ln-input').val();
    var p2_fname = $('#p2-fn-input').val();
    var p2_lname = $('#p2-ln-input').val();
    var pin = $('#PIN').val();

    if (validateFields(p1_fname, p1_lname, p2_fname, p2_lname, pin)) {

        if (validPIN(pin)) {
            // Proceed with form submission
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
                    location.reload();
                }
            });
        }
    }
}

function validPIN(pin) {
    var pinNumber_state;

    // Check if pin exists
    $.ajax({
        url: 'info_parent.php',
        type: 'post',
        async: false,
        data: {
            'pinNumber_check': 1,
            'pinNum': pin,
        },
        success: function(response) {
            if (response == 'taken') {
                pinNumber_state = false;
                $('#pin-message').show();
                $('#pin-message').addClass("error");
                $('#pin-message').text('PIN is already taken');
            }
            else if (response == 'not_taken') {
                pinNumber_state = true;
                $('#pin-message').show();
                $('#pin-message').addClass("success");
                $('#pin-message').text('PIN is available');
            }
        }
    });

    return pinNumber_state;
}


// When user clicks 'Save changes' button
function saveChanges() {
    // User input variables
    var p1_fname = $('#p1-fn-input').val();
    var p1_lname = $('#p1-ln-input').val();
    var p2_fname = $('#p2-fn-input').val();
    var p2_lname = $('#p2-ln-input').val();
    var pin = $('#PIN').val();

    if (validateFields(p1_fname, p1_lname, p2_fname, p2_lname, pin)) {

        // Check if PIN was changed. If it was, make sure there it is available.
        if (pin == currentPIN) {
            // Proceed with form submission
            submitEditForm(p1_fname, p1_lname, p2_fname, p2_lname, pin);
        }
        else {
            if (validPIN(pin)) {
                // Proceed with form submission
                submitEditForm(p1_fname, p1_lname, p2_fname, p2_lname, pin);
            }
        }
    }
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
                location.reload();
            }
            else {
                $('#pin-message').show();
                $('#pin-message').addClass("error");
                $('#pin-message').text(response);
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
        $('#pin-message').show();
        $('#pin-message').addClass("error");
        $('#pin-message').text('Please enter a PIN');
        return false;
    }
    else if (pin.length < 4) {
        $('#pin-message').show();
        $('#pin-message').addClass("error");
        $('#pin-message').text('PIN needs to be at least 4 digits');
        return false;
    }
    else if (!(/^\d+$/.test(pin))) {
        $('#pin-message').show();
        $('#pin-message').addClass("error");
        $('#pin-message').text('PIN needs to be a number');
        return false;
    }
    else {
        return true;
    }
}

// Checks if there are any fields left empty
function validateFields(p1_fname, p1_lname, p2_fname, p2_lname, pin) {
    if (p1_fname == "") {
        $('#pin-message').show();
        $('#pin-message').addClass("error");
        $('#pin-message').text('Please enter a first name for Parent/Guardian 1');
        return false;
    }
    else if (p1_lname == "") {
        $('#pin-message').show();
        $('#pin-message').addClass("error");
        $('#pin-message').text('Please enter a last name for Parent/Guardian 1');
        return false;
    }
    else if ((p2_fname == "") && !(p2_lname == "")) {
        $('#pin-message').show();
        $('#pin-message').addClass("error");
        $('#pin-message').text('Please add a first name for Parent/Guardian 2');
        return false;
    }
    else if (!(p2_fname == "") && (p2_lname == "")) {
        $('#pin-message').show();
        $('#pin-message').addClass("error");
        $('#pin-message').text('Please add a last name for Parent/Guardian 2');
        return false;
    }
    else if (validPINentry() == true) {
        return true;
    }
    else {
        return false;
    }
}

// When Add Parent(s) is clicked
// Displays the add parent form
function addParentForm() {
    // Show the log form
    $('.add-parent-popup').show();
    $('.overlay').show();
    $('#edit-button').hide();
    $('#add-button').show();

    // Change text for add form title bar
    $("#header").text("Add Parent(s)");

    // Change text for instructions
    $("#sign-instructions").text("Please add parent first and last name(s) for one family.");

    // Put focus in signature box
    $('#p1-fn-input').focus();
}

// When user clicks the close button in the top right corner of the 'Add Parent' form
function closeForm() {
    // Close the form after close button is clicked 
    $('.add-parent-popup').hide();
    $('.overlay').hide();
    clearFields();
    $('#pin-message').hide();
}

function clearFields() {
    $('#p1-fn-input').val('');
    $('#p1-ln-input').val('');
    $('#p2-fn-input').val('');
    $('#p2-ln-input').val('');
    $('#PIN').val('');
}

// When edit button is clicked for any row of families
// Displays the edit form for user to edit information
function editForm(famID) {
    // Show the parent form
    $('.add-parent-popup').show();
    $('.overlay').show();
    $('#add-button').hide();
    $('#edit-button').show();

    // Change text for edit form title bar
    $("#header").text("Edit Parent(s)");

    // Change text for instructions
    $("#sign-instructions").text("Please make any changes and click Save Changes.");

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
            // Get the data from the server
            var result = $.parseJSON(response);

            // Populate the fields
            $('#p1-fn-input').val(result[0]);
            $('#p1-ln-input').val(result[1]);
            $('#p2-fn-input').val(result[2]);
            $('#p2-ln-input').val(result[3]);
            $('#PIN').val(result[4]);

            currentPIN = result[4];
        }
    });
}


// Allows user to verify deletion
function deleteParentPopup(famID) {
    // Set up the dialog box
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
                location.reload();
            }
        }
    });
}
