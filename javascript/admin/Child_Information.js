/*global $*/
/* global location */
var currentChildID = "";

$('document').ready(function() {
    $('#select-parent1').change(function() {
        $('#select-parent2').children().remove().end().append('<option selected value="select">-- Select Parent --</option>');

        if ($(this).val() == 'select') {}
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

    // When user clicks Save changes
    $('#edit-button').on('click', function() {
        saveChanges();
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
    $('#select-parent1').children().remove().end().append('<option selected value="select">-- Select Parent --</option>');
    $('#select-parent2').children().remove().end().append('<option selected value="select">-- Select Parent --</option>');
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
        async: false,
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
        async: false,
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
                document.getElementById('select-parent2').selectedIndex = 1;
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
            async: false,
            data: {
                'addChild': 1,
                'first_name': first_name,
                'last_name': last_name,
                'family_id': famID,
            },
            success: function(response) {
                if (response == "success") {
                    location.reload();
                }
                else {
                    alert("Unable to save child. " + response);
                    location.reload();
                }
            }
        });
    }
};

// When edit button is clicked for a child
// Displays the edit form for user to edit child information
function editForm(childID) {
    currentChildID = childID;
    
    // Show child form
    addChildForm();
    $('#add-button').hide();
    $('#edit-button').show();

    // Change text for edit form
    $("#header").text("Edit Child");

    // Populate with selected child data
    populateChildData(childID);
}

// Pre-populates data whenever edit button is clicked
function populateChildData(childID) {
    $.ajax({
        url: '../../php/admin/Child_Information.php',
        type: 'post',
        async: false,
        data: {
            'populate': 1,
            'child_ID': childID,
        },
        // dataType:'json',
        success: function(response) {
            // alert("got here 1");
            // Get the data from the server
            var result = $.parseJSON(response);
            // alert("got here 2");
            if (result[0] === "error") {
                alert("There was a problem with the child information.");
            }
            else {
                populateSelectParents(result);
            }
        }
    });
}

// Chooses parents based on selected child
function populateSelectParents(info) {
    // Populate the first and last namefields
    $('#child-first-input').val(info[0]);
    $('#child-last-input').val(info[1]);

    var p1Name = info[2];
    var p2Name = info[3];
    var famID = info[4];
    var parent1dd = document.getElementById('select-parent1');
    var parent2dd = document.getElementById('select-parent2');

    // Populate parent 1
    for (var i = 0; i < parent1dd.options.length; i++) {
        if (parent1dd.options[i].text == p1Name) {
            parent1dd.selectedIndex = i;
            break;
        }
    }

    // Populate parent 2
    addSecondParent(famID);
}

// Save any changes that user made to child information
function saveChanges() {
    if (validateFields()) {
        var first_name = $('#child-first-input').val();
        var last_name = $('#child-last-input').val();
        var famID = $('#select-parent1').find('option:selected').attr("data-familyid");
        
        // proceed with form submission
        $.ajax({
            url: '../../php/admin/Child_Information.php',
            type: 'post',
            async: false,
            data: {
                'update': 1,
                'first_name': first_name,
                'last_name': last_name,
                'child_id': currentChildID,
                'famID': famID,
            },
            success: function(response) {
                if (response == "success") {
                    clearFields();
                    closeForm();
                    deleteTable();
                    location.reload();
                }
                else {
                    alert("Unable to update child information. - " + response);
                }
            }
        });
    }
}

// Allows user to verify deletion
function deleteChildPopup(childID) {
    //Set up the dialog box
    $("#dialog").dialog({
        minWidth: 400,
        minHeight: 'auto',
        autoOpen: false,
        buttons: {
            "Yes": function() {
                deleteChild(childID);
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

// Deletes child when user clicks yes
function deleteChild(childID){
    $.ajax({
        url: '../../php/admin/Child_Information.php',
        type: 'post',
        data: {
            'delete': 1,
            'childID': childID,
        },
        success: function(response) {
            if (!(response == "success")){
                alert(response);
            }
            else {
                location.reload();
            }
        }
    });
}
