<?php
    include('../connect-db.php');
    
    // Pre-populate parent name options
    if (isset($_POST['populateParents'])) {
        $parents = []; 
        
        $queryFamily = "SELECT DISTINCT Family_ID FROM Parent ORDER BY `Last_Name`";
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
                
                //Get Parent 1
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
                    echo $row['Last_Name'] . ", " . $row['First_Name'];
                }
                $count++;
            }
        }
        else{
            echo "Error: An error occurred with the connection";
        }
        
        exit();
    }
    
    // Add new Child to database
    if (isset($_POST['addChild'])) {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $famID = $_POST['family_id'];
        
        // Creates a record in Parent table for Parent 1
        $query = "INSERT INTO Child (Family_ID, First_Name, Last_Name) VALUES ('$famID', '$first_name', '$last_name')";
        if ($dbc->query($query) === FALSE) {
            echo "Error: " . $query . "<br>" . $dbc->error;
        }
        else {
            echo "success";
        }
        
        exit();
    }
?>