<?php  
require_once "PHPExcel/Classes/PHPExcel.php";
session_start();
	$orgid = "";
if(isset($_SESSION["organisationID"])){
    $orgid = $_SESSION["organisationID"];
}

//database
include("../process/controllers/config/dbconn.php");

//create phpexcel object
$excel = new PHPExcel();

$id = '';
$start = 16;
$count = 1;
$year = $_GET["monthyear"];
//selecting active sheet
//$excel -> setActiveSheetIndex(0);
//PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
$excel = PHPExcel_IOFactory::load("Schedule4.xlsx");

		$orgquery = "select * from organisation where id = '$orgid';";
	$res = $conn->query($orgquery);
	while ($orgrow = $res->fetch_assoc()) 
    { 
    	$excel -> setActiveSheetIndex(0)
		-> setCellValue('B6', $orgrow["tin"])
		-> setCellValue('C7', $orgrow["name"]);
    }


    	$query ="select ci.taxtype, ci.customerName, 
				CONCAT(IFNULL(ci.firstname, ''),' ',IFNULL(ci.middlename, ''),' ',IFNULL(ci.lastname, '')) AS 'name' ,
                it.itemcode,
				it.quantity,
                it.unitamount*-1 as unitamount,
                it.lineamount*-1 as lineamount
				from 
                invoicetrans it
                left join
                invoices iv on
				it.invoiceid = iv.id
				left join custinfo ci on
				iv.customerId = ci.customerId
				where it.lineamount<0 and year(iv.invoicedate) = '$year' and iv.invoicetype = 'ACCPAY'
				order by iv.invoicedate asc";
	    $result = $conn->query($query);
	    while ($row = $result->fetch_assoc()) 
	    { 
	    	$excel -> setActiveSheetIndex(0)
			-> setCellValue('A'.$start, ' '.$count)
			-> setCellValue('B'.$start, $row["taxtype"])
			-> setCellValue('C'.$start, $row["customerName"])
			-> setCellValue('D'.$start, $row["name"])
			-> setCellValue('D'.$start, $row["itemcode"])
			-> setCellValue('E'.$start, $row["quantity"])
			-> setCellValue('G'.$start, $row["unitamount"])
			-> setCellValue('H'.$start, $row["lineamount"]);


			$start+=1;
			$count+=1;
	    }

//redirect browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="QAP BIR FORM 1604E - SCHEDULE 4 '.date('Y-m-d h:i:sa').'.xlsx"');
header('Cache-Control: max-age=0');

//write result to a file
 $file = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
 $file->save('php://output');

?>