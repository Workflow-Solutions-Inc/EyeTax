<?php 
  $output = '<label><b>Company Name: '.$orgname.' </b></label><br>
    <label><b>Cash Disbursement Books</label></b><br>
    <label><b>As of: '.$frmdate.' to '.$todate.'</b></label><br><br>
    <table border = "1"> 
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th colspan = "3">DEBIT</th>
      <th colspan = "5">CREDIT</th>

    </table>
    <table border = "1"> 
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th colspan = "2">CASH IN BANK</th>
      <th></th>
      <th></th>
      <th colspan = "2">SUNDRY</th>
      <th></th>
      <th></th>


    </table>
    <table border = "1"> 
      <th>DATE</th>
      <th>CUSTOMER NAME</th>
      <th>CHECK NO.</th>
      <th>VOUCHER REFERENCE</th>
      <th>APV</th>
      <th>TIN</th>
      <th>ADDRESS</th>
      <th>ACCOUNT</th>
      <th>AMOUNT</th>
      <th>EXPANDED WITHHOLDING TAX</th>
      <th>ACCOUNTS PAYABLE</th>
      <th>ACCOUNT</th>
      <th>AMOUNT</th>
      <th>PARTICULARS</th>
      <th>INPUT VAT</th>


    </table>';


    header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment; filename=jonaldarjon.xls');
      echo $output ;
 ?>
