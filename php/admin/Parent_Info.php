<!DOCTYPE html>
<html lang="en">
    
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
    <link href="../../css/admin/parent_info.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
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
    <p id="description">Parent Information</p>
    <div id="parent-container">
        <?php
            // connect to the database
            include('../connect-db.php');
            
            $queryAll = "SELECT DISTINCT Family_ID FROM Parent ORDER BY `Last_Name`";
                   
            $result = mysqli_query($dbc, $queryAll);
            
            $num_rows = $result->num_rows;
            
            // Iterate over the results that we got from the database
            if ($num_rows > 0){
                // Create a table that will be populated with info
                echo "<table border='1'>
                    <tr>
                    <th>Parent/Guardian 1</th>
                    <th>Parent/Guardian 2</th>
                    <th>PIN</th>
                    </tr>";
                while($row = mysqli_fetch_assoc($result)) {
                    $famID = $row["Family_ID"];
                    
                    // Creates a row for each family.
                    echo "<tr>";
                    
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
                    
                    $queryPIN = "SELECT PIN FROM Family WHERE Family_ID = '$famID'";
                    $resultPIN = mysqli_query($dbc, $queryPIN);
                    $rowPIN =  mysqli_fetch_assoc($resultPIN);
                    echo "<td>" . $rowPIN['PIN'] . "</td>";
                    echo "</tr>";
                    
                ?>
                    
                <?php
                }
            }
        ?>
    </div>
</body>