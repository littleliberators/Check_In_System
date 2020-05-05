<?php
/*-------------------------------------------------------------------------*
* Name: message_process.php                                                *
* Description:  Handles all calls to the database to create a message      *
*               and populate table.                                        *
---------------------------------------------------------------------------*/

include('../../Model/connect-db.php');

if (isset($_POST['createMessage'])) {
        $message = addslashes($_POST['message']);

        // Creates a record in Announcement table
        $query = "INSERT INTO Announcement (Message) VALUES ('$message')";
        if ($dbc->query($query) === FALSE) {
            echo "Error: " . $query . "<br>" . $dbc->error;
        }
        else {
            echo "success";
        }
        
        exit();
}

if (isset($_POST['getMessage'])) {
    include('../../Model/connect-db.php');
        $query = "SELECT Message FROM Announcement ORDER BY Announcement_ID DESC 
                            LIMIT 1";
        //Announcement message query
        $result = mysqli_query($dbc, $query);
        while ($row = mysqli_fetch_assoc($result)) 
        {
            $message = $row['Message'];
            echo $message;
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
    
?>