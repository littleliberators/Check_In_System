<?php
/*-------------------------------------------------------------------------*
* Name: message_process.php                                                *
* Description:  Handles all calls to the database to create a message      *
*               and populate table.                                        *
---------------------------------------------------------------------------*/

include('../../Model/connect-db.php');

if (isset($_POST['createReminder'])) {
        $reminder = $_POST['reminder'];
        $parentId = $_POST['parentId'];
        
        // Update/Add Reminder in Parent table
        $query = "UPDATE Parent SET Reminder = '$reminder' WHERE Parent_ID = '$parentId' " ;
        if ($dbc->query($query) === FALSE) {
            echo "Error: " . $query . "<br>" . $dbc->error;
        }
        else {
            echo "success";
        }
        
        exit();
}
    
//deletes all records of announcements so no outdated messages show
if (isset($_POST['deleteMessage'])) {
        $message = $_POST['message'];
        
        // Deletes all records in Announcement table
        $query = "DELETE from Announcement";
        if ($dbc->query($query) === FALSE) {
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