<?php
/*-------------------------------------------------------------------------
* Name: parent_process.php                                                   *
* Description:  Handles all calls to add a new log or update log in the      *
*               database from the parent screen.                             *
*               Also updates each box (Checked In & Checked Out) based       *
*               on submitted logs in the database from previous Check-Ins.   *
---------------------------------------------------------------------------*/
    // To retrieve global variables
    session_start();
    
    // Connect to database
    include('../../Model/connect-db.php');
    
    // Populate table with child names associated with family ID, that are currently signed out
    function populateSignedOut(){
        $FamID = $_SESSION["FamilyID"];
        
        // Create an empty array that will store the child id's that are not checked in
        $_SESSION["OutNamesArray"] = [];
                
        // Connect to the database
        include('../../Model/connect-db.php');
        
        $query = "SELECT Child_ID, First_Name, Last_Name
               FROM Child
               WHERE Family_ID = '$FamID'
               AND isActive = 1
               AND isSunshine = 0"; 
        $result = mysqli_query($dbc, $query);
        
        $numrows = $result->num_rows;
        
        //Iterate over the results that we got from the database
        if ($numrows > 0){
            while($row = mysqli_fetch_assoc($result)) {
                $Child_ID = $row['Child_ID'];
                date_default_timezone_set('America/Chicago');
                $today = date('Y-m-d');
                
                // Check if there exist any logs for today
                $queryLogs = "SELECT *
                   FROM Log
                   WHERE Child_ID = ".$Child_ID."
                   AND Log_Date = '".$today."'
                   ORDER BY DateTimeStamp DESC;";
                $resultLogs = mysqli_query($dbc, $queryLogs);
                
                $num_rowsLogs = $resultLogs->num_rows;
                
                // No logs for today
                if ($num_rowsLogs == 0){
                    ?>
                    <div class="checkbox-row">
                        <input class="check-out" type="checkbox" name="Name-Out" id='<?php echo $row["First_Name"] . "-" . $row["Last_Name"] . "-Out"; ?>'
                        value='<?php echo $row["Child_ID"]; ?>'/>
                        <label class="label" for='<?php echo $row["First_Name"] . "-" . $row["Last_Name"] . "-Out"; ?>'>
                            <?php echo $row["First_Name"] . " " . $row["Last_Name"]; ?>
                        </label><br/>
                    </div>
                    <?php
                    
                    // Add child id to array, meaning it is in the checked out box.
                    $_SESSION["OutNamesArray"][] = $Child_ID;
                }
                // There is at least one log
                else {
                    $checkedIn = false;
                    
                    // Takes the most recent log entry and checks if there are any null values for times.
                    // If there are, it uses the log entry to figure out which box to put it in.
                    $rowLogs = mysqli_fetch_assoc($resultLogs);
                    if (($rowLogs["Sign_In_Time"] == NULL) || ($rowLogs["Sign_Out_Time"] == NULL))
                    {
                        // There is a sign in time but no sign out time.
                        if (($rowLogs["Sign_Out_Time"]  == NULL) && !($rowLogs["Sign_In_Time" == NULL])){
                            $checkedIn = true;
                        }
                        // There is a sign out time but no sign in time.
                        else if (($rowLogs["Sign_In_Time"]  == NULL) && !($rowLogs["Sign_Out_Time"]  == NULL)){
                            $checkedIn = false;
                        }
                        // A log exists but has no sign in or out time
                        else {
                            $checkedIn = false;
                        }
                    }
                    // There is already a sign in and out time for the log. They are currently checked out.
                    else {
                        $checkedIn = false;
                    }
                    
                    // The child is currently checked out. Add them to the left box, where they will be shown as checked out.
                    if ($checkedIn == false) {
                         ?>
                        <div class="checkbox-row">
                            <input class="check-out" type="checkbox" name="Name-Out" id='<?php echo $row["First_Name"] . "-" . $row["Last_Name"] . "-Out"; ?>'
                            value='<?php echo $row["Child_ID"]; ?>'/>
                            <label class="label" for='<?php echo $row["First_Name"] . "-" . $row["Last_Name"] . "-Out"; ?>'>
                                <?php echo $row["First_Name"] . " " . $row["Last_Name"]; ?>
                            </label><br/>
                        </div>
                        <?php
                        // Add child id to array, meaning it is in the checked out box.
                        $_SESSION["OutNamesArray"][] = $Child_ID;
                    }
                }
            }
        }
    }
    
    // Populate table with child names that are currently signed in
    // by adding any children that are not shown in the Checked out box
    function populateSignedIn(){
        $FamID = $_SESSION["FamilyID"];
        
        // Array of children that are Checked Out
        $namesArray = $_SESSION["OutNamesArray"];
        
        // connect to the database
        include('../../Model/connect-db.php');
        
        $query = "SELECT Child_ID, First_Name, Last_Name
               FROM Child
               WHERE Family_ID = '$FamID'
               AND isActive = 1
               AND isSunshine = 0";
        $result = mysqli_query($dbc, $query);
        
        while($row = mysqli_fetch_assoc($result)) {
            if (!in_array($row["Child_ID"], $namesArray)){ 
                ?>
                <div class="checkbox-row">
                    <input class="check-in" type="checkbox" name="Name-In" id='<?php echo $row["First_Name"] . "-" . $row["Last_Name"] . "-In"; ?>'
                    value='<?php echo $row["Child_ID"]; ?>'/>
                    <label class="label" for='<?php echo $row["First_Name"] . "-" . $row["Last_Name"] . "-In"; ?>'>
                        <?php echo $row["First_Name"] . " " . $row["Last_Name"]; ?>
                    </label><br/>
                </div>
                <?php 
            }
        }
    }
    
    
    
    /* Populate Sunshine table with Sunshine kids whose parents are signed in */
    function populateSunshine(){
        $FamID = $_SESSION["FamilyID"];
        
        // connect to the database
        include('../../Model/connect-db.php');
        
        $query = "SELECT Child_ID, First_Name, Last_Name
               FROM Child
               WHERE Family_ID = '$FamID'
               AND isActive = 1
               AND isSunshine = 1"; // ---------------------------------------------------------------------------------------------------------------------------------------------
        $result = mysqli_query($dbc, $query);
        
        while($row = mysqli_fetch_assoc($result)) {
            if (!in_array($row["Child_ID"], $namesArray)){ 
                ?>
                <div class="checkbox-row">
                    <input class="sunshine" type="checkbox" name="Sun-Name" id='<?php echo $row["First_Name"] . "-" . $row["Last_Name"] . "-In"; ?>'
                    value='<?php echo $row["Child_ID"]; ?>'/>
                    <label class="label" for='<?php echo $row["First_Name"] . "-" . $row["Last_Name"] . "-In"; ?>'>
                        <?php echo $row["First_Name"] . " " . $row["Last_Name"]; ?>
                    </label><br/>
                </div>
                <?php 
            }//if
            
        }//while
        
    }//function
    
    
    
    
    
    // Things that need to be done after both check in and check out boxes are loaded.
    function postValidations(){
        // Resize the containers after both sign in and out containers are populated
        // Remove the Select All option if there are no children in the box
        echo '<script type="text/javascript">',
             'resizeContainers();',
             'removeSelectAll();',
             '</script>'
        ;
    }
    
    // Add new log to the database
    if (isset($_POST['checkIn'])) {
        $date = $_POST['date'];
        $time = $_POST['time'];
        $signature = $_POST['signature'];
        $childID_array = json_decode(stripslashes($_POST['childID_array']));
        
        // Iterate through each child ID
        foreach($childID_array as $childID){
            // Create a new log with given values
            $query = "INSERT INTO Log (Child_ID, Log_Date, Sign_In_Time, Sign_Out_Time, E_Sign_In, E_Sign_Out) VALUES ('$childID', '$date', '$time', NULL, '$signature', NULL)";
            if (!$dbc->query($query)){
                echo $dbc->error;
            }
        }
        
        echo "success";
        exit();
    }
    
     // Update logs with check out time and signature
    if (isset($_POST['checkOut'])) {
        $date = $_POST['date'];
        $time = $_POST['time'];
        $signature = $_POST['signature'];
        $childID_array = json_decode(stripslashes($_POST['childID_array']));
        
        // Iterate through each child ID
        foreach($childID_array as $childID){
            // Get the existing logs for today, ordered by last modified 
            $queryLogs = "SELECT *
               FROM Log
               WHERE Child_ID = ".$childID."
               AND Log_Date = '".$date."'
               ORDER BY DateTimeStamp DESC;";
            $resultLogs = mysqli_query($dbc, $queryLogs);
            
            $num_rowsLogs = $resultLogs->num_rows;
            
            // No logs exist for today
            // It should never enter this conditional statement!!
            if ($num_rowsLogs == 0){
                // But in case it does...
                // We'll just create a new log entry that has the check out time and signature.
                $query = "INSERT INTO Log (Child_ID, Log_Date, Sign_In_Time, Sign_Out_Time, E_Sign_In, E_Sign_Out) VALUES ('$childID', '$date', NULL, '$time', NULL, '$signature')";
                if (!$dbc->query($query)){
                    echo $dbc->error;
                }
            }
            // There is at least one log
            else {
                $updated = false;
                
                // Iterate through all of the logs for today
                while($rowLogs = mysqli_fetch_assoc($resultLogs)) {
                    // Takes the most recent log entry and checks if there is a sign out time missing
                    if ($rowLogs["Sign_Out_Time"] == NULL)
                    {
                        // Update the log with sign out time and signature
                        $logID = $rowLogs["Log_ID"];
                        $updateQuery = "UPDATE Log SET Sign_Out_Time = '$time', E_Sign_Out = '$signature' WHERE Log_ID = '$logID'" ;
                                    
                        if (!($dbc->query($updateQuery) === TRUE)) {
                            echo $dbc->error;
                        }
                        
                        $updated = true;
                        
                        // Exit the while loop because we don't need to look at the other logs.
                        break;
                    }
                }
                
                // There were no entries with a missing check out time. 
                if ($updated == false){
                    // Create a new log entry that has the check out time and signature.
                    $query = "INSERT INTO Log (Child_ID, Log_Date, Sign_In_Time, Sign_Out_Time, E_Sign_In, E_Sign_Out) VALUES ('$childID', '$date', NULL, '$time', NULL, '$signature')";
                    if (!$dbc->query($query)){
                        echo $dbc->error;
                    }
                }
            }
        }
        echo "success";
        exit();
    }
?>