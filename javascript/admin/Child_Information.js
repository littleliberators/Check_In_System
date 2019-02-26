/*global $*/
/* global location */

$('document').ready(function() {
    $('#select-parent1').change(function() {
        $('#select-parent2').children().remove().end().append('<option selected value="select">-- Select Parent --</option>');

        if ($(this).val() == 'select') {
        }
        else {
            var element = $(this).find('option:selected');
            var famID = element.attr("data-familyid");
            addSecondParent(famID);
        }
    });

    // When user clicks Add 
    $('#add-button').on('click', function() {
        addChild();
    });
});

// When user clicks the close button in the top right corner of the 'Add Parent' form
function closeForm() {
    // Close the form after close button is clicked 
    $('.add-child-popup').hide();
    $('.overlay').hide();
    clearFields();
}

function clearFields() {
    $('#child-first-input').val('');
    $('#child-last-input').val('');
    $('#error-message').hide();
}

// When Add Child is clicked
// Displays the add child form
function addChildForm() {
    // Show the log form
    $('.add-child-popup').show();
    $('.overlay').show();
    $('#edit-button').hide();
    $('#add-button').show();

    // Change text for edit form
    $("#header").text("Add Child");

    // Put focus in first name box
    $('#child-first-input').focus();

    populateParents();
}

// Add the list of parent names to drop down
function populateParents() {
    $.ajax({
        url: '../../php/admin/Child_Information.php',
        type: 'post',
        dataType: "json",
        data: {
            'populateParents': 1,
        },
        success: function(parents) {
            if (parents == "") {
                $('#error-message').show();
                $('#error-message').addClass("error");
                $('#error-message').text('There are no parents saved in the database');
            }
            else {
                $.each(parents, function(key, row) {
                    // retrieve value and text from ajax
                    var html = "<option value=\"" + key + "\" data-familyid=\"" + row["Family_ID"] + "\">" + row['Last_Name'] + ", " + row['First_Name'] + "</option>";
                    $("#select-parent1").append(html);
                });
            }
        }
    });
}

// After user selects Parent 1, add Parent 2 option
function addSecondParent(famID) {
    $.ajax({
        url: '../../php/admin/Child_Information.php',
        type: 'post',
        data: {
            'addSecondParent': 1,
            'famID': famID,
        },
        success: function(parent) {
            if (parent == "none") {
                // Do nothing, there is no parent 2 in db
            }
            else {
                var html = "<option value='0' data-familyid=\"" + famID + "\">" + parent + "</option>";
                $("#select-parent2").append(html);
            }
        }
    });
}

// Validate none of the fields are empty
function validateFields() {
    var first_name = $('#child-first-input').val();
    var last_name = $('#child-last-input').val();
    var parent = $('#select-parent1').val();

    if (first_name == "") {
        $('#error-message').show();
        $('#error-message').addClass("error");
        $('#error-message').text('Please enter a first name');
        return false;
    }
    else if (last_name == "") {
        $('#error-message').show();
        $('#error-message').addClass("error");
        $('#error-message').text('Please enter a last name');
        return false;
    }
    else if (parent == "select") {
        $('#error-message').show();
        $('#error-message').addClass("error");
        $('#error-message').text('Please choose Parent/Guardian 1');
        return false;
    }
    else {
        return true;
    }
}

// Add a new child to the db
function addChild() {
    if (validateFields()) {
        var first_name = $('#child-first-input').val();
        var last_name = $('#child-last-input').val();
        var famID = $('#select-parent1').find('option:selected').attr("data-familyid");

        // proceed with form submission
        $.ajax({
            url: '../../php/admin/Child_Information.php',
            type: 'post',
            data: {
                'addChild': 1,
                'first_name': first_name,
                'last_name': last_name,
                'family_id': famID,
            },
            success: function(response) {
                if (response == "success") {
                    clearFields();
                    closeForm();
                    deleteTable();
                    location.reload();
                }
                else {
                    alert("Unable to save child. " + response);
                    clearFields();
                    closeForm();
                    deleteTable();
                    location.reload();
                }
            }
        });
    }
};

// Removes the entire parent table from the screen
function deleteTable() {
    $("#child-table").remove();
}
