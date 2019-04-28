<?php
/*-------------------------------------------------------------------------
* Name: login_process.php                                                    *
* Description:  Handles all calls to the database to check if parent pin or  *
*               admin username/password exist.                               *
---------------------------------------------------------------------------*/

  // connect to the database
  include('../../Model/connect-db.php');
  session_start();
  
    // Parent login
    if (isset($_POST['parentLogin'])) {
        $PIN= $_POST['PIN'];
        
        // Validate PIN from Family table
        $query = "SELECT * FROM Family WHERE PIN = '$PIN'";
        $result = mysqli_query($dbc, $query);
        $num_rows = $result->num_rows;
    
        // Validate PIN entry
        if ($num_rows > 1){
            echo "Hold on. Something is wrong. There are more than one family id's with the same PIN";
        }
        else if ($num_rows == 0){
            echo "none";
        } 
        else {
            $row = mysqli_fetch_assoc($result);
            $famID = $row['Family_ID'];
            $_SESSION["FamilyID"] = $famID;
            echo "success";
        }
        exit();
    }
    
    // Admin login
    if (isset($_POST['adminLogin'])) {
        $username = $_POST["username"];
        $password = $_POST["password"];
        
        // Validate username and password in Employee table
        $query = "SELECT * FROM Employee WHERE Username = '$username' AND BINARY Password = '$password'";
        $result = mysqli_query($dbc, $query);
        $num_rows = $result->num_rows;
        
        // Validate user input
        if ($num_rows > 1){
            echo "Error: There are multiple entries of the same username and password in the table.";
        }
        else if ($num_rows == 0){
            echo "none";
        } 
        else {
            echo "success";
        }
        exit();
    }
?>