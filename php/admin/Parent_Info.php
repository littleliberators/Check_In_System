<?php
        
    function populateParentTable() {
        include('../php_helpers/populateParentTable.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
    
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
    <script language="JavaScript" type="text/javascript" src="../../javascript/admin/Parent_Info.js"></script>
    <link href="../../css/admin/parent_info.css" type="text/css" rel="stylesheet" />
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
    <div id="description">Parent Information</div>
    <div style="text-align:center;">
        <button id="add" onclick="addParentForm();"><i class='material-icons-add'>add</i>Add Parent(s)</button>
    </div>
    <div id="parent-container">
        <?php
            populateParentTable();
        ?>
    </div>
    
    <!-- Add parent popup -->
    <div class="overlay hide"></div>
    <div class="add-parent-popup hide">
        <div id="add-parent-header">
            <div id="header">Add Parent(s)</div>
            <button id="close-button" aria-label="Close" onClick="closeForm();">X</button>
        </div>
        <div id="sign-instructions">
            Please add parent first and last name(s) for one family.<br>
            <i>* Required fields</i>
        </div>
        <form id="add-parent" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <div class="names-container">
                <div id="parent1-container">
                    <div class="parent-label">* Parent/Guardian 1</div>
                    <div class="name-label">* First Name: </div>
                    <input class="input-box" id="p1-fn-input" name="p1-first-name" type="text" required
                    oninvalid="this.setCustomValidity('Please enter first name'" oninput="this.setCustomValidity('')"/>
                    <div class="name-label">* Last Name: </div>
                    <input class="input-box" id="p1-ln-input" name="p1-last-name" type="text" required
                    oninvalid="this.setCustomValidity('Please enter last name'" oninput="this.setCustomValidity('')"/>
                </div>
                <div id="parent2-container">
                    <div class="parent-label">Parent/Guardian 2</div>
                    <div class="name-label">First Name: </div>
                    <input class="input-box" id="p2-fn-input" name="p2-first-name" type="text" 
                    oninvalid="this.setCustomValidity('Please enter first name'" oninput="this.setCustomValidity('')"/>
                    <div class="name-label">Last Name: </div>
                    <input class="input-box" id="p2-ln-input" name="p2-last-name" type="text" 
                    oninvalid="this.setCustomValidity('Please enter last name'" oninput="this.setCustomValidity('')"/>
                </div>
            </div>
            <div id="pin-container">
                <div id="pin-label">* PIN #: </div>
                <input class="input-box" id="PIN" name="PIN" type="text" pattern="\d{4,}" autocomplete="off" required
                oninvalid="this.setCustomValidity('4 digit PIN required.')" oninput="this.setCustomValidity('')"/>
            </div>
            <button id="add-button" name="add-parent">Add</button>
        </form>
    </div>
    
    <?php
         // If add gets clicked
        if(isset($_POST['add-parent']))
        {
            $p1_first_name = $_POST['p1-first-name'];
            $p1_last_name = $_POST['p1-last-name'];
            $p2_first_name = $_POST['p2-first-name'];
            $p2_last_name = $_POST['p2-last-name'];
            $pinNum = $_POST['PIN'];
            
            // connect to the database
            include('../connect-db.php');
            
            // Check if PIN already exists
            // $checkQuery = "SELECT * from Family where PIN='$pinNum'";
            // $result = mysqli_query($dbc, $checkQuery);
            // $num_rows = $result->num_rows;
            
            // Iterate over the results that we got from the database
            // if ($num_rows > 0){
                
            // }
            
            $query = "INSERT INTO Family (PIN) VALUES ('$pinNum')";
            
            // Creates a record in Family table
            if ($dbc->query($query) === TRUE) {
                $family_id = $dbc->insert_id;
            } else {
                echo "Error: " . $query . "<br>" . $dbc->error;
            }
            
            $p1query = "INSERT INTO Parent (Family_ID, First_Name, Last_Name) VALUES ('$family_id', '$p1_first_name', '$p1_last_name')";
            
            // Creates a record in Parent table for Parent 1
            if ($dbc->query($p1query) === FALSE) {
                echo "Error: " . $p1query . "<br>" . $dbc->error;
            }
            
            if (empty($p2_first_name)) {
                if (empty($p2_last_name)){
                    // Parent 2 is not added
                }
                else {
                    /* Last name added, missing first name. Ask user to enter first name. */
                }
            }
            else { // Add parent 2 to db
                if (empty($p2_last_name))
                {
                    /* First name added, missing last name. Ask user to enter last name. */
                }
                else {
                    $p2query = "INSERT INTO Parent (Family_ID, First_Name, Last_Name) VALUES ('$family_id', '$p2_first_name', '$p2_last_name')";
                    
                    // Creates a record in Parent table for Parent 1
                    if ($dbc->query($p2query) === FALSE) {
                        echo "Error: " . $p2query . "<br>" . $dbc->error;
                    }
                    
                    echo '<script type="text/javascript">',
                                'deleteTable();',
                                '</script>'; 
                    
                     include('../php_helpers/populateParentTable.php');
                }
            }

            $dbc->close();
        }
    ?>
</body>