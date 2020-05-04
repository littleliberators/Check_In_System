<?php
/*-------------------------------------------------------------------------*
* Name: message_process.php                                                *
* Description:  Handles all calls to the database to create a message      *
*               and populate table.                                        *
---------------------------------------------------------------------------*/

include('../../Model/connect-db.php');

if (isset($_POST['getReminder'])) {
        $parentId = $_POST["parentID"];

        $query1 = "SELECT Family_ID FROM Parent WHERE Parent_ID = '$parentId' limit 1";
        $result = mysqli_query($dbc, $query1);
        $value = mysqli_fetch_object($result);
        $_SESSION['familyId'] = $value->Family_ID;
        $familyId = $_SESSION['familyId'];

        $query2 = "SELECT Reminder FROM Parent WHERE Family_ID = '$familyId' limit 1"; // ---------------------------------------------------------------------------------------------------------------------------------------------
        $result = mysqli_query($dbc, $query2);
        $value = mysqli_fetch_object($result);
        $_SESSION['reminder'] = $value->Reminder;
        $reminder = $_SESSION['reminder'];
        
        if ($dbc->query($query2) === FALSE) {
            echo "Error: " . $query . "<br>" . $dbc->error;
        }
        else {
            echo json_encode($reminder);
        }


    
}//function



if (isset($_POST['createReminder'])) {
        $reminder = addslashes($_POST['reminder']);
        $parentId = $_POST['parentID'];
        
        // Update/Add Reminder in Parent table
        $query1 = "SELECT Family_ID FROM Parent WHERE Parent_ID = '$parentId' limit 1";
        $result = mysqli_query($dbc, $query1);
        $value = mysqli_fetch_object($result);
        $_SESSION['familyId'] = $value->Family_ID;
        $familyId = $_SESSION['familyId'];
        
        $query2 = "UPDATE Parent SET Reminder = '$reminder' WHERE Family_ID = '$familyId' " ;
        if ($dbc->query($query2) === FALSE) {
            echo "Error: " . $query . "<br>" . $dbc->error;
        }
        else {
            echo "success";
        }
        
        exit();
}
    
    
    
    
//deletes all records of announcements so no outdated messages show
if (isset($_POST['deleteMessage'])) {
        
        $parentId = $_POST['parentID'];
        
        // Deletes all records in Announcement table
        $query1 = "SELECT Family_ID FROM Parent WHERE Parent_ID = '$parentId' limit 1";
        $result = mysqli_query($dbc, $query1);
        $value = mysqli_fetch_object($result);
        $_SESSION['familyId'] = $value->Family_ID;
        $familyId = $_SESSION['familyId'];
        
        $query2 = "UPDATE Parent SET Reminder = NULL WHERE Family_ID = '$familyId' " ;
        if ($dbc->query($query2) === FALSE) {
            echo "Error: " . $query . "<br>" . $dbc->error;
        }
        else {
            echo "success";
        }
        
        exit();
}
    
if (isset($_POST['populateParent'])) {
    $parent = []; 
    $i = 0;
    
    $query = "SELECT * FROM Parent ORDER BY `First_Name`";
    $result = mysqli_query($dbc, $query);
    
    // Iterate over the results that we got from the database
    while($row = mysqli_fetch_assoc($result)) {
        $parent[$i] = $row;
        $i++;
    }
    
    echo json_encode($parent);
    exit();
}
?>