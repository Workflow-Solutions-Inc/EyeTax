  <?php  
  session_start();

include("../config/dbconn.php");
  $frmdate = htmlentities($_GET['frmdate']);
  $todate = htmlentities($_GET['todate']);
  $orgid = "";
  if(isset($_SESSION["organisationID"])){
      $orgid = $_SESSION["organisationID"];
      $orgname = $_SESSION["organisationName"];
  }
  $Date = "";
  $payee = "";
  $tin = "";
  $address = "";
  $checkvouch = "";
  $check = "";
  $particular = "";
  $accpay = "";
  $cash = "";
  $sundry = "";

  $frmdate = htmlentities($_GET['frmdate']);
  $todate = htmlentities($_GET['todate']);


  $newFromyr = substr($frmdate,0,-6);
  $newFrommonth = substr($frmdate,5,-3);
  $newFromday = substr($frmdate,8);

  $newToyr = substr($todate,0,-6);
  $newTomonth = substr($todate,5,-3);
  $newToday = substr($todate,8);

  if(isset($_GET['Date'])){
    $Date = $_GET['Date'];
  }else{
    $Date = 0;
  }

  if(isset($_GET['payee'])){
    $payee = $_GET['payee'];
  }else{
    $payee = 0;
  }

  if(isset($_GET['checkno'])){
    $checkno = $_GET['checkno'];
  }else{
    $checkno = 0;
  }

  if(isset($_GET['voucherref'])){
    $voucherref = $_GET['voucherref'];
  }else{
    $voucherref = 0;
  }

  if(isset($_GET['apv'])){
    $apv = $_GET['apv'];
  }else{
    $apv = 0;
  }

  if(isset($_GET['tin'])){
    $tin = $_GET['tin'];
  }else{
    $tin = 0;
  }

  if(isset($_GET['address'])){
    $address = $_GET['address'];
  }else{
    $address = 0;
  }

  if(isset($_GET['credit'])){
    $credit = $_GET['credit'];
  }else{
    $credit = 0;
  }

  if(isset($_GET['debit'])){
    $debit = $_GET['debit'];
  }else{
    $debit = 0;
  }
  $header1 = "";
  $header2 = "";
  $header3 = "";
  if($Date == 1){
    $header1 .= '<th></th>';
    $header2 .= '<th></th>';
    $header3 .= '  <th>DATE</th>  ';
  }
  if($payee == 1){
    $header1 .= '<th></th>';
    $header2 .= '<th></th>';
    $header3 .= '  <th>PAYEE</th>  ';
  } 
  if($checkno == 1){
    $header1 .= '<th></th>';
    $header2 .= '<th></th>';
    $header3 .= '  <th>OR No.</th>  ';
  } 
  if($voucherref == 1){
    $header1 .= '<th></th>';
    $header2 .= '<th></th>';
    $header3 .= '  <th>VOUCHER REFERENCE</th>';
  } 
  if($apv == 1){
    $header1 .= '<th></th>';
    $header2 .= '<th></th>';
    $header3 .= '  <th>APV</th>  ';
  } 
  if($tin == 1){
    $header1 .= '<th></th>';
    $header2 .= '<th></th>';
    $header3 .= '  <th>TIN</th>  ';
  } 
  if($address == 1){
    $header1 .= '<th></th>';
    $header2 .= '<th></th>';
    $header3 .= '  <th>ADDRESS</th>  ';
  } 
  if($credit == 1){
    $header1 .= '<th colspan = "3">DEBIT</th>  ';
    $header2 .= '<th colspan = "2">CASH IN BANK</th>
      <th></th>';
    $header3 .= '<th>ACCOUNT</th>
      <th>AMOUNT</th>
      <th>WITHHOLDING TAX</th>';
  }
  if($debit == 1){
    $header1 .= '<th colspan = "5">CREDIT</th>';
    $header2 .= '<th></th>
      <th colspan = "2">SUNDRY</th>
      <th></th>
      <th></th>';
    $header3 .= '<th>ACCOUNTS RECEIVABLE</th>
      <th>ACCOUNT</th>
      <th>AMOUNT</th>
      <th>PARTICULARS</th>
      <th>INPUT VAT</th>';
  }


  $output = '';
  $output = '<label><b>Company Name: '.$orgname.' </b></label><br>
    <label><b>Cash Receipts Books</label></b><br>
    <label><b>As of: '.$frmdate.' to '.$todate.'</b></label><br><br>
    <table border = "1"> 
      
    '.$header1.'
    </table>
    <table border = "1">   
    '.$header2.'

    </table>
    <table border = "1"> 
    '.$header3.'

    </table>';

  $query = "call get_Receipts('".$frmdate."','".$todate."');"; 
        $result = $conn->query($query);
        if ($result->num_rows > 0)
        {
          $output.='<table border = "1">';
           while ($row = $result->fetch_assoc()) 
            {
            
              $output.='<tr>';
              if($Date == 1){

                $output .= '  <td>'.date_format(date_create($row["invoicedate"]),"Y/m/d").'</td>';
              }
              if($payee == 1){
                $output .= '  <td>'.$row["customerName"].'</td>  ';
              } 
              /*if($checkno == 1){
                
                $output .= '  <td>'.$row["checkno"].'</td>  ';
              }*/
              if($checkno == 1){
                $newcheckno = "";
                if(strpos($row["checkno"], '|') !== false){
                  $explodestring = explode('|', str_replace(' ', '', $row["checkno"]));
                  $newcheckno = $explodestring[0];
                }else{
                  $newcheckno = $row["checkno"];
                }
                $output .= '  <td>'.$newcheckno.'</td>  ';
              } 
              if($voucherref == 1){
                $output .= '  <td>'.$row["voucher"].'</td>';
              } 
              if($apv == 1){
                $output .= '  <td>'.$row["apv"].'</td>  ';
              } 
              if($tin == 1){
                $output .= '  <td>'.$row["tin"].'</td>  ';
              } 
              if($address == 1){
                $output .= '  <td>'.$row["address"].'</td>  ';
              } 
              if($credit == 1){
                $output .= '<td>'.$row["bankaccount"].'</td>
                  <td>'.number_format($row["creditamount"],2).'</td>
                  <td>'.number_format($row["expandedwithholding"],2).'</td>';
              }
              if($debit == 1){
                $output .= '<td>'.number_format($row["accountspayable"],2).'</td>
                  <td>'.$row["sundryaccount"].'</td>
                  <td>'.number_format($row["sundryamount"],2).'</td>
                  <td>'.$row["particulars"].'</td>
                  <td>'.number_format($row["inputvat"],2).'</td>';

              }
              $output.='</tr>';
              
              
            }
             $output.='</table>';
        }
           
          
           mysqli_close($conn);
           include("../config/dbconn.php");
   


