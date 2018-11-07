/* global $*/

function load() {
    var array = new Array();
 
    while (array.length < 10) {
        var temp = Math.round(Math.random() * 9);
        if (!contain(array, temp)) {
            array.push(temp);
        }
    }
    for (var i = 0; i < 10; i++) {
        var keyButton = document.getElementById("keyButton" + i); //Assuming the ID of the Keys
        keyButton.value = array[i];
    }
}

function Input(e) 
{
    var keyInput = document.getElementById("keyInput");
    keyInput.value = keyInput.value + e.value;
}
 
function Delete() 
{
    var keyInput = document.getElementById("keyInput");
    keyInput.value = keyInput.value.substr(0, keyInput.value.length - 1);
}



