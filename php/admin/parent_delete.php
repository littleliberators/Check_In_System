<?php
    include('../connect-db.php');
    
    $id = $_GET['id']; // $id is now defined
    
    // Delete Parents from the row
    mysqli_query($dbc,"DELETE FROM Parent WHERE Family_ID='$id'");
    
    // Delete the Family from Family table
    mysqli_query($dbc,"DELETE FROM Family WHERE Family_ID='$id'");
    
    mysqli_close($dbc);
    header("Location: Parent_Info.php");
?> 