<?php
/*-------------------------------------------------------------------------
* Name: log_process.php                                                     *
* Description:  Handles all calls to the database to add/edit/update/       *
*               delete logs.                                                *
---------------------------------------------------------------------------*/

    include('../../Model/connect-db.php');
    
    // Pre-populate child name options
    if (isset($_POST['populateChildren'])) {
        $children = []; 
        $i = 0;
        
        $query = "SELECT * FROM Child ORDER BY `First_Name`";
        $result = mysqli_query($dbc, $query);
        
        // Iterate over the results that we got from the database
        while($row = mysqli_fetch_assoc($result)) {
            $children[$i] = $row;
            $i++;
        }
        
        echo json_encode($children);
        exit();
    }
    
    // Add new Log to database
    if (isset($_POST['addLog'])) {
        $date = $_POST['date'];
        $childID = $_POST['childID'];
        $signInTime = $_POST['signInTime'];
        $signInSign = $_POST['signInSign'];
        $signOutTime = $_POST['signOutTime'];
        $signOutSign = $_POST['signOutSign'];
        
        $signInTime = !empty($signInTime) ? "'$signInTime'" : "NULL";
        $signOutTime = !empty($signOutTime) ? "'$signOutTime'" : "NULL";
        
        // Creates a record in Log
        $query = "INSERT INTO Log (Child_ID, Log_Date, Sign_In_Time, Sign_Out_Time, E_Sign_In, E_Sign_Out) 
        VALUES ('$childID', '$date', $signInTime, $signOutTime, '$signInSign', '$signOutSign')";
        if ($dbc->query($query) === FALSE) {
            echo "Error: " . $query . "<br>" . $dbc->error;
        }
        else {
            echo "success";
        }
        
        exit();
    }
    
    // Delete selected log
    if (isset($_POST['delete'])) {
        $logID = $_POST['logID'];
        
        // Delete Child from the database
        mysqli_query($dbc,"DELETE FROM Log WHERE Log_ID='$logID'");
        
        mysqli_close($dbc);
        
        echo "success";
        exit();
    }
    
    // Populate the log info screen with data from db
    if (isset($_POST['populate'])) {
        $logID = $_POST['logID'];
        
        $query = "SELECT * FROM Log WHERE Log_ID = '$logID'";
        $result = mysqli_query($dbc, $query);
        $row= mysqli_fetch_assoc($result);
        
        $childID = $row['Child_ID'];
        $date = $row['Log_Date'];
        $signInTime = $row['Sign_In_Time'];
        $signInSign = $row['E_Sign_In'];
        $signOutTime = $row['Sign_Out_Time'];
        $signOutSign = $row['E_Sign_Out'];
        
        echo json_encode(array($childID, $date, $signInTime, $signInSign, $signOutTime, $signOutSign));
        exit();
    }
    
    // Update log info with user changes
    if (isset($_POST['update'])) {
        $logID = $_POST['logID'];
        $date = $_POST['date'];
        $childID = $_POST['childID'];
        $signInTime = $_POST['signInTime'];
        $signInSign = $_POST['signInSign'];
        $signOutTime = $_POST['signOutTime'];
        $signOutSign = $_POST['signOutSign'];
        
        $signInTime = !empty($signInTime) ? "'$signInTime'" : "NULL";
        $signOutTime = !empty($signOutTime) ? "'$signOutTime'" : "NULL";
        
        $updateQuery = "UPDATE Log SET Child_ID = '$childID', Log_Date = '$date', Sign_In_Time = $signInTime, Sign_Out_Time = $signOutTime, E_Sign_In = '$signInSign', E_Sign_Out = '$signOutSign' WHERE Log_ID = '$logID'";
        
        if ($dbc->query($updateQuery) === FALSE) {
            echo "Error: ". $dbc->error."";
            exit();
        }
        else {
            echo "success";
            exit();
        }
    }
    
    // Populate parent table
    if (isset($_POST['populateTable'])) {
        include 'populateLogTable.php';
        populateLogTable();
        exit();
    }
?>