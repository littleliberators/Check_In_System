<?php
/*-------------------------------------------------------------------------
* Name: child_process.php                                                    *
* Description:  Handles all calls to the database to add/edit/update/        *
*               delete children and populate the table.                      *
---------------------------------------------------------------------------*/
    
    
    /* Test Comment */
    
    
    
    
    
    include('../../Model/connect-db.php');
    
    // Pre-populate parent name options
    if (isset($_POST['populateParents'])) {
        $parents = []; 
        
        // $queryFamily = "SELECT DISTINCT Family_ID FROM Parent ORDER BY `First_Name`";
        $queryFamily = "SELECT DISTINCT Family_ID FROM Parent";
        $result = mysqli_query($dbc, $queryFamily);
        $num_rows = $result->num_rows;
        
        // Iterate over the results that we got from the database
        if ($num_rows > 0){
            $i = 0;
            
            while($row = mysqli_fetch_assoc($result)) {
                $famID = $row["Family_ID"];
                
                // Parents
                $queryParents = "SELECT * FROM Parent WHERE Family_ID = '$famID'";
                $resultParents = mysqli_query($dbc, $queryParents);
                $numRowsParents = $resultParents->num_rows;
                
                // Get Parent 1
                $rowParents = mysqli_fetch_assoc($resultParents);
                
                $parents[$i] = $rowParents;
                $i++;
            }
        }
        
        echo json_encode($parents);
        exit();
    }
    
    // Add second parent after parent 1 is selected
    if (isset($_POST['addSecondParent'])) {
        $famID = $_POST['famID'];
        
        $query = "SELECT * FROM Parent WHERE Family_ID = '$famID'";
        $result = mysqli_query($dbc, $query);
        $numRows = $result->num_rows;
        
        // Only 1 parent
        if ($numRows == 1){
            echo "none";
        }
        // 2 parents
        else if ($numRows == 2){
            $count = 0;
            while($row = mysqli_fetch_assoc($result)){
                if ($count == 0){
                    // Skip this, parent 1 is already added
                }
                else if ($count == 1){
                    echo $row['First_Name'] . " " . $row['Last_Name'];
                }
                $count++;
            }
        }
        else{ // No parents
            echo "none";
        }
        
        exit();
    }
    
    // Add new Child to database
    if (isset($_POST['addChild'])) {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $famID = $_POST['family_id'];
        $sunshine = $_POST['isSunshine'];
        
        // Creates a record in Child table
        $query = "INSERT INTO Child (Family_ID, First_Name, Last_Name, isActive, isSunshine) VALUES ('$famID', '$first_name', '$last_name', 1, '$sunshine')";
        if ($dbc->query($query) === FALSE) {
            echo "Error: " . $query . "<br>" . $dbc->error;
        }
        else {
            echo "success";
        }
        
        exit();
    }
    
    // Populate the edit child screen with data from db
    if (isset($_POST['populate'])) {
        $first_name = $last_name = $p1_name = $p2_name = "";
        
        $childID = $_POST['child_ID'];
        
        $queryChild = "SELECT * FROM Child WHERE Child_ID = '$childID'";
        $resultChild = mysqli_query($dbc, $queryChild);
        $numRowsChild = $resultChild->num_rows;
        
        if (($numRowsChild < 1) || ($numRowsChild > 1)){
            echo json_encode(array("error"));
            exit();
        }
        else {
            $rowChild = mysqli_fetch_assoc($resultChild);
            $first_name = $rowChild['First_Name'];
            $last_name = $rowChild['Last_Name'];
            $famID = $rowChild['Family_ID'];
            
            
            // Get the parent names associated with child
            $queryParents = "SELECT * FROM Parent WHERE Family_ID = '$famID'";
            $resultParents = mysqli_query($dbc, $queryParents);
            $numRowsParents = $resultParents->num_rows;
        
            // Only 1 parent
            if ($numRowsParents == 1){
                $rowParents = mysqli_fetch_assoc($resultParents);
                $p1_name = $rowParents['First_Name'] . " " . $rowParents['Last_Name'];
            }
            // 2 parents
            else if ($numRowsParents == 2){
                $count = 0;
                while($rowParents = mysqli_fetch_assoc($resultParents)){
                    if ($count == 0){
                        $p1_name = $rowParents['First_Name'] . " " . $rowParents['Last_Name'];
                    }
                    else if ($count == 1){
                        $p2_name = $rowParents['First_Name'] . " " . $rowParents['Last_Name'];
                    }
                    $count++;
                }
            }
            echo json_encode(array($first_name, $last_name, $p1_name, $p2_name, $famID));
            exit();
        }
    }
    
    // Update child info with user changes
    if (isset($_POST['update'])) {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $childID = $_POST['child_id'];
        $famID = $_POST['famID'];
        
        // $sunshine = $_POST['sunshine'];
        
        // $updateQuery = "UPDATE Child SET First_Name = '$first_name', Last_Name = '$last_name', Family_ID = '$famID', isActive = 1, isSunshine = 1,  WHERE Child_ID = '$childID'";
        $updateQuery = "UPDATE Child SET First_Name = '$first_name', Last_Name = '$last_name', Family_ID = '$famID', WHERE Child_ID = '$childID'";
        
        if ($dbc->query($updateQuery) === FALSE) {
            echo "Error: ". $dbc->error."";
            exit();
        }
        else {
            echo "success";
            exit();
        }
    }

    // Delete selected children
    if (isset($_POST['delete'])) {
        $childID = $_POST['childID'];
        
        // It will not actually delete a child. Instead, it will change the 
        // isActive field to 0, indicating that the child is deleted by the
        // user. This is because we need to keep the child info for log purposes.
        $query = "UPDATE Child SET isActive = 0 WHERE Child_ID = '$childID'";

        if ($dbc->query($query) === TRUE) {
            echo "success";
        } else {
            echo "Error updating record: " . $dbc->error;
        }
        
        mysqli_close($dbc);
        exit();
    }
    
    // Populate child table
    if (isset($_POST['populateTable'])) {
        include 'populateChildTable.php';
        populateChildTable();
        exit();
    }
    
    // Saves the search string as global variable in populateLogTable.php file
    if (isset($_POST['saveSearch'])){
        include 'populateChildTable.php';
        $value = $_POST['search_value'];
        saveSearchString($value);
        exit();
    }
?>