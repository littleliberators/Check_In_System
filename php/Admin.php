<!DOCTYPE html>
<html lang="en">
    
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
    <link href="../css/admin.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>

<body>
    <div id="header-background">
        <img id="title" src="../images/Block_Title2.png" alt="Little Liberators" />
        <button id="sign-out" onClick="document.location.href='Login.php'">
            <i class="material-icons">logout</i>
            <div class="header-buttons">LOGOUT</div>
        </button>
    </div>
    <p id="description">Admin</p>
    <div id="menu-container">
        <div id="parent-info-box" class="box" onClick="document.location.href='admin/Parent_Info.php'">
            <img class="icon" src="../images/admin_images/couple.png" alt="parents" />
            <div class="title">Parent<br>Information</div>
            <div class="subtitle">Add Parent(s)</div>
            <div class="subtitle">Edit Parent(s)</div>
        </div>
        <div id="child-info-box" class="box" onClick="document.location.href='admin/Child_Information.php'">
            <img class="icon" src="../images/admin_images/kids.png" alt="kids" />
            <div class="title">Child<br>Information</div>
            <div class="subtitle">Add Child</div>
            <div class="subtitle">Edit Child</div>
        </div>
        <div id="time-log-box" class="box" onClick="document.location.href='admin/Log_Info.php'">
            <img class="icon" src="../images/admin_images/clock.png" alt="clock" />
            <div class="title">Time Log<br>Information</div>
            <div class="subtitle">Add Log for Child</div>
            <div class="subtitle">Edit Log(s)</div>
        </div>
        <div id="reports-box" class="box" onClick="document.location.href='admin/Reports.php'">
            <img class="icon" src="../images/admin_images/report.png"alt="reports" />
            <div class="title">Print<br>Reports</div>
            <div class="subtitle">Print Sign-in Sheets</div>
        </div>
        <div id="finance-box" class="box" onClick="document.location.href='admin/Finance_Info.php'">
            <img class="icon" src="../images/admin_images/money.png"alt="reports" />
            <div class="title">Finance<br>Information</div>
            <div class="subtitle">View Payments</div>
            <div class="subtitle">Edit Payments</div>
        </div>
    </div>
</body>