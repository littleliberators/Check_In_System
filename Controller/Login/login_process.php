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
        
        //grabbing pins from db
        $query = "SELECT PIN FROM Family";
        $result = mysqli_query($dbc, $query);
        $data = array(); // create a variable to hold the information

        while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) { //fetch all PINs from row and store in array
            $data[]=$row;
        }
        
        $pinFlag = false; //flag variable for while loop
        $loginPin; //this will save the pin to a local variable once it is compared using password_verify()
        //looping through array
        foreach ($data as $value){
            $pinFlag = password_verify($PIN, $value['PIN']); //compare Pin with db value, if Pin matches or is in Hash, TRUE
            if ($pinFlag == True){
                $loginPin = $value['PIN'];  
            }
            else if($PIN == $value['PIN']){
                $loginPin = $value['PIN'];  
            }
            
        }

        //Validate PIN from Family table
        $query = "SELECT * FROM Family WHERE PIN = '$loginPin'";
        
        // $query = "SELECT * FROM Family WHERE PIN = '$PIN'";
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
        
        //$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        
        // Validate username and password in Employee table
        $query = "SELECT * FROM Employee WHERE Username = '$username' AND BINARY Password = md5('$password')";
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
            $_SESSION["admin_login"] = "YES";
            echo "success";
        }
        exit();
    }
    
?>