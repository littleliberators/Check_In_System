<?php
/*-------------------------------------------------------------------------
* Name: populateParentTable.php                                               *
* Description:  Creates and populates a table with all of the parent info.    *
*               Adds pagination to the bottom of the table .                  *
---------------------------------------------------------------------------*/
    // Retrieve global variables
    session_start();
    
    // Creates new parent table
    function populateParentTable(){
        // Connect to the database
        include('../../Model/connect-db.php');
        
        // Global variables
        global $page_no, $total_records_per_page, $offset, $previous_page, $next_page, $adjacents, $total_no_of_pages, $second_last, $search;
        
        // Get page number
        if (isset($_GET['page_no']) && $_GET['page_no']!="") {
    	    $page_no = $_GET['page_no'];
    	} else {
		    $page_no = 1;
        }
        
        // Save the search string as global variable
        $search = $_SESSION["Search_Parent"];
        
        // Clear the search variable after it is saved
        if(isset($_SESSION['Search_Parent'])){ 
            unset($_SESSION['Search_Parent']);
         }
         
         // If there is input in search box, add it to the query
        if ($search == ""){
    	    $searchQuery = "";
    	    $result_count = mysqli_query($dbc,"SELECT COUNT(*) As total_records FROM `Family`");
    	} else {
    	    $searchQuery = "HAVING (Parent_Name_1 LIKE '%$search%'
    	    OR Parent_Name_2 LIKE '%$search%'
    	    OR f.PIN LIKE '%$search%')";
    	    
    	    $result_count = mysqli_query($dbc,"SELECT COUNT(*) As total_records FROM (
    	        SELECT
                (
                    SELECT CONCAT(p.First_Name,' ', p.Last_Name)
                    From Parent p
                    WHERE f.Family_ID=p.Family_ID
                    LIMIT 1
                ) as Parent_Name_1,
                IfNull((
                    SELECT CONCAT(p.First_Name,' ', p.Last_Name)
                    From Parent p
                    WHERE f.Family_ID=p.Family_ID
                    LIMIT 1, 1
                ), '') as Parent_Name_2,
                f.PIN
                FROM Family f "
                . $searchQuery .
                " ) rowCount;");
    	}
    	
    	// Pagination values
    	$total_records_per_page = 15;
        $offset = ($page_no-1) * $total_records_per_page;
    	$previous_page = $page_no - 1;
    	$next_page = $page_no + 1;
    	$adjacents = "2"; 
    	
    	$total_records = mysqli_fetch_array($result_count);
    	$total_records = $total_records['total_records'];
        $total_no_of_pages = ceil($total_records / $total_records_per_page);
    	$second_last = $total_no_of_pages - 1; // total page minus 1
    	
    	if ($total_no_of_pages == 0){
    	    $page_no = 0;
    	}
    	
    	// Select all of the Parent records from the database
    	$query = "
    	SELECT f.Family_ID,
            (
                SELECT CONCAT(p.First_Name,' ', p.Last_Name)
                From Parent p
                WHERE f.Family_ID=p.Family_ID
                LIMIT 1
            ) as Parent_Name_1,
            IfNull((
                SELECT CONCAT(p.First_Name,' ', p.Last_Name)
                From Parent p
                WHERE f.Family_ID=p.Family_ID
                LIMIT 1, 1
            ), '') as Parent_Name_2,
            f.PIN
            FROM Family f "
            . $searchQuery .
            " ORDER BY Parent_Name_1
            LIMIT $offset, $total_records_per_page;
    	";
    	
    	$result = mysqli_query($dbc, $query);
        
        if (!$result) {
            printf("Error: %s\n", mysqli_error($dbc));
            exit();
        }
        
        //Show table header
        echo "<table id='parent-table' border='1'>
                <tr>
                <th>Parent/Guardian 1</th>
                <th>Parent/Guardian 2</th>
                <th>PIN</th>
                <th>Edit</th>
                <th>Delete</th>
                </tr>";
        
        
        // Iterate over the results that we got from the database
        while($row = mysqli_fetch_assoc($result)) {
                
            // Creates a row for each family.
            echo "<tr>";
            
            // Parent 1
            echo "<td>" . $row['Parent_Name_1'] . "</td>";
            
            // Parent 2
            echo "<td>" . $row['Parent_Name_2'] . "</td>";
            
            // Show PIN
            echo "<td>" . $row['PIN'] . "</td>";
                
            // Edit button
            echo '<td class="table-button"><i class="material-icons-table" onClick="editForm(\'' . $row["Family_ID"] . '\');">edit</i></td>';
            
            // Delete button
            echo '<td class="table-button"><i class="material-icons-table" onClick="deleteParentPopup(\'' . $row["Family_ID"] . '\');">delete</i></td>';
            echo "</tr>";
        }
        echo "</table>";
        addPagination();
        mysqli_close($dbc);
    }
    
    // Saves user input in search box as a global variable
    function saveSearchString($value){
        $_SESSION["Search_Parent"] = $value;
    }
    
    // Add the pagination bar at the bottom of the table
    function addPagination(){
        echo "<div id='pagination-info'>
                <div id='pages-label'>
                    <strong>Page ".getPageNumbers()."</strong>
                </div>
                <ul class='pagination'>
                    ".showFirst()."
                	<li ".disablePrevious().">
                	    <a ".showPrevious().">Previous</a>
                	</li>
                    ".showPageNumberLinks()."
                	<li ".disableNext().">
                	    <a ".showNext().">Next</a>
                	</li>
                    ".showLast()."
                </ul>
            </div>
        ";
    }
    
    // Show links to the page numbers at the bottom of the table
    function showPageNumberLinks(){
        // Global variables
        global $total_no_of_pages, $page_no, $second_last, $adjacents;
        $page_links = "";
        
        // If 10 or less total pages, go ahead and show all of them.
        if ($total_no_of_pages <= 10){  	 
    		for ($counter = 1; $counter <= $total_no_of_pages; $counter++){
    			if ($counter == $page_no) {
    		        $page_links.="<li class='active'><a>$counter</a></li>";	
				} else {
                    $page_links.="<li><a href='?page_no=$counter'>$counter</a></li>";
				}
            }
    	}
    	// If greater than 10 pages
    	else if($total_no_of_pages > 10){
    	    // If current page number is 4 or less, show the ellipses after the 7th page
            if($page_no <= 4) {			
                for ($counter = 1; $counter < 8; $counter++){		 
                    if ($counter == $page_no) {
                        $page_links.="<li class='active'><a>$counter</a></li>";	
                	} else {
                        $page_links.="<li><a href='?page_no=$counter'>$counter</a></li>";
            	    }
                }
                $page_links.="<li><a>...</a></li>";
                $page_links.="<li><a href='?page_no=$second_last'>$second_last</a></li>";
                $page_links.="<li><a href='?page_no=$total_no_of_pagesh'>$total_no_of_pages</a></li>";
            }
            // If current page number is higher, show ellipses in the beginning and end
            else if($page_no > 4 && $page_no < $total_no_of_pages - 4) {		 
                $page_links.="<li><a href='?page_no=1'>1</a></li>";
                $page_links.="<li><a href='?page_no=2'>2</a></li>";
                $page_links.="<li><a>...</a></li>";
                for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {			
                    if ($counter == $page_no) {
                        $page_links.="<li class='active'><a>$counter</a></li>";	
                	} else {
                        $page_links.="<li><a href='?page_no=$counter'>$counter</a></li>";
                	}                  
                }
                $page_links.="<li><a>...</a></li>";
                $page_links.="<li><a href='?page_no=$second_last'>$second_last</a></li>";
                $page_links.="<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";      
            }
            // Current page number is near the end, show the ellipses after the second page
            else {
                $page_links.="<li><a href='?page_no=1'>1</a></li>";
                $page_links.="<li><a href='?page_no=2'>2</a></li>";
                $page_links.="<li><a>...</a></li>";
                
                for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
                    if ($counter == $page_no) {
                        $page_links.="<li class='active'><a>$counter</a></li>";	
                	}else{
                        $page_links.="<li><a href='?page_no=$counter'>$counter</a></li>";
                	}                   
                }
            }
	    }
	    return $page_links;
    }
    
    // Show user how many total page numbers there are
    function getPageNumbers(){
        global $page_no, $total_no_of_pages;
        return $page_no." of ".$total_no_of_pages;
    }
    
    // Show the First button
    function showFirst(){
        global $page_no, $total_no_of_pages;
        if($page_no > 1){
		    return "<li><a href='?page_no=1'>&laquo; First</a></li>";
        }
    }
    
    // Disable Previous button
    function disablePrevious(){
        global $page_no;
        if($page_no <= 1){ 
            return "class='disabled'"; 
        }
    }
    
    // Show the Previous button
    function showPrevious(){
        global $page_no, $previous_page;
        if($page_no > 1){ 
            return "href='?page_no=$previous_page'"; 
        }
    }
    
    // Disable Next button
    function disableNext(){
        global $page_no, $total_no_of_pages;
        if($page_no >= $total_no_of_pages){ 
            return "class='disabled'"; 
        }
    }
    
    // Show Next button
    function showNext(){
        global $page_no, $total_no_of_pages, $next_page;
        if($page_no < $total_no_of_pages) { 
            return "href='?page_no=$next_page'"; 
        }
    }
    
    // Show the Last button
    function showLast(){
        global $page_no, $total_no_of_pages;
        if($page_no < $total_no_of_pages){
		    return "<li><a href='?page_no=$total_no_of_pages'>Last &raquo;</a></li>";
        }
    }
?>