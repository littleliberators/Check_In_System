<?php
/*-------------------------------------------------------------------------
* Name: info_reminder.php                                                      *
* Description:  Admin will be able to view and edit finance info.             *
---------------------------------------------------------------------------*/

    // Controller files
   include('../../Controller/Admin/reminder_process.php');
   
   session_start();
?>

<!DOCTYPE html>
<html lang="en">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    
    <!-- Bootstrap multi-select -->
        <link rel="stylesheet" href="bootstrap_multi-select/bootstrap-3.3.2.min.css" type="text/css" />
        <link rel="stylesheet" href="bootstrap_multi-select/bootstrap-multiselect.css" type="text/css" />
        <script type="text/javascript" src="bootstrap_multi-select/bootstrap-3.3.2.min.js"></script>
        <script type="text/javascript" src="bootstrap_multi-select/bootstrap-multiselect.js"></script>
    
    <!-- Stylesheets, JavaScript -->
    <script language="JavaScript" type="text/javascript" src="info_reminder.js"></script>
    <link href="../main.css" type="text/css" rel="stylesheet" />
    <link href="info_reminder.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
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
  </body>
  
  <div id="success" class="hide">Success</div>
  <div class="overlay hide"></div>
  
  <div id="description">Create Reminder</div>
  <div id="announce-form" class="input" align="center">
    <input class="input-field" id="reminder-text" type="text" name="message" oninput="this.setCustomValidity('')"><br>
    <div class="filter-container" id="children-container">
        <div class="label" id="all-label">Choose parent:</div><br>
        <div class="select-container">
            <select class="select-box" id="select-parent" name="select-parent[]" ></select>
        </div>
    </div>
    <div class="label" id="all-label">Current Message:</div><br>
    
    <div class = "current-message" id="current-message">
        
    </div>

    
    <button class="myButton" id="submit" type="submit">SUBMIT</button>
    <button class="myButton" id="delete" type="delete">DELETE CURRENT ANNOUNCEMENT</button>

  </div>
  
</html>