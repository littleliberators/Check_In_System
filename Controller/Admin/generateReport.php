<?php
/*-------------------------------------------------------------------------
* Name: generateReport.php                                                  *
* Description:  Creates a pdf based on user input and opens it in a         *
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
            <table nobr="true" border="1" cellspacing="0" cellpadding="3">  
                <tr nobr="true" align="center" style="font-weight:bold; background-color:#888; color: #fff;">  
                   <th width="20%">Date</th>  
                   <th width="15%">In Time</th>  
                   <th width="25%">In Signature</th>  
                   <th width="15%">Out Time</th>
                   <th width="25%">Out Signature</th> 
                </tr> 
        ';
        
        return $header;
    }
    
    // When 'select-all' was clicked.
    // Generates the data for pdf which includes ALL of the children's logs
    function output_all($start_date, $end_date) {
        include('../../Model/connect-db.php');
        $output = '';   
        
        //Each child
        $queryChild = 'SELECT * FROM Child ORDER BY `First_Name`';
        $resultChild = mysqli_query($dbc, $queryChild);
        
        while($rowChild = mysqli_fetch_array($resultChild)) {
            $childID = $rowChild['Child_ID'];
            $name = $rowChild['First_Name'] . " " . $rowChild['Last_Name'];
            
            //Child name
            $output .= '<br /><br /><div style="font-weight:bold; font-size: 16pt;">'.$name.'</div><br />';
            
            // Start a new table
            $output .= table_header();
            
            $query = "SELECT * 
                FROM Log 
                WHERE Child_ID = ".$childID." 
                AND (Log_Date BETWEEN '".$start_date."' AND '".$end_date."') 
                ORDER BY `Log_Date` DESC,`Sign_In_Time` DESC, `Sign_Out_Time` DESC;";
            
            $result = mysqli_query($dbc, $query);
            
            while($row = mysqli_fetch_array($result)) {
                $date = date( 'm-d-Y', strtotime($row['Log_Date']));
                $in_time = date( 'g:i A', strtotime($row['Sign_In_Time']));
                $in_sign = $row['E_Sign_In'];
                $out_time = date( 'g:i A', strtotime($row['Sign_Out_Time']));
                $out_sign = $row['E_Sign_Out'];
                
                $output .= '
                    <tr nobr="true">
                        <td>'.$date.'</td>
                        <td>'.$in_time.'</td>
                        <td>'.$in_sign.'</td>
                        <td>'.$out_time.'</td>
                        <td>'.$out_sign.'</td>
                    </tr>
                ';
            }
            $output .= '</table>';
        }
        return $output;
    }
    
    // When individual child was clicked.
    // Generates the data for pdf based on which child user chose.
    function output_child($childID, $start_date, $end_date) {
        include('../../Model/connect-db.php');
        $output = '';   
        
        // Get child name
        $queryChild = 'SELECT * FROM Child WHERE Child_ID = '.$childID.'';
        $resultChild = mysqli_query($dbc, $queryChild);
        $rowChild = mysqli_fetch_array($resultChild);
        $name = $rowChild['First_Name'] . " " . $rowChild['Last_Name'];
            
        // Display child name
        $output .= '<br /><br /><div style="font-weight:bold; font-size: 16pt;">'.$name.'</div><br />';
            
        // Start a new table
        $output .= table_header();
            
        $query = "SELECT * 
            FROM Log 
            WHERE Child_ID = ".$childID." 
            AND (Log_Date BETWEEN '".$start_date."' AND '".$end_date."') 
            ORDER BY `Log_Date` DESC,`Sign_In_Time` DESC, `Sign_Out_Time` DESC;";
            
        $result = mysqli_query($dbc, $query);
            
        while($row = mysqli_fetch_array($result)) {
            $date = date( 'm-d-Y', strtotime($row['Log_Date']));
            $in_time = date( 'g:i A', strtotime($row['Sign_In_Time']));
            $in_sign = $row['E_Sign_In'];
            $out_time = date( 'g:i A', strtotime($row['Sign_Out_Time']));
            $out_sign = $row['E_Sign_Out'];
                
            $output .= '
                <tr nobr="true">
                    <td>'.$date.'</td>
                    <td>'.$in_time.'</td>
                    <td>'.$in_sign.'</td>
                    <td>'.$out_time.'</td>
                    <td>'.$out_sign.'</td>
                </tr>
            ';
        }
        
        $output .= '</table>';
        return $output;
    }
    
    // Creates the pdf after button is clicked
    if (isset($_POST["create_pdf"])) {
        
        // Validate either 'select all' or one child selection made
        if ((isset($_POST['select-all']) == 0) && ($_POST['select-child'] == 'select')) {
            ?>
            <div>
            <!-- Shows error message if no child is selected -->
                <script type="text/javascript">
                /* global $*/
                    $(function(){
                        $('#error-message').show();
                        $('#error-message').addClass("error");
                        $('#error-message').text('Please select a child before continuing.');
                    });
                </script>
            </div>
            <?php
        }
        else {
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
            
            // Select All was clicked
            if (isset($_POST['select-all'])) {
                $data .= output_all($sql_start, $sql_end);
            }
            // A separate child was clicked
            else { 
                $childID = $_POST['select-child'];
                $data .= output_child($childID, $sql_start, $sql_end);
            }
            
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
    }
?>