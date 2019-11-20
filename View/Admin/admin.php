<!-------------------------------------------------------------------------
* Name: admin.php                                                            *
* Description:  After the admin logs in successfully, they will be able to   *
*               do different tasks based on the buttons provided (Add        *
*               parent, add child, add log, generate report, view finances)  *
--------------------------------------------------------------------------->
<!DOCTYPE html>
<html lang="en">
    
<head>
    <link href="admin.css" type="text/css" rel="stylesheet" />
    <link href="../main.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    
    <!-- Header -->
    <title>Admin</title>
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
    <p id="description">Admin</p>
    <div id="menu-container">
        <div id="parent-info-box" class="box" onClick="document.location.href='info_parent.php'">
            <img class="icon" src="images/couple.png" alt="parents" />
            <div class="title">Parent<br>Information</div>
            <div class="subtitle">Add parent(s)</div>
            <div class="subtitle">Edit parent(s)</div>
        </div>
        <div id="child-info-box" class="box" onClick="document.location.href='info_child.php'">
            <img class="icon" src="images/kids.png" alt="kids" />
            <div class="title">Child<br>Information</div>
            <div class="subtitle">Add child</div>
            <div class="subtitle">Edit child</div>
        </div>
        <div id="time-log-box" class="box" onClick="document.location.href='info_logs.php'">
            <img class="icon" src="images/clock.png" alt="clock" />
            <div class="title">Time Log<br>Information</div>
            <div class="subtitle">Add log for child</div>
            <div class="subtitle">Edit log(s)</div>
        </div>
        <div id="reports-box" class="box" onClick="document.location.href='info_reports.php'">
            <img class="icon" src="images/report.png"alt="reports" />
            <div class="title">Print<br>Reports</div>
            <div class="subtitle">Create reports</div>
            <div class="subtitle">Print reports</div>
        </div>
      
        <!----------------------------------------------------------------------
        The below commented code will create a tab for the finance portion on the admin
        home page. Uncomment this code to see it show up on the admin page. The admin.css
        file will need to be modified to align all of the tabs together. 
        Clicking on the finance tab will take you to the info_finance.php, where the
        code for the finance info should be added.
        ------------------------------------------------------------------------>
        <div id="message-box" class="box" onClick="document.location.href='info_message.php'">
        <img class="icon" src="images/money.png"alt="reports" />
        <div class="title">Create<br>Announcement</div>
        
        <div class="subtitle">Create Message </div>
        <div class="subtitle">Delete Message</div>
        </div>
    </div>
</body>