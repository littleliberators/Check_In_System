<?php
    function populateLogTable(){
        include('../php_helpers/populateLogTable.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
    
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
    <!--<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->
    <!--<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">-->
    <script language="JavaScript" type="text/javascript" src="../../javascript/admin/Log_Info.js"></script>
    <link href="../../css/admin/log_info.css" type="text/css" rel="stylesheet" />
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
    <div style="text-align:center;">
        <button id="add" onclick="addChildForm();"><i class='material-icons-add'>add</i>Add New Log</button>
    </div>
    <div id="log-container">
        <div id="search">
            <!--<div id="search-label">Search: </div>-->
            <input type="text" id="search-input" placeholder="Search all logs"></input>
            <i id="search-icon" class="material-icons">search</i>
        </div>
        <?php
            populateLogTable();
        ?>
    </div>
    
</body>