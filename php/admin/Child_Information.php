<?php
    include('child_process.php');

    function populateChildTable(){
        include('../php_helpers/populateChildTable.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
    
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script language="JavaScript" type="text/javascript" src="../../javascript/admin/Child_Information.js"></script>
    <link href="../../css/admin/child_info.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
</head>

<body>
    <div id="header-background">
        <button id="back" onClick="document.location.href='../Admin.php'">
            <i class="material-icons">arrow_back</i>
            <div class="header-buttons">BACK</div>
        </button>
        <img id="title" src="../../images/Block_Title2.png" alt="Little Liberators" />
        <button id="sign-out" onClick="document.location.href='../Login.php'">
            <i class="material-icons">logout</i>
            <div class="header-buttons">LOGOUT</div>
        </button>
    </div>
    <div id="description">Child Information</div>
    <div style="text-align:center;">
        <button id="add" onclick="addChildForm();"><i class='material-icons-add'>add</i>Add Child</button>
    </div>
    <div id="child-container">
        <?php
            populateChildTable();
        ?>
    </div>
    
    <!-- Add child popup -->
    <div class="overlay hide"></div>
    <div class="add-child-popup hide">
        <div id="add-child-header">
            <div id="header">Add Child</div>
            <button id="close-button" aria-label="Close" onClick="closeForm();">X</button>
        </div>
        <div id="sign-instructions">
            Please add child first and last names, and select the parent(s) for the child.<br>
            <i>* Required fields</i>
        </div>
        <form id="add-child">
            <div class="names-container">
                <div>
                    <div class="name-label" id="child-first-label">* First Name: </div>
                    <input class="input-box" id="child-first-input" name="child-fn" type="text" required
                    oninvalid="this.setCustomValidity('Please enter first name'" oninput="this.setCustomValidity('')"/>
                </div>
                <div>
                    <div class="name-label" id="child-last-label">* Last Name: </div>
                    <input class="input-box" id="child-last-input" name="child-ln" type="text" required
                    oninvalid="this.setCustomValidity('Please enter last name'" oninput="this.setCustomValidity('')"/>
                </div>
            </div>
            <div class="parents-container">
                <div>
                    <div class="name-label" id="parent-1-label">* Parent/Guardian 1</div>
                    <select class="select-box" id="select-parent1">
                        <option value="select">-- Select Parent --</option>
                    </select>
                </div>
                <div>
                    <div class="name-label" id="parent-2-label">Parent/Guardian 2</div>
                    <select class="select-box" id="select-parent2" disabled>
                        <option value="select">-- Select Parent --</option>
                    </select>
                </div>
            </div>
            <div class="error-message-label hide" id="error-message">PIN number taken</div>
            <button class="button" id="add-button" name="add-parent" type="button">Add</button>
            <button class="button" id="edit-button" name="edit-parent" type="button">Save Changes</button>
        </form>
    </div>
    
    <!-- Are you sure you want to delete popup -->
    <div class="hide" id="dialog" title="Delete">
      Are you sure you want to delete?
    </div>
</body>