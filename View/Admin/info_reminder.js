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
    
    // $('#select-parent').multiselect({
    //     includeSelectAllOption: true
    // });
    
    //when user clicks SUBMIT
    $('#submit').on('click', function() {
        createReminder();
    });
    $('#delete').on('click', function() {
        deleteMessage();
    });
    $("#select-parent").change(function(){
        getReminder();
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
    $("#select-parent").empty();
    $("#select-parent").append('<option selected value="select">-- Select Parent --</option>');
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
                    if(row["Reminder"] == null){
                        var html = "<option value=\"" + row["Parent_ID"] + "\">" + row['First_Name'] + " " + row['Last_Name'] + "</option>";
                        $("#select-parent").append(html);
                    }
                    else{
                        var html = "<option value=\"" + row["Parent_ID"] + "\">" + row['First_Name'] + " " + row['Last_Name'] + " (R) </option>";
                        $("#select-parent").append(html);
                    }
                });
                getReminder();
            }
        }
    });
}

//create parent reminder
function createReminder() {
    if (validateFields()) {
        var reminder = $('#reminder-text').val();
        var parentId = $('#select-parent').val();
    $.ajax({
            url: 'info_reminder.php',
            type: 'POST',
            async: false,
            data: {
                'createReminder': 1,
                'reminder': reminder,
                'parentID': parentId,
            },
            success: function(response) {
                if (response == "success") {
                    getReminder();
                    populateParent();
                    successPopup("Successfully created reminder");
                }
                else {
                    alert("Unable to create reminder. " + response);
                }
            }
        });
    }
};

//Returns the current reminder for a selected parent
function getReminder() {
    $('#current-message').html('');
    var parentId = $('#select-parent').val();
    if(parentId !='select'){
        $.ajax({
                url: 'info_reminder.php',
                type: 'POST',
                async: false,
                data: {
                    'getReminder': 1,
                    'parentID': parentId,
                },
                success: function(response) {
                    if(response.charAt(0) == '"'){
                        const result = response.split('"');
                        var div = document.getElementById("current-message");
                        var text = document.createTextNode(result[1]);
                        div.appendChild(text);
                    }
                    else
                    {
                        var div = document.getElementById("current-message");
                        var text = document.createTextNode("No Message Saved");
                        div.appendChild(text);
                    }
                }
            });
    }
    
};

// Checks which button should be force clicked when enter is pressed
function enterPressed(){
    if ($('#submit:visible').length > 0) {
        $("#submit").click();
        createReminder();
    }
}
// Validate none of the fields are empty 
function validateFields() {
    var message = $('#reminder-text').val();
    
    if (message == "") {
        showError('Please enter a message');
        return false;
    }
    else {
        return true;
    }
}


//deletes announcements from home screen
function deleteMessage() {

    var parentId = $('#select-parent').val();

    $.ajax({
            url: 'info_reminder.php',
            type: 'POST',
            async: false,
            data: {
                'deleteMessage': 1,
                'parentID': parentId,
            },
            success: function(response) {
                if (response == "success") {
                    getReminder();
                    populateParent();
                    successPopup("Successfully deleted reminder");
                }
                else {
                    alert("Unable to delete reminder. " + response);
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
    hideSuccess();
}

function hideSuccess()
{
    $('#success').removeClass("hide");
    setTimeout(function(){ $('#success').addClass("hide"); }, 3000);
}
