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
        <button id="sign-out" onClick="document.location.href='Login.php'">Sign Out</button>
    </div>
    <div class="row-child" id="instructions">Please select the child(ren) to check in/out</div>
    <div class="row-child" id="child-container">
        <div>
            <!-- script to check or uncheck all of the checkboxes -->
            <script type="text/javascript">
            /* global $*/
                $(function(){
            
                 $('#select-all').click(function(event) {   
                    if(this.checked) {
                      // Iterate each checkbox
                      $(':checkbox').each(function() {
                        this.checked = true;                        
                      });
                    }
                    else {
                      // Iterate each checkbox
                      $(':checkbox').each(function() {
                        this.checked = false;
                      });
                    }
                  });
                })
            </script>
            <form class="select-student" action="processlist.php" method="post">
                <div class = "check-all-row">
                    <span class="check-all-label">Check All</span> 
                    <input type='checkbox' name='select-all' id='select-all' value="Check All"/>
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
                //echo "Connected successfully";
                
                $query = "SELECT First_Name, Last_Name
                       FROM Child
                       WHERE Family_ID = '$FamID'";
                $result = mysqli_query($dbc, $query);
                
                $num_rows = $result->num_rows;
                
                //Iterate over the results that we gotten from the database
                if ($num_rows > 0){
                    while($row = mysqli_fetch_assoc($result)) {
                    ?>
                        <div class="checkbox-row">
                            <span class="child-label"><?php echo $row["First_Name"] . " " . $row["Last_Name"]; ?></span> 
                            <input class="check" type="checkbox" name="Name[]" id='<?php echo $row["First_Name"] . "-" . $row["Last_Name"]; ?>'
                            value='<?php echo $row["First_Name"] . " " . $row["Last_Name"]; ?>'/><br/>
                        </div>
                    <?php
                   }
                }
            ?>
            </form>
        </div> 
    </div>
    <div class="row-child" id="child-info-btn">
        <button id="add-new-log-btn">Add New Log</button>
    </div>
    <div class="overlay hideform"></div>
    <!-- Time log popup -->
    <div class="log-time-popup hideform">
        <div id="log-time-header">
            <div id="header">Log Time</div>
            <button id="close-button" aria-label="Close" >X</button>
        </div>
        <form id="log-time">
            <div class="row">
                <div id="select-wrapper">
                    <select class="log-button" id="in-out-button">
                    <option value="I">Check In</option>
                    <option value="O">Check Out</option>
                </select>
                </div>
            </div>
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
                    <input id="e-sign-input" type="text" autocomplete="off">
                </div>
            </div>
            <div>
                <button id="submit-log" type="submit">Submit</button>
            </div>
        </form>
    </div>
</body>

</html>
