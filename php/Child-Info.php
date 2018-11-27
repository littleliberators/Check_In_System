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
    <div class="row-child" id="instructions">Please select the child(ren) to check in/out</div>
    <div class="row-child" id="child-container">
        <div>
            
            <!-- script to handle checkboxes -->
            <script type="text/javascript">
            /* global $*/
            var allchecked;
                $(function(){
                    // Check all boxes if "Select All" is clicked
                    $('#select-all').click(function(event) {   
                        if(this.checked) {
                          $(':checkbox').each(function() {
                            this.checked = true;                        
                          });
                        }
                        else {
                          $(':checkbox').each(function() {
                            this.checked = false;
                          });
                        }
                    });
                      
                    // Uncheck "Select All" if any other checkbox is unchecked  
                    $(':checkbox').click(function(event) {
                        $(':checkbox').each(function() {
                        if (this.checked == false){
                           $('#select-all').prop('checked', false);
                        }
                    });
                    
                    
                    // Check "Select All" if all other checkboxes are checked
                    $(':checkbox').change(function() {
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
                })
            })
            </script>
            
            <form class="select-student" action="processlist.php" method="post">
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
                    
                    $query = "SELECT First_Name, Last_Name
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
                                value='<?php echo $row["First_Name"] . " " . $row["Last_Name"]; ?>'/>
                                <label class="label" for='<?php echo $row["First_Name"] . "-" . $row["Last_Name"]; ?>'>
                                    <?php echo $row["First_Name"] . " " . $row["Last_Name"]; ?>
                                </label><br/>
                            </div>
                        <?php
                       }
                    }
                ?>
            </form>
        </div> 
    </div>
    <div class="row-child" id="child-info-btn">
        <button class="sign-btn" id="sign-in-btn">Sign In</button>
        <button class="sign-btn" id="sign-out-btn">Sign Out</button>
    </div>
    <div class="overlay hideform"></div>
    <!-- Time log popup -->
    <div class="log-time-popup hideform">
        <div id="log-time-header">
            <div id="header">Log Time</div>
            <button id="close-button" aria-label="Close" >X</button>
        </div>
        <form id="log-time">
            <div class="row" id="date-time-container">
                <div class="text-label" id="day-label">Day:</div>
                <input class="input-box" id="date-input" type="date">
                <div class="text-label" id="time-label">Time:</div>
                <input class="input-box" id="time-input" type="time" />
            </div>
            <div class="row">
                <div class="text-label" id="sign-instructions">
                    Please type your name in below to sign electronically.
                </div>
            </div>
            <div class="row">
                <div id="e-sign-container">
                    <div id="x">X</div>
                    <input id="e-sign-input" type="text" autocomplete="off" tab-index="0">
                </div>
            </div>
            <div>
                <button id="submit-log" type="submit">Submit</button>
            </div>
        </form>
    </div>
</body>

</html>
