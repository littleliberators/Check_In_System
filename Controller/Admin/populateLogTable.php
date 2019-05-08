<?php
/*-------------------------------------------------------------------------
* Name: populateLogTable.php                                                 *
* Description:  Creates and populates a table with all of the log info.
*               Adds pagination*
---------------------------------------------------------------------------*/
    
    function populateLogTable(){
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
        
        $search = $_SESSION["Search"];
        
        // If there is input in search box, add it to the query
        if ($search == ""){
    	    $searchQuery = "";
    	    $result_count = mysqli_query($dbc,"SELECT COUNT(*) As total_records FROM `Log`");
    	} else {
    	    $searchQuery = "WHERE (CONCAT(Child.First_Name,' ', Child.Last_Name) LIKE '%$search%'
    	    OR Log_Date LIKE '%$search%'
    	    OR Sign_In_Time LIKE '%$search%'
    	    OR Sign_Out_Time LIKE '%$search%'
    	    OR E_Sign_In LIKE '%$search%'
    	    OR E_Sign_Out LIKE '%$search%')";
    	    
    	    $result_count = mysqli_query($dbc,"SELECT COUNT(*) As total_records 
    	    FROM Log, Child "
    	    .$searchQuery.
    	    " AND Log.Child_ID=Child.Child_ID");
    	}
        
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
    
        $result = mysqli_query($dbc,"SELECT Log.*, CONCAT(Child.First_Name,' ', Child.Last_Name)  AS Full_Name
        FROM Log 
        JOIN Child ON Log.Child_ID=Child.Child_ID "
        .$searchQuery.
        " ORDER BY `Log_Date` DESC,`Sign_In_Time` DESC, `Sign_Out_Time` DESC 
        LIMIT $offset, $total_records_per_page");
        
        // Show table header
        echo "
            <table id='log-table' border='1'>
                <thead>
                    <tr>
                        <th id='date-header'>Date</th>
                        <th id='name-header'>Child Name</th>
                        <th id='in-time-header'>Sign In Time</th>
                        <th id='sign-in-header'>Sign In Signature</th>
                        <th id='out-time-header'>Sign Out Time</th>
                        <th id='sign-out-header'>Sign Out Signature</th>
                        <th id='edit-header'>Edit</th>
                        <th id='delete-header'>Delete</th>
                    </tr>
                </thead>
                <tbody>
        ";
        
        // Format row data
        while($row = mysqli_fetch_array($result)){
    		echo "<tr>";
            
            // Date
            echo "<td>" . date( 'm-d-Y', strtotime($row['Log_Date'])) . "</td>";
            
            // Child Name
            // $childID = $row['Child_ID'];
            // $queryChild = "SELECT First_Name, Last_Name FROM Child WHERE Child_ID = '$childID'";
            // $resultChild = mysqli_query($dbc, $queryChild);
            // $rowChild = mysqli_fetch_assoc($resultChild);
            echo "<td>" . $row['Full_Name'] . "</td>";
            
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
                
            // Delete
            echo '<td class="table-button"><i class="material-icons-table" onClick="deleteLogPopup(\'' . $row["Log_ID"] . '\');">delete</i></td>';
            
            echo "</tr>";
        }
        
        // Closing tags for table
        echo "
        </tbody>
            </table>
        ";
        
        addPagination();
        
    	mysqli_close($dbc);
    }
    
    // Saves user input in search box as a global variable
    function saveSearchString($value){
        $_SESSION["Search"] = $value;
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