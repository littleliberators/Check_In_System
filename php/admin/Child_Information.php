<!DOCTYPE html>
<html lang="en">
    
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
    <link href="../../css/admin/child_info.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
</head>

<body>
    <div id="header-background">
        <button id="back" onClick="document.location.href='../Admin.php'">
            <i class="material-icons">arrow_back</i>
            <div class="header-buttons">BACK</div>
        </button>
        <img id="title" src="../../images/Block_Title2.png" alt="Little Liberators" />
        <button id="sign-out" onClick="document.location.href='../Login.php'">
            <i class="material-icons">logout</i>
            <div class="header-buttons">LOGOUT</div>
        </button>
    </div>
    <p id="description">Child Information</p>
    <div id="child-container">
        <?php
            // connect to the database
            include('../connect-db.php');
            
            $queryChildren = "SELECT * FROM Child ORDER BY `Last_Name`;";
                   
            $result = mysqli_query($dbc, $queryChildren);
            
            $num_rows = $result->num_rows;
            
            // Iterate over the results that we got from the database
            if ($num_rows > 0){
                // Create a table that will be populated with info
                echo "<table border='1'>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Parent/Guardian 1</th>
                        <th>Parent/Guardian 2</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>";
                
                while($row = mysqli_fetch_assoc($result)) {
                    // Creates a row for each child.
                    echo "<tr>";
                    
                    // First Name
                    echo "<td>" . $row['First_Name'] . "</td>";
                    
                    // Last Name
                    echo "<td>" . $row['Last_Name'] . "</td>";
                    
                    // Parents
                    $famID = $row['Family_ID'];
                    $queryParents = "SELECT First_Name, Last_Name FROM Parent WHERE Family_ID = '$famID'";
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
                    
                    // Edit button
                    echo "<td class='table-button'><i class='material-icons-table'>edit</i></td>";
                    
                    // Delete button
                    echo "<td class='table-button'><i class='material-icons-table'>delete</i></td>";
                    echo "</tr>";
                }
                
                echo "</table>";
            }
            else 
            {
                echo "No child entries found";
            }
        ?>
        <div style="text-align:center;">
            <button id="add"><i class='material-icons-add'>add</i>Add Child</button>
        </div>
    </div>
</body>