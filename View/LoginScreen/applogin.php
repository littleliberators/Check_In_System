<?php
/*-------------------------------------------------------------------------
* Name: login.php                                                            *
* Description:  The login screen for parents and employees to login in to    *
*               their respective pages. Parents will login using their PIN   *
*               number. Employees will login using the admin username/       *
*               password credentials.  
*
---------------------------------------------------------------------------*/
//test comment for git username
    // Controller methods
    include('../../Controller/Login/applogin_process.php');
    
    // Store variables
    session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
    <script language="JavaScript" type="text/javascript" src="applogin.js"></script>
    <link href="applogin.css" type="text/css" rel="stylesheet" />
    <link href="../main.css" type="text/css" rel="stylesheet" />
    <link href = "calendar.css" type = "text/css" rel = "stylesheet" />
    <!-- Header -->
    <title>Little Liberators</title>
    <link rel="shortcut icon" href="../images/icon.ico" type="image/x-icon"/>
</head>

<body>
  <div id="header-background">
    <img id="title" src="../images/Block_Title2.png" alt="Little Liberators" />
  </div>
  <p id="description">Childcare Center</p>
       <div class = "message-field">
       Please login to access the app
        </div>
    <!-- left panel of page -->
     <div class="imgLeft">
         <img class="leftimg" src="../images/Left_Toys.png" align="Left" alt="Left Toys">
      </div>
      <div class="imgRight">
       <img class="rightimg" src="../images/Right_Toys.png" align="Right" alt="Right Toys">
      </div>
        
    <div class="container">
      <div id="panel-heading">
        <div id="tab-admin" class="not-selected" >
            <p>ADMIN</p>
          </div> 
      </div>
      <div id="panel-body">
        <form id="admin-form">
          <div id="instructions">Please enter username and password</div>
          <div class="input-container" id="username-container">
            <div class="input-label">Username:</div>
            <input autofocus class="input-field" id="username" name="username" type="text" placeholder="Enter username" required></input>
          </div>
          <div class="input-container" id="password-container">
            <div class="input-label">Password:</div>
            <input class="input-field" id="password" name="password" type="password" placeholder="Enter password" required></input>
          </div>
          <div id="admin-error" class="hide">
            The username and password do not match.<br>Please try again.
          </div>
          <div style="text-align:center;">
            <button id="admin-submit" name="admin-submit" type="button" onClick="adminLogin();">Login</button>
          </div>
        </form>
      </div>
    </div>
    <!--<div class = "content-right">-->
     <!-- right panel of page -->
      <!--<div class="imgRight">-->
      <!-- <img class="rightimg" src="../images/Right_Toys.png" align="Right" alt="Right Toys">-->
      <!--</div>-->
    <!--</div>-->
    <div class="footer">
        <a href="https://www.sbuniv.edu/" style="text-decoration: none;">
          <img class="logo" id="sbulogo" src="../images/SBUNew.png" alt="Southwest Baptist University"/>
        </a>
        <a href="http://bolivarschools.ss18.sharpschool.com" style="text-decoration: none;">
          <img class="logo" id="libslogo" src="../images/Logo.png" alt="Bolivar Liberators"/>
        </a>
      <div id="copyright">&copy 2019 Southwest Baptist University</div>
    </div>
  </body>
</html>

</div>