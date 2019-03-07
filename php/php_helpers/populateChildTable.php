<?php
    // connect to the database
    include('../connect-db.php');
    
    $queryChildren = "SELECT * FROM Child ORDER BY `Last_Name`;";
           
    $result = mysqli_query($dbc, $queryChildren);
    
    $num_rows = $result->num_rows;
    
    // Iterate over the results that we got from the database
    if ($num_rows > 0){
        // Create a table that will be populated with info
        echo "<table id='child-table' border='1'>
            <tr>
                <th>Child Name</th>
                <th>Parent/Guardian 1</th>
                <th>Parent/Guardian 2</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>";
        
        while($row = mysqli_fetch_assoc($result)) {
            // Creates a row for each child.
            echo "<tr>";
            
            // Child Name
            echo "<td>" . $row['Last_Name'] . ", " . $row['First_Name'] . "</td>";
            
            // Parents
            $famID = $row['Family_ID'];
            $queryParents = "SELECT First_Name, Last_Name FROM Parent WHERE Family_ID = '$famID'";
            $resultParents = mysqli_query($dbc, $queryParents);
            $numRowsParents = $resultParents->num_rows;
            
            // Only 1 parent
            if ($numRowsParents == 1){
                $rowParents = mysqli_fetch_assoc($resultParents);
                echo "<td>" . $rowParents['Last_Name'] . ", " . $rowParents['First_Name'] . "</td>";
                echo "<td> </td>";
            }
            // 2 parents
            else if ($numRowsParents == 2){
                 while($rowParents = mysqli_fetch_assoc($resultParents)){
                     echo "<td>" . $rowParents['Last_Name'] . ", " . $rowParents['First_Name'] . "</td>";
                 }
            }
            else if ($numRowsParents == 0){
                echo "<td style='background-color: #FFC4C4;'></td>";
                echo "<td style='background-color: #FFC4C4;'></td>";
                // echo "Error: No parents found for id - " . $famID . "<br>";
            }
            else{
                echo "Error: There are more than 2 instances of parents for one family<br>";
            }
            
            // Edit button
            echo '<td class="table-button"><i class="material-icons-table" onClick="editForm(\'' . $row["Child_ID"] . '\');">edit</i></td>';
            
            // Delete button
            echo '<td class="table-button"><i class="material-icons-table" onClick="deleteChildPopup(\'' . $row["Child_ID"] . '\');">delete</i></td>';
            echo "</tr>";
        }
        
        echo "</table>";
    }
    else 
    {
        echo "No child entries found";
    }
?>