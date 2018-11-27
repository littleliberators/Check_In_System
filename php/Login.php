<?php
// To store variables
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
    <script language="JavaScript" type="text/javascript" src="../javascript/Keypad.js"></script>
    <link href="../css/login_screen.css" type="text/css" rel="stylesheet" />
</head>

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
        <div id="tab-parent">
          <p>PARENT</p>
        </div>
        <div id="tab-admin">
          <p>ADMIN</p>
        </div>
      </div>
      <div id="panel-body">
        <form id="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
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
              <button class="key" id="submit-button" name="submit" type="submit" onclick="this.blur();"> OK </button>
            </div>
            <div class="popup" id="forgot-link" onclick="forgotPIN()">Forgot PIN?
              <span class="popuptext" id="myPopup">Please see admin to reset PIN</span>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div id="logo">
    <img src="../images/Logo.png" align="Right" alt="Logo">
  </div>
  
  <!-- Validate PIN entry -->
  <?php
  if(isset($_POST['submit']))
  {
      // Database credentials
      $host = "127.0.0.1";
      $user = "emmatsipan";
      $pass = "";
      $db = "little_liberators";
      $port = 3306;
      
      // Connect to the database
      $dbc = mysqli_connect($host, $user, $pass, $db, $port);
      
      // Check connection
      if ($dbc->connect_error) {
         die("Connection failed: " . $cdbc->connect_error);
      } 
  
      $PIN = $_POST["PIN"];
  
      // Pull PIN from Family table
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
          <!-- script to handle invalid pin -->
              <script type="text/javascript">
              /* global $*/
                  $(function(){
                    $('#incorrect-pin').show();
                  });
              </script>
              </div>
              <?php
      } else {
          $row = mysqli_fetch_assoc($result);
          $famID = $row['Family_ID'];
          $_SESSION["FamilyID"] = $famID;
          header('Location: Child-Info.php');
          exit();
      }
  }
  ?>

</body>

</html>
