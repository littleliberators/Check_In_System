/* global $*/
/* global pin */
/* global location */
$('document').ready(function() {

    // Force click 'add' whenever enter is pressed
    $(document).keypress(function(e) {
        if (e.key === "Enter") {
            $("#add-button").click()
        }
    });

    var pinNumber_state = false;

    // When user clicks Add 
    $('#add-button').on('click', function() {
        // User input variables
        var p1_fname = $('#p1-fn-input').val();
        var p1_lname = $('#p1-ln-input').val();
        var p2_fname = $('#p2-fn-input').val();
        var p2_lname = $('#p2-ln-input').val();
        var pin = $('#PIN').val();

        if (p1_fname == "") {
            $('#pin-message').show();
            $('#pin-message').addClass("error");
            $('#pin-message').text('Please enter a first name for Parent/Guarian 1');
        }
        else if (p1_lname == "") {
            $('#pin-message').show();
            $('#pin-message').addClass("error");
            $('#pin-message').text('Please enter a last name for Parent/Guardian 1');
        }
        else if ((p2_fname == "") && !(p2_lname == "")) {
            $('#pin-message').show();
            $('#pin-message').addClass("error");
            $('#pin-message').text('Please add a first name for Parent/Guardian 2');
        }
        else if (!(p2_fname == "") && (p2_lname == "")) {
            $('#pin-message').show();
            $('#pin-message').addClass("error");
            $('#pin-message').text('Please add a last name for Parent/Guardian 2');
        }
        else if (validPIN() == true) {
            // Check if pin exists
            $.ajax({
                url: '../../php/admin/Parent_Info.php',
                type: 'post',
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

                    // PIN is valid and is not taken
                    if (pinNumber_state) {
                        // proceed with form submission
                        $.ajax({
                            url: '../../php/admin/Parent_Info.php',
                            type: 'post',
                            data: {
                                'save': 1,
                                'p1_fname': p1_fname,
                                'p1_lname': p1_lname,
                                'p2_fname': p2_fname,
                                'p2_lname': p2_lname,
                                'pin': pin,
                            },
                            success: function(response) {
                                clearFields();
                                closeForm();
                                deleteTable();
                                location.reload();
                            }
                        });
                    }
                }
            });
        }
    });
});

// Validates the entered pin has the following criteria
// 1. Not empty
// 2. At least 4 digits long
// 3. Numeric
function validPIN() {
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

// When Add Parent(s) is clicked
// Displays the add parent form
function addParentForm() {
    // Show the log form
    $('.add-parent-popup').show();
    $('.overlay').show();
    $('#edit-button').hide();
    $('#add-button').show();

    // Change text for edit form
    $("#header").text("Add Parent(s)");

    // Put focus in signature box
    $('#p1-fn-input').focus();
}

// When user clicks the close button in the top right corner of the 'Add Parent' form
function closeForm() {
    // Close the form after close button is clicked 
    $('.add-parent-popup').hide();
    $('.overlay').hide();
    clearFields();
}

// Removes the entire parent table from the screen
function deleteTable() {
    $("#parent-table").remove();
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
    // Show the log form
    $('.add-parent-popup').show();
    $('.overlay').show();
    $('#add-button').hide();
    $('#edit-button').show();

    // Change text for edit form
    $("#header").text("Edit Parent(s)");

    populateParentData(famID);
}

// Pre-populates data whenever edit button is clicked
function populateParentData(famID) {
    $.ajax({
        url: '../../php/admin/Parent_Info.php',
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
        }
    });
}
