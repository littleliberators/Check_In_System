/*-------------------------------------------------------------------------
* Name: info_message.js                                                        *
* Description:  Scripts to handle changes made to the UI on the child info   *
*               page, such as button clicks (Submit, Back,        *
*               Logout, Search).                                             *
*               Scripts to pass child data into the Controller or diplay     *
*               data from the Controller (ajax functions).                   *  
---------------------------------------------------------------------------*/

/*global $*/

$('document').ready(function() {
    populateParent();
    
    $('#select-parent').multiselect({
        includeSelectAllOption: true
    });
    
    //when user clicks SUBMIT
    $('#submit').on('click', function() {
        createMessage();
    });
    $('#delete').on('click', function() {
        deleteMessage();
    });
    
    // Force click button whenever enter is pressed
    $(document).keypress(function(e) {
        if (e.key === "Enter") {
            enterPressed();
            return false;
        }
    });
});

function populateParent() {
    $.ajax({
        url: 'info_reminder.php',
        type: 'post',
        dataType: "json",
        async: false,
        data: {
            'populateParent': 1,
        },
        success: function(parent) {
            if (parent == "") {
                $('#error-message').show();
                $('#error-message').addClass("error");
                $('#error-message').text('There are no parents saved in the database.');
            }
            else {
                $.each(parent, function(key, row) {
                    var html = "<option value=\"" + row["Parent_ID"] + "\">" + row['First_Name'] + " " + row['Last_Name'] + "</option>";
                    $("#select-parent").append(html);
                });
            }
        }
    });
}

// Checks which button should be force clicked when enter is pressed
function enterPressed(){
    if ($('#submit:visible').length > 0) {
        $("#submit").click();
        createMessage();
    }
}
// Validate none of the fields are empty 
function validateFields() {
    var message = $('#announce-text').val();
    
    if (message == "") {
        showError('Please enter a message');
        return false;
    }
    else {
        return true;
    }
}
//creates message
function createMessage() {
    if (validateFields()) {
        var message = $('#announce-text').val();
    
    $.ajax({
            url: 'info_message.php',
            type: 'post',
            async: false,
            data: {
                'createMessage': 1,
                'message': message,
            },
            success: function(response) {
                if (response == "success") {
                    successPopup("Successfully created message");
                }
                else {
                    alert("Unable to create message. " + response);
                }
            }
        });
    }
};

//deletes announcements from home screen
function deleteMessage() {

    var message1 = ""; //had to do this for it to work, need to find a better way. Not even used really

    $.ajax({
            type: 'POST',
            url: 'info_message.php',
            async: false,
           data: {
                'createMessage': 1,
                'message': message1,
            },
            success: function(response) {
                if (response == "success") {
                    successPopup("Successfully deleted message");
                }
                else {
                    alert("Unable to delete message. " + response);
                }
            }
        });
}

// Shows an error on the form
function showError(message) {
    $('#error-message').show();
    $('#error-message').removeClass("success");
    $('#error-message').addClass("error");
    $('#error-message').text(message);
}

// Shows success message on the form
function showSuccess(message){
    $('#error-message').show();
    $('#error-message').removeClass("error");
    $('#error-message').addClass("success");
    $('#error-message').text(message);
}

// Shows a success popup after adding/editing/deleting
function successPopup(message) {
    $("#success").text(message);
    $("#success").show().delay(3000).hide(1);
}