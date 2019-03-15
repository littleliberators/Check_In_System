<?php
    // connect to the database
    include('../connect-db.php');
    
    $query = "SELECT * FROM Log ORDER BY `Log_Date` DESC,`Sign_In_Time` DESC, `Sign_Out_Time` DESC;"; //`Sign_Out_Time`, `DateTimeStamp` ASC;";
    $result = mysqli_query($dbc, $query);
    
     // Create a table that will be populated with info
        echo "<table id='log-table' border='1'>
            <tr>
                <th id='date-header'>Date</th>
                <th id='name-header'>Child Name</th>
                <th id='in-time-header'>Sign In Time</th>
                <th id='sign-in-header'>Sign In Signature</th>
                <th id='out-time-header'>Sign Out Time</th>
                <th id='sign-out-header'>Sign Out Signature</th>
                <th id='edit-header'>Edit</th>
                <th id='delete-header'>Delete</th>
            </tr>";
    
    
    // Iterate over the results that we got from the database
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        
        // Date
        echo "<td>" . date( 'm-d-Y', strtotime($row['Log_Date'])) . "</td>";
        
        // Child Name
        $childID = $row['Child_ID'];
        $queryChild = "SELECT First_Name, Last_Name FROM Child WHERE Child_ID = '$childID'";
        $resultChild = mysqli_query($dbc, $queryChild);
        $rowChild = mysqli_fetch_assoc($resultChild);
        echo "<td>" . $rowChild['First_Name'] . " " . $rowChild['Last_Name'] . "</td>";
        
        // Sign In Time
        if ($row['Sign_In_Time'] == ""){ 
            echo "<td style='background-color: #FFC4C4'></td>"; // Missing sign in time
        }
        else { 
            echo "<td>" . date( 'g:i A', strtotime($row['Sign_In_Time'])) . "</td>"; 
        }
        
        // Sign In Signature
        if ($row['E_Sign_In'] == ""){
            echo "<td style='background-color: #FFC4C4'></td>"; // Missing sign in signature
        }
        else {
            echo "<td>" . $row['E_Sign_In'] . "</td>";
        }
        
        // Sign Out Time
        if ($row['Sign_Out_Time'] == ""){ 
            echo "<td style='background-color: #FFC4C4'></td>"; // Missing sign out time
        }
        else { 
            echo "<td>" . date( 'g:i A', strtotime($row['Sign_Out_Time'])) . "</td>"; 
        }
        
        // Sign Out Signature
        if ($row['E_Sign_Out'] == ""){
            echo "<td style='background-color: #FFC4C4'></td>"; // Missing sign out signature
        }
        else {
            echo "<td>" . $row['E_Sign_Out'] . "</td>";
        }
        
        // Edit
        echo '<td class="table-button"><i class="material-icons-table" onClick="editForm(\'' . $row["Log_ID"] . '\');">edit</i></td>';
            
        //Delete
        echo '<td class="table-button"><i class="material-icons-table" onClick="deleteLogPopup(\'' . $row["Log_ID"] . '\');">delete</i></td>';
        
        echo "</tr>";
    }
        
?>