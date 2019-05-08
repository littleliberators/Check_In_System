<?php
/*-------------------------------------------------------------------------
* Name: info_child.php                                                        *
* Description:  Page to view, edit, add, and delete children. After clicking  *
*               the child tab on admin screen, a table of all of the          *
*               children will be automatically pulled up.                     *
---------------------------------------------------------------------------*/
    
    // Controller files
    include('../../Controller/Admin/child_process.php');
    include('../../Controller/Admin/populateChildTable.php');
    
    // Retrieve global variables
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
    
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script language="JavaScript" type="text/javascript" src="info_child.js"></script>
    <link href="info_child.css" type="text/css" rel="stylesheet" />
    <link href="../main.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
    
    <!-- Header -->
    <title>Child Information</title>
    <link rel="shortcut icon" href="../images/icon.ico" type="image/x-icon"/>
</head>

<body>
    <div id="header-background">
        <button id="back" onClick="document.location.href='admin.php'">
            <i class="material-icons">arrow_back</i>
            <div class="header-buttons">BACK</div>
        </button>
        <img id="title" src="../images/Block_Title2.png" alt="Little Liberators" />
        <button id="sign-out" onClick="document.location.href='../LoginScreen/login.php'">
            <i class="material-icons">logout</i>
            <div class="header-buttons">LOGOUT</div>
        </button>
    </div>
    <div id="description">Child Information</div>
    <div id="child-container">
         <div id="child-header-container">
            <button class="button-add" id="add" onclick="addChildForm();"><i class='material-icons-add'>add</i>Add Child</button>
            <div id="search">
                <input type="text" id="search-input" value="<?php echo $_SESSION['Search']; ?>" placeholder="Search.."></input>
                <button id="clear-button" onclick="clearSearch();"><i class='material-icons-search'>clear</i>Clear</button>
                <button id="search-button" onclick="search();"><i class='material-icons-search'>search</i>Search</button>
            </div>
        </div>
        <!-- Add child table -->
        <div class="table-container">
            <?php populateChildTable(); ?>
        </div>
    </div>
    
    <!-- Success message popup -->
    <div id="success" class="hide">Success</div>
    <div class="overlay hide"></div>
    
    <!-- Add child popup -->
    <div class="add-child-popup hide">
        <div id="add-child-header">
            <div id="header">Add Child</div>
            <button id="close-button" aria-label="Close" onClick="closeForm();">X</button>
        </div>
        <div id="sign-instructions" class="instructions">
            Please add child first and last names, and select the parent(s) for the child.<br>
        </div>
        <div class="instructions"><i>* Required fields</i></div>
        <form id="add-child">
            <div class="names-container">
                <div>
                    <div class="name-label" id="child-first-label">* First Name: </div>
                    <input class="input-box" id="child-first-input" name="child-fn" type="text" required
                    oninvalid="this.setCustomValidity('Please enter first name'" oninput="this.setCustomValidity('')"/>
                </div>
                <div>
                    <div class="name-label" id="child-last-label">* Last Name: </div>
                    <input class="input-box" id="child-last-input" name="child-ln" type="text" required
                    oninvalid="this.setCustomValidity('Please enter last name'" oninput="this.setCustomValidity('')"/>
                </div>
            </div>
            <div class="parents-container">
                <div>
                    <div class="name-label" id="parent-1-label">* Parent/Guardian 1</div>
                    <select class="select-box" id="select-parent1">
                        <option value="select">-- Select Parent --</option>
                    </select>
                </div>
                <div>
                    <div class="name-label" id="parent-2-label">Parent/Guardian 2</div>
                    <select class="select-box" id="select-parent2" disabled>
                        <option value="select">-- Select Parent --</option>
                    </select>
                </div>
            </div>
            <div class="error-message-label hide" id="error-message">PIN number taken</div>
            <button class="button" id="add-button" name="add-parent" type="button">Add</button>
            <button class="button" id="edit-button" name="edit-parent" type="button">Save Changes</button>
        </form>
    </div>
    
    <!-- Are you sure you want to delete popup -->
    <div class="hide" id="dialog" title="Delete">
      Are you sure you want to delete?<br>You will still be able to create pdf reports for this child.
    </div>
</body>