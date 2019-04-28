<?php
/*-------------------------------------------------------------------------
* Name: info_reports.php                                                      *
* Description:  Admin will be able to generate reports based on a date        *
*               range and which children to include.                          *
---------------------------------------------------------------------------*/

    require_once('../../Controller/tcpdf/tcpdf.php');
    include('../../Controller/Admin/reports_process.php');
    include('../../Controller/Admin/generateReport.php');
?>

<!DOCTYPE html>
<html lang="en">
        <!-- DateRangePicker -->
        <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
        
        <script language="JavaScript" type="text/javascript" src="info_reports.js"></script>
        <link href="info_reports.css" type="text/css" rel="stylesheet" />
        <link href="../main.css" type="text/css" rel="stylesheet" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
        
        <!-- Header -->
        <title>Create PDFs</title>
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
        <div id="description">Create Reports</div>
        <div id="filter-selections">
            <form method="post" target="_blank">
                <div class="filter-container" id="date-container">
                    <div class="label" id="date-label">Choose a date range:</div>
                    <input class="input-box" id="daterange" type="text" name="daterange" value="getTodaysDate();"/>
                </div>
                <div class="filter-container" id="children-container">
                    <div class="label" id="all-label">Choose all children or select one:</div>
                    <label class="checkbox-text">
                        <input id="select-all" type="checkbox" name="select-all" value="select-all"></input>
                        All children
                    </label>
                    <select class="select-box" id="select-child" name="select-child">
                        <option value="select">-- Select Child --</option>
                    </select>
                </div>
                <input id="button_validate" type="button" name="button_validate" value="Create PDF" />
                <input id="generate" type="submit" name="create_pdf" style="display:none;"/>
            </form>
        </div>
        <div class="error-message-label hide" id="error-message">Error</div>
    </body>
</html>
    