<?php
    include('parent_process.php');
    
    function populateParentTable() {
        include('../php_helpers/populateParentTable.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
    
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
    <script language="JavaScript" type="text/javascript" src="../../javascript/admin/Parent_Info.js"></script>
    <link href="../../css/admin/parent_info.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
</head>

<body>
    <div id="header-background">
        <button id="back" onClick="document.location.href='../Admin.php'">
            <i class="material-icons">arrow_back</i>
            <div class="header-buttons">BACK</div>
        </button>
        <img id="title" src="../../images/Block_Title2.png" alt="Little Liberators" />
        <button id="sign-out" onClick="document.location.href='../parent/Login.php'">
            <i class="material-icons">logout</i>
            <div class="header-buttons">LOGOUT</div>
        </button>
    </div>
    <div id="description">Parent Information</div>
    <div style="text-align:center;">
        <button id="add" onclick="addParentForm();"><i class='material-icons-add'>add</i>Add Parent(s)</button>
    </div>
    <div id="parent-container">
        <?php
            populateParentTable();
        ?>
    </div>
    
    <!-- Add parent popup -->
    <div class="overlay hide"></div>
    <div class="add-parent-popup hide">
        <div id="add-parent-header">
            <div id="header">Add Parent(s)</div>
            <button id="close-button" aria-label="Close" onClick="closeForm();">X</button>
        </div>
        <div id="sign-instructions">
            Please add parent first and last name(s) for one family.<br>
            <i>* Required fields</i>
        </div>
        <form id="add-parent">
            <div class="names-container">
                <div id="parent1-container">
                    <div class="parent-label">* Parent/Guardian 1</div>
                    <div class="name-label">* First Name: </div>
                    <input class="input-box" id="p1-fn-input" name="p1-first-name" type="text" required
                    oninvalid="this.setCustomValidity('Please enter first name'" oninput="this.setCustomValidity('')"/>
                    <div class="name-label">* Last Name: </div>
                    <input class="input-box" id="p1-ln-input" name="p1-last-name" type="text" required
                    oninvalid="this.setCustomValidity('Please enter last name'" oninput="this.setCustomValidity('')"/>
                </div>
                <div id="parent2-container">
                    <div class="parent-label">Parent/Guardian 2</div>
                    <div class="name-label">First Name: </div>
                    <input class="input-box" id="p2-fn-input" name="p2-first-name" type="text" 
                    oninvalid="this.setCustomValidity('Please enter first name'" oninput="this.setCustomValidity('')"/>
                    <div class="name-label">Last Name: </div>
                    <input class="input-box" id="p2-ln-input" name="p2-last-name" type="text" 
                    oninvalid="this.setCustomValidity('Please enter last name'" oninput="this.setCustomValidity('')"/>
                </div>
            </div>
            <div id="pin-container">
                <div id="pin-label">* PIN #: </div>
                <input class="input-box" id="PIN" name="PIN" type="text" pattern="\d{4,}" autocomplete="off" required
                oninvalid="this.setCustomValidity('4 digit PIN required.')" oninput="this.setCustomValidity('')"/>
            </div>
            <div class="pin-message-label hide" id="pin-message">PIN number taken</div>
            <button class="button" id="add-button" name="add-parent" type="button">Add</button>
            <button class="button" id="edit-button" name="edit-parent" type="button">Save Changes</button>
        </form>
    </div>
</body>