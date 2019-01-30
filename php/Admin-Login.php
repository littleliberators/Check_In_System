<?php
// To store variables
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
    <script language="JavaScript" type="text/javascript" src="../javascript/Keypad.js"></script>
    <link href="../css/admin.css" type="text/css" rel="stylesheet" />
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
            <p>Username:</p> 
            <input autofocus type="username" name="Username" id="Admin-textbox" class="input-field" placeholder="Enter Username" minlength="10" required></input>
            <div class="hide" id="incorrect-admin">Incorrect Username. Please try again.</div>
          <div class = "input" align = "center">
            <p>Password:</p>
            <input autofocus type="password" name="Password" id="Admin-textbox" class="input-field" placeholder="Enter Password" minlength="10" required></input>
            <div class="hide" id="incorrect-admin">Incorrect Password. Please try again.</div>
          </div>
          </div>
        </form>
        <button class="key" id="submit-button" name="Login" type="login" onclick="this.blur();"> Login </button>
      </div>
  <div id="logo">
    <img src="../images/Logo.png" align="Right" alt="Logo">
  </div>
  

</body>

</html>