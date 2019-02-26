<?php
    include('../connect-db.php');
    
    $id = $_GET['id']; // $id is now defined
    
    // Delete Child from the database
    mysqli_query($dbc,"DELETE FROM Child WHERE Child_ID='$id'");
    
    mysqli_close($dbc);
    header("Location: Child_Information.php");
?> 