// RECEIVE MONEY
    /*$output.='<table border = "1">';
  $query2 = "call get_Receipts('".$frmdate."','".$todate."');"; 
        $result2 = $conn->query($query2);
          while ($row2 = $result2->fetch_assoc()) 
          {
            $output.='<tr>';
            if($Date == 1){
              $output .= '  <td>'.$row2["invoicedate"].'</td>';
            }
            if($payee == 1){
              $output .= '  <td>'.$row2["customerName"].'</td>  ';
            } 
            if($checkno == 1){
              
              $output .= '  <td>'.$row2["checkno"].'</td>  ';
            } 
            if($voucherref == 1){
              $output .= '  <td>'.$row2["voucher"].'</td>';
            } 
            if($apv == 1){
              $output .= '  <td>'.$row2["apv"].'</td>  ';
            } 
            if($tin == 1){
              $output .= '  <td>'.$row2["tin"].'</td>  ';
            } 
            if($address == 1){
              $output .= '  <td>'.$row2["address"].'</td>  ';
            } 
            if($credit == 1){
              $output .= '<td>'.$row2["bankaccount"].'</td>
                <td>'.$row2["creditamount"].'</td>
                <td>'.$row2["expandedwithholding"].'</td>';
            }
            if($debit == 1){
              $output .= '<td>'.$row2["accountspayable"].'</td>
                <td>'.$row2["sundryaccount"].'</td>
                <td>'.$row2["sundryamount"].'</td>
                <td>'.$row2["particulars"].'</td>
                <td>'.$row2["inputvat"].'</td>';
            }
            $output.='</tr>';
          }
           mysqli_close($conn);
           include("../config/dbconn.php");
    $output.='</table>';
*/
      

  $reportTitle = "".$frmdate."-".$todate."";    
  $output .= '</table>';
  header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment; filename='.$orgname.'_Disbursement_'.$reportTitle.'.xls');
      echo $output ;




  ?>