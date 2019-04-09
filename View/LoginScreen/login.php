<!-------------------------------------------------------------------------
* Name: login.php                                                            *
* Description:  The login screen for parents and employees to login in to    *
*               their respective pages. Parents will login using their PIN   *
*               number. Employees will login using the admin username/       *
*               password credentials.                                        *
--------------------------------------------------------------------------->

<?php
    // Store variables
    session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
    <script language="JavaScript" type="text/javascript" src="keypad.js"></script>
    <script language="JavaScript" type="text/javascript" src="loginTabs.js"></script>
    <link href="login.css" type="text/css" rel="stylesheet" />
    <link href="../main.css" type="text/css" rel="stylesheet" />
</head>

<!-- Validate PIN entry -->
<?php

  // connect to the database
  include('../../Model/connect-db.php');
  
  // Check connection
  if ($dbc->connect_error) {
     die("Connection failed: " . $dbc->connect_error);
  } 
      
  if(isset($_POST['parent-submit']))
  {
    $PIN = $_POST["PIN"];

    // Validate PIN from Family table
    $query = "SELECT * FROM Family WHERE PIN = '$PIN'";
    $result = mysqli_query($dbc, $query);
    $num_rows = $result->num_rows;
    
    // Validate PIN entry
    if ($num_rows > 1){
        echo "Hold on. Something is wrong. There are more than one family id's with the same PIN";
    }
    else if ($num_rows == 0){
        ?>
        <div>
        <!-- show error message if no selection was made -->
            <script type="text/javascript">
            /* global $*/
                $(function(){
                  $('#incorrect-pin').show();
                });
            </script>
            </div>
            <?php
    } else {
      ?>
        <div>
        <!-- script to hide error message -->
            <script type="text/javascript">
            /* global $*/
                $(function(){
                  $('#error-message').hide();
                });
            </script>
            </div>
            <?php
            
        $row = mysqli_fetch_assoc($result);
        $famID = $row['Family_ID'];
        $_SESSION["FamilyID"] = $famID;
        header('Location: ../Parent/parent.php');
        exit();
    }
  }
  
  if(isset($_POST['admin-submit']))
  {
    $Username = $_POST["username"];
    $Password = $_POST["password"];
    
    // Validate username and password in Employee table
    $query = "SELECT * FROM Employee WHERE Username = '$Username' AND Password = '$Password'";
    $result = mysqli_query($dbc, $query);
    $num_rows = $result->num_rows;
    
    // Validate user input
    if ($num_rows > 1){
        echo "Hold on. Something is wrong. There are multiple entries of the same username and password in the table.";
    }
    else if ($num_rows == 0){
      ?>
        <div>
        <!-- script to handle invalid input-->
          <script type="text/javascript">
          /* global $*/
              $(function(){
                $('#admin-error').show();
              });
          </script>
        </div>
      <?php
      echo '<script type="text/javascript">',
        'AdminTabFocus()',
        '</script>';
    } else {
      header('Location: ../Admin/admin.php');
      exit();
    }
  }
?>
  
<body>
  <div id="header-background">
    <img id="title" src="../images/Block_Title2.png" alt="Little Liberators" />
  </div>
  <p id="description">Childcare Center</p>
  <div id="content">
    <div id="imgLeft">
      <img id="leftimg" src="../images/Left_Toys.png" align="Left" alt="Left Toys">
    </div>
    <div id="imgRight">
      <img id="rightimg" src="../images/Right_Toys.png" align="Right" alt="Right Toys">
    </div>
    <div class="container">
      <div id="panel-heading">
        <div id="tab-parent" class="selected">
          <p>PARENT</p>
        </div>
        <div id="tab-admin" class="not-selected">
          <p>ADMIN</p>
        </div>
      </div>
      <div id="panel-body">
        <form id="parent-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
          <div class="input" align="center">
            <input autofocus type="password" name="PIN" id="PIN-textbox" class="input-field" placeholder="Enter PIN" minlength="4" required></input>
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
              <button class="key" id="submit-button" name="parent-submit" type="submit" onclick="this.blur();"> OK </button>
            </div>
            <div class="popup" id="forgot-link" onclick="forgotPIN()">Forgot PIN?
              <span class="popuptext" id="myPopup">Please see admin to reset PIN</span>
            </div>
          </div>
        </form>
        <form id="admin-form" class="hide" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
          <div id="instructions">Please Enter Username and Password</div>
          <div class="input-container" id="username-container">
            <div class="input-label">Username:</div>
            <input autofocus class="input-field" id="username" name="username" type="text" placeholder="Enter Username" required></input>
          </div>
          <div class="input-container" id="password-container">
            <div class="input-label">Password:</div>
            <input class="input-field" id="password" name="password" type="password" placeholder="Enter Password" required></input>
          </div>
          <div id="admin-error" class="hide">
            The username and password do not match.<br>Please Try Again.
          </div>
          <div style="text-align:center;">
            <button id="admin-submit" name="admin-submit" type="submit" >Login</button>
          </div>
        </form>
      </div>
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
