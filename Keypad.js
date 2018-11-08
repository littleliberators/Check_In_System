/* global $*/

$(function() {
    let buttons = $("#keypad button");
    buttons.each(function(index) {
        $(this).click(input);
    })
});

function input() {
    // Current pin value
    let pinVal = document.getElementById("PIN-textbox").value;

    // The back button was clicked
    if (this.innerText == "") {
        pinVal = pinVal.slice(0, -1);
    }
    // The OK button was clicked
    else if (this.innerText == "OK") {
        // This is where the pin needs to be validated and user taken to the next screen
    }
    // Any other number on the keypad was clicked
    else {
        let newVal = this.innerText;
        pinVal += newVal;
    }

    $("#PIN-textbox").val(pinVal);
}

function forgotPIN() {
    var popup = document.getElementById("myPopup");
    popup.classList.toggle("show");
}
