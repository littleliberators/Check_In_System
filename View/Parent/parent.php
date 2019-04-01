<!-------------------------------------------------------------------------
* Name: parent.php                                                            *
* Description:  This page is called after a parent successfully logs in with  *
*               their PIN number. The children associated with the PIN will   *
*               be pulled up and the parent will have the option to sign them *
*               in/out.                                                       *
--------------------------------------------------------------------------->

<?php
// To retrieve global variables
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
    <script language="JavaScript" type="text/javascript" src="parent.js"></script>
    <link href="parent.css" type="text/css" rel="stylesheet" />
    <link href="timeLog.css" type="text/css" rel="stylesheet" />
    <link href="../main.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>

<body>
    <div class="header">        
        <div id="welcome">Welcome to Little Liberators</div>
        <button id="sign-out" onClick="document.location.href='../LoginScreen/login.php'">
            <i class="material-icons">logout</i>
            <div class="header-buttons">LOGOUT</div>
        </button>
    </div>
    <div class="row-child" id="instructions">Please Select the Child(ren) to Sign In/Out</div>
    <div id="imgLeft">
      <img id="leftimg" src="../images/Left_Toys.png" align="Left" alt="Left Toys">
    </div>
    <div id="imgRight">
      <img id="rightimg" src="../images/Right_Toys.png" align="Right" alt="Right Toys">
    </div>
    
    <div class="row-child" id="child-container">
        <form class="select-student" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <div class = "check-all-row">
                <input type="checkbox" name="select-all" id="select-all" value="Select All"/>
                <label class="label" id="select-all-label" for="select-all">Select All</label>
            </div>
                
            <!-- Pull student data from sql -->
            <?php
                $FamID = $_SESSION["FamilyID"];
                
                // connect to the database
                include('../../Model/connect-db.php');
                
                $query = "SELECT Child_ID, First_Name, Last_Name
                       FROM Child
                       WHERE Family_ID = '$FamID'";
                $result = mysqli_query($dbc, $query);
                
                $num_rows = $result->num_rows;
                
                //Iterate over the results that we got from the database
                if ($num_rows > 0){
                    while($row = mysqli_fetch_assoc($result)) {
                    ?>
                        <!-- Create a label and checkbox for each child -->
                        <div class="checkbox-row">
                            <input class="check" type="checkbox" name="Name[]" id='<?php echo $row["First_Name"] . "-" . $row["Last_Name"]; ?>'
                            value='<?php echo $row["Child_ID"]; ?>'/>
                            <label class="label" for='<?php echo $row["First_Name"] . "-" . $row["Last_Name"]; ?>'>
                                <?php echo $row["First_Name"] . " " . $row["Last_Name"]; ?>
                            </label><br/>
                        </div>
                    <?php
                   }
                }
            ?>
            </div> 
            <div class="hide" id="please-select">Please select at least one child.</div>
            <div class="row-child" id="child-info-btn">
                <button class="sign-btn" id="sign-in-btn" name="signinbutton">Sign In</button>
                <button class="sign-btn" id="sign-out-btn" name="signoutbutton">Sign Out</button>
            </div>
        </form>
    <div id="success" class="fade hide">Success</div>
    <div class="overlay hideform"></div>
    
    <!-- Time log popup -->
     <div class="log-time-popup hideform">
        <div id="log-time-header">
            <div id="header">Log Time</div>
            <button id="close-button" aria-label="Close" >X</button>
        </div>
        <form id="log-time" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <div class="row" id="date-time-container">
                <div class="text-label" id="day-label">Day:</div>
                <input class="input-box" id="date-input" name="date" type="date" required readonly
                oninvalid="this.setCustomValidity('Valid date required')" oninput="this.setCustomValidity('')"/>
                <div class="text-label" id="time-label">Time:</div>
                <input class="input-box" id="time-input" name="time" type="time" required readonly
                oninvalid="this.setCustomValidity('Valid time required')" oninput="this.setCustomValidity('')"/>
            </div>
            <div class="row">
                <div class="text-label" id="sign-instructions">
                    Please type your name in below to sign electronically.
                </div>
            </div>
            <div class="row">
                <div id="e-sign-container">
                    <div id="sign-here">X</div>
                    <input id="e-sign-input" name="e-sign" type="text" autocomplete="off" tab-index="0" required pattern="[^\d]*"
                    oninvalid="this.setCustomValidity('Please provide an electronic signature')" oninput="this.setCustomValidity('')">
                </div>
            </div>
            <div>
                <button id="submit-log" type="submit" name="submit">Submit</button>
            </div>
        </form>
    </div> 
    
    <div id="logo">
        <img id ="logo" src="../images/Logo.png" align="Right" alt="Logo" />
    </div>
    <!--<div id = "SBU">-->
        <!--<img id ="SBU" src="../images/SBU.png" align ="Left" alt="SBU" />-->
    <!--</div>-->
    
    <?php
        // Array of children to sign in/out
        $names = $_POST['Name'];
        
        // If sign in gets clicked
        if(isset($_POST['signinbutton']))
        {
            // Validate at least one checkbox was clicked
            if(!empty($names)) {
                // Sign in was clicked
                $_SESSION["SignInOut"] = 0;
                // Assign names array to global variable
                $_SESSION["NamesArray"] = $names;
                echo '<script type="text/javascript">',
                'signInForm();',
                '</script>'; 
            }
            else {
                echo '<script type="text/javascript">',
                'showError("Please select at least one child.");',
                '</script>'; 
            }
        }
        
        // If sign out gets clicked
        if(isset($_POST['signoutbutton']))
        {
            // Validate at least one checkbox was clicked
            if(!empty($names)) {
                // Sign out was clicked
                $_SESSION["SignInOut"] = 1;
                // Assign names array to global variable
                $_SESSION["NamesArray"] = $names;
                echo '<script type="text/javascript">',
                'signOutForm();',
                '</script>'; 
            }
            else {
                echo '<script type="text/javascript">',
                'showError("Please select at least one child.");',
                '</script>'; 
            }
        }
        
        // Store values in database
        if(isset($_POST['submit']))
        {
            /*  This prevents the form adding another entry in mysql if user refreshes the page
                Solution borrowed from: 
                https://stackoverflow.com/questions/6320113/how-to-prevent-form-resubmission-when-page-is-refreshed-f5-ctrlr */
            //create digest of the form submission:
            $messageIdent = md5($_POST['date'] . $_POST['time'] . $_POST['e-sign']);
        
            //and check it against the stored value:
            $sessionMessageIdent = isset($_SESSION['messageIdent'])?$_SESSION['messageIdent']:'';
        
            if($messageIdent!=$sessionMessageIdent){//if its different:          
                //save the session var:
                $_SESSION['messageIdent'] = $messageIdent;
                
                //...continue with adding values to mysql
                $date = $_POST['date'];
                                
                $time = $_POST['time'];
                $esign = $_POST['e-sign'];
                $signing = $_SESSION["SignInOut"];
                $namesArray = $_SESSION["NamesArray"];
                
                if (!empty($namesArray)){
                    // Signing in
                    if($signing == 0){
                        foreach($namesArray as $childID) {
                            $SignInQuery = "INSERT INTO Log (Child_ID, Log_Date, Sign_In_Time, Sign_Out_Time, E_Sign_In, E_Sign_Out, CheckedIn) VALUES ('$childID', '$date', '$time', NULL, '$esign', NULL, 1)";
                            
                            if ($dbc->query($SignInQuery) === TRUE) {
                                // Show success message
                                echo '<script type="text/javascript">',
                                'showSuccessMessage("&#x2713; Successfully Signed In");',
                                '</script>';
                                
                            } else {
                                echo '<script type="text/javascript">',
                                'console.log("Error: ' . $dbc->error . '");',
                                '</script>'; 
                            } 
                        } 
                    }
                    // Signing out
                    else if ($signing == 1){
                        foreach($namesArray as $childID) {
                            
                            $SignOutQuery = "SELECT Log_ID FROM Log WHERE Log_Date = '$date' AND Child_ID = '$childID' AND CheckedIn = 1";
                            
                            if ($stmt = $dbc->prepare($SignOutQuery)) {
                                 // Execute query 
                                $stmt->execute();
                            
                                // Store result 
                                $stmt->store_result();
                                $stmt->bind_result($logID);
                            
                                $num_rows = $stmt->num_rows;
                                
                                // More sign in times than sign out times
                                if ($num_rows > 1){
                                    //Debugging
                                    echo '<script type="text/javascript">',
                                    'console.log("More than 1 rows.");',
                                    '</script>'; 
                                }
                                // Not signed in yet
                                else if ($num_rows == 0){
                                    //Debugging
                                    echo '<script type="text/javascript">',
                                    'showError("Unable to sign out. There are no sign in times.")',
                                    '</script>'; 
                                }
                                // Currently signed in, ready to sign out
                                else if ($num_rows == 1){
                                    // Get the resulting logID from query
                                    while ($stmt->fetch()) {
                                        
                                        $UpdateLogQuery = "UPDATE Log SET Sign_Out_Time = '$time', E_Sign_Out = '$esign', CheckedIn = 0 WHERE Log_ID = '$logID'" ;
                                        
                                        if ($dbc->query($UpdateLogQuery) === TRUE) {
                                            // Show success message
                                            echo '<script type="text/javascript">',
                                            'showSuccessMessage("&#x2713; Successfully Signed Out");',
                                            '</script>';
                                            
                                        } else {
                                            echo '<script type="text/javascript">',
                                            'console.log("Error: ' . $dbc->error,'");',
                                            '</script>'; 
                                        } 
                                    }
                                }
                            
                                // Close statement 
                                $stmt->close();
                            }
                        }
                    }
                }
                else{
                    // Debugging
                    echo '<script type="text/javascript">',
                            'console.log("Empty.");',
                            '</script>'; 
                }
            }
        }
    ?>
    
    
    
</body>

</html>

