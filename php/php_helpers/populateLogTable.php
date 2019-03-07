<?php
    // connect to the database
    include('../connect-db.php');
    
    $query = "SELECT * FROM Log ORDER BY `Log_Date` DESC,`Sign_In_Time` DESC, `Sign_Out_Time` DESC;"; //`Sign_Out_Time`, `DateTimeStamp` ASC;";
    $result = mysqli_query($dbc, $query);
    
     // Create a table that will be populated with info
        echo "<table id='child-table' border='1'>
            <tr>
                <th>Date</th>
                <th>Child Name</th>
                <th>Sign In Time</th>
                <th>Sign In Signature</th>
                <th>Sign Out Time</th>
                <th>Sign Out Signature</th>
            </tr>";
    
    
    // Iterate over the results that we got from the database
    while($row = mysqli_fetch_assoc($result)) {
        // Creates a row for Log entry
        echo "<tr>";
        
        // Date
        echo "<td>" . date( 'd-m-Y', strtotime($row['Log_Date'])) . "</td>";
        
        // Child Name
        $childID = $row['Child_ID'];
        $queryChild = "SELECT First_Name, Last_Name FROM Child WHERE Child_ID = '$childID'";
        $resultChild = mysqli_query($dbc, $queryChild);
        $rowChild = mysqli_fetch_assoc($resultChild);
        echo "<td>" . $rowChild['Last_Name'] . ", " . $rowChild['First_Name'] . "</td>";
        
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
        
        echo "</tr>";
    }
        
?>