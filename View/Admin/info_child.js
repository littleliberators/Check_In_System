/*-------------------------------------------------------------------------
* Name: info_child.js                                                        *
* Description:  Scripts to handle changes made to the UI on the child info   *
                page, such as button clicks (Add, Edit, Delete, Back,        *
                Logout).                                                     *
---------------------------------------------------------------------------*/

/*global $*/
/* global location */
var currentChildID = "";

$('document').ready(function() {
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
    
    $('#select-parent1').change(function() {
        $('#select-parent2').children().remove().end().append('<option selected value="select">-- Select Parent --</option>');
        $("#select-parent2").css("color", "graytext");

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
    
    // Hide child popup when clicked outside of form
    $(document).mouseup(function(e) {
        var container = $(".add-child-popup");

        // if the target of the click isn't the container nor a descendant of the container
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            closeForm();
        }
    });
});

// When user clicks the close button in the top right corner of the 'Add Parent' form
function closeForm() {
    $('.add-child-popup').hide();
    $('.overlay').hide();
    clearFields();
}

// Clears all of the fields in popup screen
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
    // Show the child form
    $('.add-child-popup').show();
    $('.overlay').show();
    $('#edit-button').hide();
    $('#add-button').show();

    // Change text for add form title bar
    $("#header").text("Add Child");
    
    // Change text for instructions
    $("#sign-instructions").text("Please add child first and last names, and select the parent(s) for the child.");

    // Put focus in first name box
    $('#child-first-input').focus();
    
    // Add all available parent options from database to dropdown
    populateParents();
}

// Add the list of parent names to drop down
function populateParents() {
    $.ajax({
        url: 'info_child.php',
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
                    var html = "<option value=\"" + key + "\" data-familyid=\"" + row["Family_ID"] + "\">" + row['First_Name'] + " " + row['Last_Name'] + "</option>";
                    $("#select-parent1").append(html);
                });
            }
        }
    });
}

// After user selects Parent 1, add Parent 2 option
function addSecondParent(famID) {
    $.ajax({
        url: 'info_child.php',
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
                $("#select-parent2").css("color", "black");
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
            url: 'info_child.php',
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

    // Change text for edit form title bar
    $("#header").text("Edit Child");
    
    // Change text for instructions
    $("#sign-instructions").text("Please make any changes and click Save Changes.");

    // Populate with selected child data
    populateChildData(childID);
}

// Pre-populates data whenever edit button is clicked
function populateChildData(childID) {
    $.ajax({
        url: 'info_child.php',
        type: 'post',
        async: false,
        data: {
            'populate': 1,
            'child_ID': childID,
        },
        success: function(response) {
            // Get the data from the server
            var result = $.parseJSON(response);
            populateSelectParents(result);
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
            url: 'info_child.php',
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
        url: 'info_child.php',
        type: 'post',
        data: {
            'delete': 1,
            'childID': childID,
        },
        success: function(response) {
            if (!(response == "success")){
                alert(response);
                location.reload();
            }
            else {
                location.reload();
            }
        }
    });
}