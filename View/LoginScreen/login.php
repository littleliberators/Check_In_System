<?php
/*-------------------------------------------------------------------------
* Name: login.php                                                            *
* Description:  The login screen for parents and employees to login in to    *
*               their respective pages. Parents will login using their PIN   *
*               number. Employees will login using the admin username/       *
*               password credentials.  
*
---------------------------------------------------------------------------*/

    // Controller methods
    include('../../Controller/Login/login_process.php');
    
    // Store variables
    session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
    <script language="JavaScript" type="text/javascript" src="login.js"></script>
    <link href="login.css" type="text/css" rel="stylesheet" />
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

    <!-- left panel of page -->
     <div class="imgLeft">
       
       <!-- I have a way to possibly try and clean this code up more effectively that I will do later -->
       <?php 
        //Once we get this functioning properly, we will clean the code up better so it doesn't look messy here
        include('../../Model/connect-db.php');
    
        //Message displayed, formatting and display needs cleaned up
        $query = "SELECT Message FROM Announcement ORDER BY Announcement_ID DESC 
                            LIMIT 1";
        //Announcement message query
        $result = mysqli_query($dbc, $query);
        while ($row = mysqli_fetch_assoc($result)) 
        {
           ?>  
                  <text type = "text" class = "message-field"  >
                  <?php echo $row["Message"];?>
                  </text>
                  <text type = "text" class = "message-sig"  >
                  Welcome!
                  </text>
          <?php
        }
        ?>
           <!--<img class="leftimg" src="../images/Left_Toys.png" align="Left" alt="Left Toys">-->
      </div>
        

       
   
    <div class="container">
      <div id="panel-heading">
        <div id="tab-parent" class="selected" onclick="ParentTabFocus();">
          <p>PARENT</p>
        </div>
        <div id="tab-admin" class="not-selected" onclick="AdminTabFocus();">
          <p>ADMIN</p>
        </div> 
      </div>
      <div id="panel-body">
        <form id="parent-form">
          <div class="input" align="center">
            <input autofocus type="password" name="PIN" id="PIN-textbox" class="input-field" placeholder="Enter PIN"></input>
            <div class="hide" id="incorrect-pin">Incorrect PIN. Please try again.</div>
            <div id="keypad">
              <button class="key" type="button" onclick="this.blur();"> 1</button>
              <button class="key" type="button" onclick="this.blur();"> 2</button>
              <button class="key" type="button" onclick="this.blur();"> 3</button>
              <br />
              <button class="key" type="button" onclick="this.blur();"> 4</button>
              <button class="key" type="button" onclick="this.blur();"> 5</button>
              <button class="key" type="button" onclick="this.blur();"> 6</button>
              <br />
              <button class="key" type="button" onclick="this.blur();"> 7</button>
              <button class="key" type="button" onclick="this.blur();"> 8</button>
              <button class="key" type="button" onclick="this.blur();"> 9</button>
              <br />
              <button class="key" id="back-button" type="button" onclick="this.blur();">  
                <img id="back-arrow" src="../images/Back_Arrow.png" alt="Back Arrow" />
              </button>
              <button class="key" type="button" onclick="this.blur();"> 0</button>
              <button class="key" id="submit-button" name="parent-submit" type="button" onclick="parentLogin();"> OK </button>
            </div>
            <div class="popup" id="forgot-link" onclick="forgotPIN()">Forgot PIN?
              <span class="popuptext" id="myPopup">Please see admin to reset PIN</span>
            </div>
          </div>
        </form>
        <form id="admin-form" class="hide">
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
    <div class = "content-right">
     <!-- right panel of page -->
      <div class="imgRight">
        <!--<img id="rightimg" src="../images/Right_Toys.png" align="Right" alt="Right Toys">-->
      </div>
    </div>
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