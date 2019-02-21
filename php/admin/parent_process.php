<?php 
    include('../connect-db.php');

  if (isset($_POST['pinNumber_check'])) {
  	$pin = $_POST['pinNum'];
  	$sql = "SELECT * FROM Family WHERE PIN='$pin'";
  	$results = mysqli_query($dbc, $sql);
  	if (mysqli_num_rows($results) > 0) {
  	  echo "taken";	
  	}else{
  	  echo 'not_taken';
  	}
  	exit();
  }
  
  if (isset($_POST['save'])) {
  	$p1_fname = $_POST['p1_fname'];
  	$p1_lname = $_POST['p1_lname'];
  	$p2_fname = $_POST['p2_fname'];
  	$p2_lname = $_POST['p2_lname'];
  	$pin = $_POST['pin'];
  	
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
  
  if (isset($_POST['refresh'])) {
  	include('../php_helpers/populateParentTable.php');
  	echo "populated table";
  	exit();
  }

?>