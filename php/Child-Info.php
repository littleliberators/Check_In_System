<?php
// To retrieve global variables
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
    <script language="JavaScript" type="text/javascript" src="../javascript/Info.js"></script>
    <link href="../css/Info.css" type="text/css" rel="stylesheet" />
    <link href="../css/time_log.css" type="text/css" rel="stylesheet" />
</head>

<body>
    <div class="header">
        <div id="welcome">Welcome</div>
        <button id="sign-out" onClick="document.location.href='Login.php'">Log Out</button>
    </div>
    <div class="row-child" id="instructions">Please select the child(ren) to sign in/out</div>
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
                <input class="input-box" id="date-input" name="date" type="date" required disabled
                oninvalid="this.setCustomValidity('Valid date required')" oninput="this.setCustomValidity('')"/>
                <div class="text-label" id="time-label">Time:</div>
                <input class="input-box" id="time-input" name="time" type="time" required disabled
                oninvalid="this.setCustomValidity('Valid time required')" oninput="this.setCustomValidity('')"/>
            </div>
            <div class="row">
                <div class="text-label" id="sign-instructions">
                    Please type your name in below to sign electronically.
                </div>
            </div>
            <div class="row">
                <div id="e-sign-container">
                    <div id="x">X</div>
                    <input id="e-sign-input" name="e-sign" type="text" autocomplete="off" tab-index="0" required pattern="[^\d]*"
                    oninvalid="this.setCustomValidity('Please provide an electronic signature')" oninput="this.setCustomValidity('')">
                </div>
            </div>
            <div>
                <button id="submit-log" type="submit" name="submit">Submit</button>
            </div>
        </form>
    </div>
    
    <!-- script to handle checkbox changes -->
    <script type="text/javascript">
        /* global $*/
        var allchecked;
        $(function(){
            
            // Check all boxes if "Select All" is clicked
            $('#select-all').click(function(event) {   
                if(this.checked) {
                  $(':checkbox').each(function() {
                    $(this).prop('checked', true);                       
                  });
                }
                else {
                  $(':checkbox').each(function() {
                    $(this).prop('checked', false);
                  });
                }
            });
              
            // Uncheck "Select All" if any other checkbox is unchecked  
            $('.check').click(function(event) {
                $('.check').each(function() {
                    if (this.checked == false){
                       $('#select-all').prop('checked', false);
                       
                    }
                });
            });
            
            // Check "Select All" if all other checkboxes are checked
            $('.check').change(function() {
                allchecked = true;
                $('.check').each(function() {
                    if (this.checked == false) {
                        allchecked = false;
                    }
                });
                if (allchecked == true) {
                    $('#select-all').prop('checked', true);
                }
                allchecked = "";
            });
        });
    </script>
    
    <?php
        // Array of children to sign in/out
        $names = $_POST['Name'];
        $signing = "";
        
        // If sign in gets clicked
        if(isset($_POST['signinbutton']))
        {
            // Validate at least one checkbox was clicked
            if(!empty($names)) {
                $signing = 0;
                echo '<script type="text/javascript">',
                'signInForm();',
                '</script>'; 
            }
            else {
                echo '<script type="text/javascript">',
                'showSelectChild();',
                '</script>'; 
            }
        }
        
        // If sign out gets clicked
        if(isset($_POST['signoutbutton']))
        {
            // Validate at least one checkbox was clicked
            if(!empty($names)) {
                $signing = 1;
                echo '<script type="text/javascript">',
                'signOutForm();',
                '</script>'; 
            }
            else {
                echo '<script type="text/javascript">',
                'showSelectChild();',
                '</script>'; 
            }
        }
        
        // Store values in database
        if(isset($_POST['submit']))
        {
            $date = $_POST['date'];
            $time = $_POST['time'];
            $esign = $_POST['e-sign'];
            
            // Signing in 
            if($signing == 0){
                // Debugging
                echo '<script type="text/javascript">',
                'console.log("Signing in");',
                '</script>'; 
                if (!empty($names)){
                    echo '<script type="text/javascript">',
                            'console.log("Array is not empty. Passed if statement.);',
                            '</script>'; 
                    foreach($names as $childID) { // Work in progress
                        echo '<script type="text/javascript">',
                            'console.log("Entered foreach loop");',
                            '</script>'; 
                        $SignInQuery = "INSERT INTO Log (Log_ID, Child_ID, Log_Date, Sign_In_Time, E_Sign_In) 
                        VALUES (NULL, $childID, $date, $time, $esign)";
                
                        if ($conn->query($SignInQuery) === TRUE) {
                            echo '<script type="text/javascript">',
                            'console.log("New record created");',
                            '</script>'; 
                        } else {
                            echo '<script type="text/javascript">',
                            'console.log(',"Error: " . $sql . "<br>" . $conn->error,');',
                            '</script>'; 
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
            // Signing out
            else if ($signing == 1){
                // Debugging
                echo '<script type="text/javascript">',
                'console.log("signing out");',
                '</script>'; 
            }
            
            // Debugging
            echo '<script type="text/javascript">',
                'console.log("Done");',
                '</script>'; 
        }
    ?>
</body>

</html>
