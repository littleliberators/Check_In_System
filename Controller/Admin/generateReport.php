<?php
/*-------------------------------------------------------------------------
* Name: generateReport.php                                                  *
* Description:  Creates a pdf based on user input and opens it in a         *
*               new tab. The user can download it or print it from the      *
*               new tab.                                                    *
---------------------------------------------------------------------------*/

    // Extend the TCPDF class to create custom Header and Footer
    class MYPDF extends TCPDF {
    
    	// Page footer
    	public function Footer() {
    		// Position at 15 mm from bottom
    		$this->SetY(-15);
    		// Set font
    		$this->SetFont('helvetica', 'I', 8);
    		// Page number
    		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    	}
    }
    
    // Create the header cells for each table
    function table_header() {
        $header = '
            <br /><table border="0" cellspacing="0" cellpadding="3">  
                <tr align="center" style="font-weight:bold; background-color:#888; color: #fff;">  
                   <th width="20%">Date</th>  
                   <th width="15%">In Time</th>  
                   <th width="25%">In Signature</th>  
                   <th width="15%">Out Time</th>
                   <th width="25%">Out Signature</th> 
                </tr> 
        ';
        
        return $header;
    }
    
    // Add a row to the table with log info
    function add_Row($row) {
        // Date
        $date = date( 'D, M d', strtotime($row['Log_Date']));
        
        // Sign In Time
        if ($row['Sign_In_Time'] == ""){ 
            $in_time = "";
        }
        else { 
            $in_time = date( 'g:i A', strtotime($row['Sign_In_Time']));
        }
        
        // Sign In Signature
        $in_sign = $row['E_Sign_In'];
        
        // Sign Out Time
        if ($row['Sign_Out_Time'] == ""){ 
            $out_time = "";
        }
        else { 
            $out_time = date( 'g:i A', strtotime($row['Sign_Out_Time']));
        }
        
        // Sign Out Signature
        $out_sign = $row['E_Sign_Out'];
        
        // Add all of the info as a row in table
        return '
            <tr>
                <td align="center" style="border-bottom: 1px solid #ddd;">'.$date.'</td>
                <td align="center" style="border-bottom: 1px solid #ddd;">'.$in_time.'</td>
                <td align="center" style="border-bottom: 1px solid #ddd;">'.$in_sign.'</td>
                <td align="center" style="border-bottom: 1px solid #ddd;">'.$out_time.'</td>
                <td align="center" style="border-bottom: 1px solid #ddd;">'.$out_sign.'</td>
            </tr>
        ';
    }
    
    // Add a row to the table, but keep the date field empty
    function add_Row_noDate($row){
        // Sign In Time
        if ($row['Sign_In_Time'] == ""){ 
            $in_time = "";
        }
        else { 
            $in_time = date( 'g:i A', strtotime($row['Sign_In_Time']));
        }
        
        // Sign In Signature
        $in_sign = $row['E_Sign_In'];
        
        // Sign Out Time
        if ($row['Sign_Out_Time'] == ""){ 
            $out_time = "";
        }
        else { 
            $out_time = date( 'g:i A', strtotime($row['Sign_Out_Time']));
        }
        
        // Sign Out Signature
        $out_sign = $row['E_Sign_Out'];
        
        // Add all of the info as a row in table
        return '
            <tr>
                <td align="center" style="border-bottom: 1px solid #ddd;"></td>
                <td align="center" style="border-bottom: 1px solid #ddd;">'.$in_time.'</td>
                <td align="center" style="border-bottom: 1px solid #ddd;">'.$in_sign.'</td>
                <td align="center" style="border-bottom: 1px solid #ddd;">'.$out_time.'</td>
                <td align="center" style="border-bottom: 1px solid #ddd;">'.$out_sign.'</td>
            </tr>
        ';
    }
    
    // Show an empty row to indicate new week
    function add_new_week() {
        // Return an empty row with a gray background
        return '
            <tr style="background-color: #ddd;">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        ';
    }
    
    function add_empty_row($date) {
        // Date
        $current_date = date( 'D, M d', strtotime($date));
        
        // Add empty row with only the date
        return '
            <tr>
                <td align="center" style="border-bottom: 1px solid #ddd;">'.$current_date.'</td>
                <td align="center" style="border-bottom: 1px solid #ddd;"></td>
                <td align="center" style="border-bottom: 1px solid #ddd;"></td>
                <td align="center" style="border-bottom: 1px solid #ddd;"></td>
                <td align="center" style="border-bottom: 1px solid #ddd;"></td>
            </tr>
        ';
    }
    
    // Display Child name
    function show_child($name){
        return '<br /><div style="font-weight:bold; font-size: 20pt;">'.$name.'</div>';
    }
    
    // Generates the data for pdf which includes each of the selected children's logs
    function output_children($child_ids, $start_date, $end_date) {
        include('../../Model/connect-db.php');
        $output = '';   
        
        // Create table for each child
        foreach ($child_ids as $child_id){
            $queryChild = 'SELECT * FROM Child WHERE Child_ID = '.$child_id.' ORDER BY `First_Name`';
            $resultChild = mysqli_query($dbc, $queryChild);
            
            while($rowChild = mysqli_fetch_array($resultChild)) {
                // Display Child name
                $childID = $rowChild['Child_ID'];
                $name = $rowChild['First_Name'] . " " . $rowChild['Last_Name'];
                $output .= show_child($name);
                
                // Put specified dates into array
                $day_array = array();
                $current_day = $start_date;
                
                // Add each day between start date and end date into array
                while(($current_day != (date("Y-m-d", strtotime("+1 day", strtotime($end_date)))))){
                    $day_array[] = $current_day;
                    $current_day = date("Y-m-d", strtotime("+1 day", strtotime($current_day)));
                }
                
                // Keeps track of the days that the child was present. If 0 at the end of the loop,
                // then there are no recorded logs for the specified dates.
                $daysPresent = 0;
                
                // Start a new table
                $child_table = '';
                $child_table .= table_header();
                
                // Get all the logs for each date
                for($i=0; $i< sizeof($day_array); $i++)
                {
                    $query = "SELECT * 
                    FROM Log 
                    WHERE Child_ID = ".$childID." 
                    AND Log_Date = '".$day_array[$i]."'
                    ORDER BY `Log_Date` DESC,`Sign_In_Time` ASC, `Sign_Out_Time` ASC;";
                
                    $result = mysqli_query($dbc, $query);
                    if ($result === FALSE) {
                        echo mysqli_error($result);
                    }
                    $numRows = mysqli_num_rows($result);
                    
                    // Check if the the child was present that day
                    if ($numRows > 0) {
                        $daysPresent++;
                        $numLogs = 1;
                        
                        while($row = mysqli_fetch_array($result)) {
                            // If first row, add a row of log info to table, including date
                            if ($numLogs<2){
                                $child_table .= add_Row($row);
                            }
                            // If more than 1 rows, leave the date column empty
                            else {
                                $child_table .= add_Row_noDate($row);
                            }
                            $numLogs++;
                        }
                    }
                    // The child was not present that day
                    else {
                        $day_name = date('D', strtotime($day_array[$i]));
                        
                        // Add empty row if not saturday or sunday
                        if (!($day_name =='Sat' || $day_name == 'Sun')){
                            $child_table .= add_empty_row($day_array[$i]);
                        }
                    }
                    
                    // If day is a saturday, add empty row to show new week.
                    if ((date('D', strtotime($day_array[$i]))) == 'Sat'){
                        $child_table .= add_new_week();
                    }
                }
                if ($daysPresent == 0) {
                    $output .= '<div style="font-style: italic; font-size: 12pt; padding-top: none;">There are no recorded sign-in in or sign-out times for '.$name.'.</div>';
                }
                else {
                    $output .= $child_table;
                    $output .= '</table><br />';
                }
            }
        }
        return $output;
    }
    
    // Creates the pdf after button is clicked
    if (isset($_POST["create_pdf"])) {
       
        $data = '';
        
        // Gets the chosen date range
        $daterange = $_POST['daterange'];
        
        // Separates dates into array
        $dates_array = explode(" ", $daterange);
        $start = $dates_array[0];
        $end = $dates_array[2];
        
        // Convert each date back into a DateTime object
        $form_start = new DateTime($start);
        $form_end = new DateTime($end);
        
        // Formats dates to match the dates saved in mysql
        $sql_start = $form_start->format('Y-m-d');
        $sql_end = $form_end->format('Y-m-d');
        
        // Create the tables and store in a variable for to add to PDF later
        $childID_array = $_POST['select-child'];
        $data .= output_children($childID_array, $sql_start, $sql_end);
        
        // Create a new pdf
        $obj_pdf = new MYPDF('p', PDF_UNIT, 'LETTER', true, 'UTF-8', false);  
        $obj_pdf->SetCreator(PDF_CREATOR);  
        $obj_pdf->SetTitle("Little Liberators Report");  
        $obj_pdf->SetHeaderData('Block_Title2.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);  
        $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
        $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
        $obj_pdf->SetDefaultMonospacedFont('helvetica');  
        $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
        $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);  
        $obj_pdf->setPrintHeader(false);  
        $obj_pdf->setPrintFooter(true);  
        $obj_pdf->SetAutoPageBreak(TRUE, 20);  
        $obj_pdf->SetFont('helvetica', '', 11);  
        $obj_pdf->AddPage(); 
        $html = '
            <h1 color="#444">Log Report</h1>
            <p color="#555"><font size="12pt">'.$start.' - '.$end.'</font></p>
        ';
        $obj_pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, 'J', true);
        $obj_pdf->Image('../../View/images/Block_Title2.png', 120, 5, 80, 0, 'PNG', '', 'N', false, '', '', false, false, 0, false, false, false);
        $content = ''; 
        $content .= $data;
        $obj_pdf->writeHTML($content);  
        $obj_pdf->Output('report.pdf', 'I');
    }
?>