<?php
    include('log_process.php');

    function populateLogTable(){
        include('../php_helpers/populateLogTable.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
    
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script language="JavaScript" type="text/javascript" src="../../javascript/admin/Log_Info.js"></script>
    <link href="../../css/admin/admin_all.css" type="text/css" rel="stylesheet" />
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
    <div id="description">Time Log Information</div>
    <div id="log-container">
        <div id="log-header-container">
            <div id="add-button" style="text-align:center;">
                <button class="button-add" id="add" onclick="addLogForm();"><i class='material-icons-add'>add</i>Add New Log</button>
            </div>
            <div id="search">
                <!--<div id="search-label">Search: </div>-->
                <input type="text" id="search-input" placeholder="Search all logs"></input>
                <i id="search-icon" class="material-icons">search</i>
            </div>
        </div>
        <?php
            populateLogTable();
        ?>
    </div>
    
     <!-- Add child popup -->
    <div class="overlay hide"></div>
    <div class="add-log-popup hide">
        <div id="add-log-header">
            <div id="header">Add Child</div>
            <button id="close-button" aria-label="Close" onClick="closeForm();">X</button>
        </div>
        <div id="instructions">
            Create a new time log for a child.<br>
            <i>* Required fields</i>
        </div>
        <form id="add-log-form">
            <div class="date-container">
                <div class="label" id="date-label">* Date: </div>
                <input class="input-box" id="date-input" name="date-input" type="date"/>
            </div>
            <div class="child-container">
                <div class="label" id="name-label">* Name: </div>
                <select class="select-box" id="select-child">
                    <option value="select">-- Select Child --</option>
                </select>
            </div>
            <div class="sign-in-container">
                <div class="sign-label">SIGN IN</div>
                <div class="label" id="sign-in-time-label">Time:</div>
                <input class="input-box" id="sign-in-time" name="sign-in-time" type="time"/>
                <div class="label" id="sign-in-signature-label">Signature:</div>
                <input class="input-box" id="sign-in-signature" name="sign-in-signature" type="text"/>
            </div>
            <div class="sign-out-container">
                <div class="sign-label">SIGN OUT</div>
                <div class="label" id="sign-out-time-label">Time:</div>
                <input class="input-box" id="sign-out-time" name="sign-out-time" type="time"/>
                <div class="label" id="sign-out-signature-label">Signature:</div>
                <input class="input-box" id="sign-out-signature" name="sign-out-signature" type="text"/>
            </div>
            <div class="error-message-label hide" id="error-message">PIN number taken</div>
            <button class="button" id="add-log-button" name="add-log" type="button">Add</button>
            <button class="button" id="edit-button" name="edit-parent" type="button">Save Changes</button>
        </form>
    </div>
    
    <!-- Are you sure you want to delete popup -->
    <div class="hide" id="dialog" title="Delete">
      Are you sure you want to delete?
    </div>
</body>