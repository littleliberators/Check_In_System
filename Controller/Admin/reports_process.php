<?php
/*-------------------------------------------------------------------------
* Name: reports_process.php                                                 *
* Description:  Handles all calls to the database to populate children      * 
*               options, etc.                                               *
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
?>