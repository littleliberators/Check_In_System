<?php
/*-------------------------------------------------------------------------
* Name: populateParentTable.php                                               *
* Description:  Creates and populates a table with all of the parent info.    *
---------------------------------------------------------------------------*/

    // connect to the database
    include('../../Model/connect-db.php');
    
    $queryAll = "SELECT DISTINCT Family_ID FROM Parent ORDER BY `First_Name`";
           
    $result = mysqli_query($dbc, $queryAll);
    
    $num_rows = $result->num_rows;
    
    // Iterate over the results that we got from the database
    if ($num_rows > 0){
        // Create a table that will be populated with info
        echo "<table id='parent-table' border='1'>
            <tr>
            <th>Parent/Guardian 1</th>
            <th>Parent/Guardian 2</th>
            <th>PIN</th>
            <th>Edit</th>
            <th>Delete</th>
            </tr>";
        while($row = mysqli_fetch_assoc($result)) {
            $famID = $row["Family_ID"];
            
            // Creates a row for each family.
            echo "<tr>";
            
            $queryParents = "SELECT * FROM Parent WHERE Family_ID = '$famID'";
            $resultParents = mysqli_query($dbc, $queryParents);
            $numRowsParents = $resultParents->num_rows;
            
            // Only 1 parent
            if ($numRowsParents == 1){
                $rowParents = mysqli_fetch_assoc($resultParents);
                echo "<td>" . $rowParents['First_Name'] . " " . $rowParents['Last_Name'] . "</td>";
                echo "<td> </td>";
            }
            // 2 parents
            else if ($numRowsParents == 2){
                 while($rowParents = mysqli_fetch_assoc($resultParents)){
                     echo "<td>" . $rowParents['First_Name'] . " " . $rowParents['Last_Name'] . "</td>";
                 }
            }
            else if ($numRowsParents == 0){
                echo "Error: No parents found for id - " . $famID . "<br>";
            }
            else{
                echo "Error: There are more than 2 instances of parents for one family<br>";
            }
            
            $queryPIN = "SELECT PIN FROM Family WHERE Family_ID = '$famID'";
            $resultPIN = mysqli_query($dbc, $queryPIN);
            $rowPIN =  mysqli_fetch_assoc($resultPIN);
            
            // Show PIN
            echo "<td>" . $rowPIN['PIN'] . "</td>";
            
            // Edit button
            echo '<td class="table-button"><i class="material-icons-table" onClick="editForm(\'' . $row["Family_ID"] . '\');">edit</i></td>';
            
            // Delete button
            echo '<td class="table-button"><i class="material-icons-table" onClick="deleteParentPopup(\'' . $row["Family_ID"] . '\');">delete</i></td>';
            
            echo "</tr>";
        }
        echo "</table>";
    }
?>