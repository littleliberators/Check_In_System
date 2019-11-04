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
    //when user clicks SUBMIT
    $('#submit').on('click', function() {
        createMessage();
    });
    
    // Force click button whenever enter is pressed
    $(document).keypress(function(e) {
        if (e.key === "Enter") {
            enterPressed();
            return false;
        }
    });
});

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