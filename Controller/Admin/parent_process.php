<?php 
/*-------------------------------------------------------------------------
* Name: parent_process.php                                                  *
* Description:  Handles all calls to the database to add/edit/update/       *
*               delete parents and populate parent table.                   *
---------------------------------------------------------------------------*/
    // Connect to the database
    include('../../Model/connect-db.php');
    
    // Validate that the pin does not exist in the database
    if (isset($_POST['pinNumber_check'])) {
        
      	$pin = $_POST['pinNum'];
      	$famID = $_POST['famID'];
      	
      	$query = "SELECT * FROM Family";
        $result = mysqli_query($dbc, $query);
        $data = array(); // create a variable to hold the information

        while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) { //fetch all PINs from row and store in array
            $data[]=$row;
        }
        
        $pinFlag = false; //flag variable for while loop
        $loginPin; //this will save as true if pinflag is true
        //looping through array
        foreach ($data as $value){
            $pinFlag = password_verify($pin, $value['PIN']); //compare Pin with db value, if Pin matches or is in Hash, TRUE
            if ($pinFlag == True){
                if($famID != $value['Family_ID']){ //check if this is the current password for this family
                    $loginPinTaken = True;  
                }
            }
        }
      	
      	//Check for unencrypted pin
      	$sql1 = "SELECT * FROM Family WHERE PIN='$pin'";
      	$results1 = mysqli_query($dbc, $sql1);
      	
      	if (mysqli_num_rows($results1) > 0) {
      	  echo "taken";	
      	}
      	else if($loginPinTaken == True) {
      	  echo 'taken';
      	} else {
      	    echo 'not_taken';
      	}
      	exit();
    }
  
    // Save data to db
    if (isset($_POST['save'])) {
      	$p1_fname = $_POST['p1_fname'];
      	$p1_lname = $_POST['p1_lname'];
      	$p2_fname = $_POST['p2_fname'];
      	$p2_lname = $_POST['p2_lname'];
      	$pin = password_hash($_POST['pin'], PASSWORD_DEFAULT);
      	
      	// Create a new record in Family table
      	$insertquery = "INSERT INTO Family (PIN) VALUES ('$pin')";
      	if ($dbc->query($insertquery) === TRUE) {
            $family_id = $dbc->insert_id;
        } else {
            echo "Error: " . $insertquery . "<br>" . $dbc->error;
        }
        
        // Creates a record in Parent table for Parent 1
        $p1query = "INSERT INTO Parent (Family_ID, First_Name, Last_Name) VALUES ('$family_id', '$p1_fname', '$p1_lname')";
        if ($dbc->query($p1query) === FALSE) {
            echo "Error: " . $p1query . "<br>" . $dbc->error;
        }
      	
      	// Parent 2 was entered
      	if (strlen($p2_fname) > 0) {
      	    $p2query = "INSERT INTO Parent (Family_ID, First_Name, Last_Name) VALUES ('$family_id', '$p2_fname', '$p2_lname')";
            if ($dbc->query($p2query) === FALSE) {
                echo "Error: " . $p2query . "<br>" . $dbc->error;
            }
      	}
      	exit();
    }
  
    // Populate the edit parent screen with data from db
    if (isset($_POST['populate'])) {
        $p1_fname = $p1_lname = $p2_fname = $p2_lname = $pin = "";
        $famID = $_POST['famID'];
          
        $queryParents = "SELECT * FROM Parent WHERE Family_ID = '$famID'";
        $resultParents = mysqli_query($dbc, $queryParents);
        $numRowsParents = $resultParents->num_rows;
        
        // Only 1 parent
        if ($numRowsParents == 1){
            $rowParents = mysqli_fetch_assoc($resultParents);
            $p1_fname = $rowParents['First_Name'];
            $p1_lname = $rowParents['Last_Name'];
            $p2_fname = "";
            $p2_lname = "";
        }
        // 2 parents
        else if ($numRowsParents == 2){
            $count = 1;
            while($rowParents = mysqli_fetch_assoc($resultParents)){
                if ($count == 1){
                    $p1_fname = $rowParents['First_Name'];
                    $p1_lname = $rowParents['Last_Name'];
                }
                else if ($count == 2){
                    $p2_fname = $rowParents['First_Name'];
                    $p2_lname = $rowParents['Last_Name'];
                }
                $count++;
            }
        }
        else{
            echo "Error: An error occurred with the connection";
        }
        
        $queryPIN = "SELECT PIN FROM Family WHERE Family_ID = '$famID'";
        $resultPIN = mysqli_query($dbc, $queryPIN);
        $rowPIN =  mysqli_fetch_assoc($resultPIN);
        $pin = $rowPIN['PIN'];
        
        echo json_encode(array($p1_fname, $p1_lname, $p2_fname, $p2_lname, $pin));
        exit();
    }
  
    // Update parent information
    if (isset($_POST['update'])) {
        $p1_fname = $_POST['p1_fname'];
        $p1_lname = $_POST['p1_lname'];
        $p2_fname = $_POST['p2_fname'];
        $p2_lname = $_POST['p2_lname'];
        $pin = password_hash($_POST['pin'],PASSWORD_DEFAULT);
        

        $famID = $_POST['famID'];
        
        $query = "SELECT * FROM Parent WHERE Family_ID = '$famID'";
        $result = mysqli_query($dbc, $query);
        $numRow = $result->num_rows;
        
        // Only 1 parent in db
        if ($numRow == 1){
            $row = mysqli_fetch_assoc($result);
            $p1ID = $row['Parent_ID'];
            
            // Only parent 1 updated
            if ($p2_fname == ""){
                // Update Parent 1
                $updateQuery = "UPDATE Parent SET First_Name = '$p1_fname', Last_Name = '$p1_lname' WHERE Parent_ID = '$p1ID'" ;
                if ($dbc->query($updateQuery) === FALSE) {
                    echo "Error(1) updating Parent 1: ". $dbc->error."";
                    exit();
                }
            }
            else {
                // Update Parent 1
                $updateQuery = "UPDATE Parent SET First_Name = '$p1_fname', Last_Name = '$p1_lname' WHERE Parent_ID = '$p1ID'" ;
                if ($dbc->query($updateQuery) === FALSE) {
                    echo "Error(2) updating Parent 1: ". $dbc->error."";
                    exit();
                }
                
                // Create record for Parent 2
                $p2query = "INSERT INTO Parent (Family_ID, First_Name, Last_Name) VALUES ('$famID', '$p2_fname', '$p2_lname')";
                if ($dbc->query($p2query) === FALSE) {
                    echo "Error(3) adding Parent 2: ". $dbc->error."";
                    exit();
                }
            }
        }
        // 2 parents in db
        else if ($numRow == 2){
            // Parent 2 was removed
            if ($p2_fname == ""){
                $count = 1;
                while($row = mysqli_fetch_assoc($result)){
                    $pID = $row['Parent_ID'];
                    // Parent 1
                    if ($count == 1){
                        // Update Parent 1
                        $updateQuery = "UPDATE Parent SET First_Name = '$p1_fname', Last_Name = '$p1_lname' WHERE Parent_ID = '$pID'" ;
                        if ($dbc->query($updateQuery) === FALSE) {
                            echo "Error(4) updating Parent 1: ". $dbc->error."";
                            exit();
                        }
                    }
                    // Parent 2
                    else if ($count == 2){
                        // Delete Parents 2 from db
                        mysqli_query($dbc,"DELETE FROM Parent WHERE Parent_ID='$pID'");
                    }
                    $count++;
                }
                
            }
            // Update both parents
            else {
                $count = 1;
                while($row = mysqli_fetch_assoc($result)){
                    $pID = $row['Parent_ID'];
                    // Parent 1
                    if ($count == 1){
                        // Update Parent 1
                        $updateQuery = "UPDATE Parent SET First_Name = '$p1_fname', Last_Name = '$p1_lname' WHERE Parent_ID = '$pID'" ;
                        if ($dbc->query($updateQuery) === FALSE) {
                            echo "Error(5) updating Parent 1: ". $dbc->error."";
                            exit();
                        }
                    }
                    // Parent 2
                    else if ($count == 2){
                        // Update Parent 2
                        $updateQuery = "UPDATE Parent SET First_Name = '$p2_fname', Last_Name = '$p2_lname' WHERE Parent_ID = '$pID'" ;
                        if ($dbc->query($updateQuery) === FALSE) {
                            echo "Error(6) updating Parent 2 :". $dbc->error."";
                            exit();
                        }
                    }
                    $count++;
                }
            }
            
        }
        else{
            echo "Error(7) updating the database :". $dbc->error."";
        }
        
        //Update PIN for family
        if($_POST['pin'] != ""){
            $updateQuery = "UPDATE Family SET PIN = '$pin' WHERE Family_ID = '$famID'" ;
            if ($dbc->query($updateQuery) === FALSE) {
                echo "Error(8) updating PIN: ". $dbc->error."";
                exit();
            }
        }
        echo "done";
       exit();
    }
   
    // Delete selected parents
    if (isset($_POST['delete'])) {
        $famID = $_POST['famID'];
        
        // Delete Parents from the row
        mysqli_query($dbc,"DELETE FROM Parent WHERE Family_ID='$famID'");
        
        // Delete the Family from Family table
        mysqli_query($dbc,"DELETE FROM Family WHERE Family_ID='$famID'");
        mysqli_close($dbc);
        
        echo "success";
        exit();
    }
    
    // Populate parent table
    if (isset($_POST['populateTable'])) {
        include 'populateParentTable.php';
        populateParentTable();
        exit();
    }
    
    // Saves the search string as global variable in populateLogTable.php file
    if (isset($_POST['saveSearch'])){
        include 'populateParentTable.php';
        $value = $_POST['search_value'];
        saveSearchString($value);
        exit();
    }
?>