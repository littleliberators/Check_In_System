<?php
/*-------------------------------------------------------------------------
* Name: info_parent.php                                                       *
* Description:  Page to view, edit, add, and delete parents. After clicking   *
*               the parent tab on admin screen, a table of all of the         *
*               parents will be automatically pulled up.                      *
---------------------------------------------------------------------------*/
    include('../../Controller/Admin/parent_process.php');
    
    function populateParentTable() {
        include('../../Controller/Admin/populateParentTable.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
    
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">    
    <script language="JavaScript" type="text/javascript" src="info_parent.js"></script>
    <link href="info_parent.css" type="text/css" rel="stylesheet" />
    <link href="../main.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
    
    <!-- Header -->
    <title>Parent Information</title>
    <link rel="shortcut icon" href="../images/icon.ico" type="image/x-icon"/>
</head>

<body>
    <div id="header-background">
        <button id="back" onClick="document.location.href='admin.php'">
            <i class="material-icons">arrow_back</i>
            <div class="header-buttons">BACK</div>
        </button>
        <img id="title" src="../images/Block_Title2.png" alt="Little Liberators" />
        <button id="sign-out" onClick="document.location.href='../LoginScreen/login.php'">
            <i class="material-icons">logout</i>
            <div class="header-buttons">LOGOUT</div>
        </button>
    </div>
    <div id="description">Parent Information</div>
    <div id="parent-container">
        <div id="parent-header-container">
            <button class="button-add" id="add" onclick="addParentForm();"><i class='material-icons-add'>add</i>Add Parent(s)</button>
            <div id="search">
                <input type="text" id="search-input" placeholder="Search.."></input>
            </div>
        </div>
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
        <div id="sign-instructions" class="instructions">
            Please add parent first and last name(s) for one family.<br>
        </div>
        <div class="instructions"><i>* Required fields</i></div>
        <form id="add-parent">
            <div class="names-container">
                <div id="parent1-container">
                    <div class="parent-label">* Parent/Guardian 1</div>
                    <div class="name-label">* First Name: </div>
                    <input class="input-box" id="p1-fn-input" name="p1-first-name" type="text" required>
                    <div class="name-label">* Last Name: </div>
                    <input class="input-box" id="p1-ln-input" name="p1-last-name" type="text" required>
                </div>
                <div id="parent2-container">
                    <div class="parent-label">Parent/Guardian 2</div>
                    <div class="name-label">First Name: </div>
                    <input class="input-box" id="p2-fn-input" name="p2-first-name" type="text">
                    <div class="name-label">Last Name: </div>
                    <input class="input-box" id="p2-ln-input" name="p2-last-name" type="text">
                </div>
            </div>
            <div id="pin-container">
                <div id="pin-label">* PIN #: </div>
                <input class="input-box" id="PIN" name="PIN" type="text" pattern="\d{4,}" autocomplete="off" required>
            </div>
            <div class="pin-message-label hide" id="pin-message">PIN number taken</div>
            <button class="button" id="add-button" name="add-parent" type="button">Add</button>
            <button class="button" id="edit-button" name="edit-parent" type="button">Save Changes</button>
        </form>
    </div>
    
    <!-- Are you sure you want to delete popup -->
    <div class="hide" id="dialog" title="Delete">
      Are you sure you want to delete?
    </div>
</body>