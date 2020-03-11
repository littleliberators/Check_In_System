<?php
/* -------------------------------------------------------------------------
* Name: parent.php                                                            *
* Description:  This page is called after a parent successfully logs in with  *
*               their PIN number. The children associated with the PIN will   *
*               be pulled up and the parent will have the option to sign them *
*               in/out.                                                       *
---------------------------------------------------------------------------*/


// To retrieve global variables
session_start();

include('../../Controller/Parent/parent_process.php');
?>

<!DOCTYPE html>
<html>

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script language="JavaScript" type="text/javascript" src="parent.js"></script>
    <link href="parent.css" type="text/css" rel="stylesheet" />
    <link href="timeLog.css" type="text/css" rel="stylesheet" />
    <link href="../main.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    
    <!-- Header -->
    <title>Check In/Out</title>
    <link rel="shortcut icon" href="../images/icon.ico" type="image/x-icon"/>
</head>

<body>
    <div id="header-background">
        <img id="title" src="../images/Block_Title2.png" alt="Little Liberators" />
        <button id="sign-out" onClick="document.location.href='../LoginScreen/login.php'">
            <i class="material-icons">logout</i>
            <div class="header-buttons">LOGOUT</div>
        </button>
    </div>
    <div class="row-child" id="instructions">Please select the child(ren) to check in/out</div>
    <div id="imgLeft">
      <img id="leftimg" src="../images/Left_Toys.png" alt="Left Toys">
    </div>
    <div id="imgRight">
      <img id="rightimg" src="../images/Right_Toys.png" alt="Right Toys">
    </div>
    
    <div id="child-wrapper">
        <form class="select-student" id="form-out">
            <div class="container-label" id="checked-out-label">Checked Out</div>
            <div class="row-child" id="child-container-out">
                <div class="check-all-row" id="check-all-row-out">
                    <input type="checkbox" name="select-all-out" id="select-all-out" value="Select All">
                    <label class="label" id="select-all-out-label" for="select-all-out">Select All</label>
                </div>
                <div class="checkbox-container" id="checkboxes-out">
                    <?php
                        populateSignedOut();
                    ?>
                </div>
            </div>
            <div class="error-message-container">
                <div class="error-message hide" id="please-select-out">Please select at least one child.</div>
            </div>
            <div class="row-child" id="child-out-btn">
                <input type="button" class="sign-btn" id="sign-in-btn" name="signinbutton" onclick="checkInForm();" value="Click to Check In">
            </div>
        </form>
        
        <form class="select-student" id="form-in">
            <div class="container-label" id="checked-in-label">Checked In</div>
            <div class="row-child" id="child-container-in">
                <div class="check-all-row" id="check-all-row-in">
                    <input type="checkbox" name="select-all-in" id="select-all-in" value="Select All">
                    <label class="label" id="select-all-in-label" for="select-all-in">Select All</label>
                </div>
                <div class="checkbox-container" id="checkboxes-in">
                    <?php
                        populateSignedIn();
                    ?>
                </div>
            </div> 
            <div class="error-message-container">
                <div class="error-message hide" id="please-select-in">Please select at least one child.</div>
            </div>
            <div class="row-child" id="child-in-btn">
                <input type="button" class="sign-btn" id="sign-out-btn" name="signoutbutton" onclick="checkOutForm();" value="Click to Check Out">
            </div>
        </form>
        
        <div id="description">Sunshine</div>
        <div id="sunshine-container">
            <div id="sunshine-header-container">
                <div id="add-button" style="text-align:center;">
                    <button class="button-add" id="add" onclick="addLogForm('add');"><i class='material-icons-add'>add</i>Add New Log</button>
                </div>
            </div>
            <!-- Add log table -->
            <div class='table-container'>
                <?php populateLogTable(""); ?>
            </div>
        </div>
        
        <?php
            postValidations();
        ?>
    </div>
    
    <div id="success" class="fade hide">Success</div>
    <div class="overlay hideform"></div>
    
    <!-- Time log popup -->
     <div class="log-time-popup hideform" >
        <div id="log-time-header">
            <div id="header">Log Time</div>
            <button id="close-button" aria-label="Close" >X</button>
        </div>
        <form id="log-time">
            <div class="row" id="date-time-container">
                <div class="text-label" id="day-label">Day:</div>
                <input class="input-box" id="date-input" name="date" type="date" required readonly>
                <div class="text-label" id="time-label">Time:</div>
                <input class="input-box" id="time-input" name="time" type="time" required readonly>
            </div>
            <div class="row">
                <div class="text-label" id="sign-instructions">
                    Please type your name in below to sign electronically.
                </div>
            </div>
            <div class="row">
                <div id="e-sign-container">
                    <div id="sign-here">X</div>
                    <input id="e-sign-input" name="e-sign" type="text" autocomplete="off">
                </div>
            </div>
            <div class="hide" id="form-error">Please enter an electronic signature.</div>
            <div>
                <input type="button" id="submit-log" name="submit" value="Submit" onclick="submitForm();">
            </div>
        </form>
    </div> 
    
    <!-- REminder popup -->
    <div class="reminder-popup">
        <div id="reminder-header">
            <div id="header">REMINDER</div>
            <button id="x-button" aria-label="Close" onClick="closeForm();">X</button>
        </div>
        <div id="reminder-container">
            <?php
                populateReminder();
            ?>
        </div>
    </div>
    
    <!-- Prompt logout -->
    <div class="hide" id="dialog" title="Success!">
      <div id="successMessage"></div>
    </div>
    
     <!-- Add log popup -->
    <div class="add-log-popup hide">
        <div id="add-log-header">
            <div id="header">Add Child</div>
            <button id="close-button" aria-label="Close" onClick="closeForm();">X</button>
        </div>
        <div id="sign-instructions" class="instructions">
            Create a new time log for a child.<br>
        </div>
        <div class="instructions"><i>* Required fields</i></div>
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
</body>

</html>

