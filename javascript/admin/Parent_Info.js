/* global $*/
/* global pin */
/* global location */
$('document').ready(function() {
    
    // Click add if enter is pressed
    $(document).keypress(function(e) {
        if (e.key === "Enter") {
            $("#add-button").click()
        }
    });

    var pinNumber_state = false;

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
                                $('#p1-fn-input').val('');
                                $('#p1-ln-input').val('');
                                $('#p2-fn-input').val('');
                                $('#p2-ln-input').val('');
                                $('#PIN').val('');
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

function addParentForm() {
    // Show the log form
    $('.add-parent-popup').show();
    $('.overlay').show();

    // Put focus in signature box
    $('#p1-fn-input').focus();
}

function closeForm() {
    // Close the form after close button is clicked 
    $('.add-parent-popup').hide();
    $('.overlay').hide();
}

function deleteTable() {
    $("#parent-table").remove();
}
