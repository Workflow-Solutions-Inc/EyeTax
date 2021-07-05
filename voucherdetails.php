
<?php
require('fpdf/fpdf.php');
include("process/controllers/config/dbconn.php");
session_start();
$orgid = "";
if(isset($_SESSION["organisationID"])){
    $orgid = $_SESSION["organisationID"];
}
$pdf = new FPDF();
$pdf = new FPDF('P','mm','A4');
$pdf->AddPage();


$voucherNo_ = $_GET["vnno"];

$query_ = "SELECT
						 iv.Invoiceid,
                         ci.customerName,
                         date(iv.invoicedate) as 'invdate'
						FROM invoices iv

						left join invoicevoucher ivs on
						ivs.Invoiceno = iv.id

						left join custinfo ci on
						iv.customerId = ci.customerId

						left join invoicetrans it on
						iv.id = it.invoiceid
						,(SELECT @rownum := 0) r
		where iv.invoicetype = 'ACCPAY' 
		and iv.invoicestatus = 'PAID'  
		and ivs.voucherno = '".$voucherNo_."' and iv.company = '".$orgid."'
		group by iv.Invoiceid,ci.customerName

		union 

		select reference, ci.customerName, bt.btdate
		from banktrans bt
		left join custinfo ci on ci.customerId = bt.contactid 
		where voucher = '".$voucherNo_."' and bt.company = '".$orgid."'

		union
        
        select p.invoiceno, ci.customerName, date(p.paymentDate) 
        from payments p
        left join invoices inv on inv.Invoiceid = p.invoiceno
        left join custinfo ci on ci.customerId = inv.customerId
        where p.voucher = '".$voucherNo_."' and p.company = '".$orgid."'
		";
$result_ = $conn->query($query_);
$row_ = $result_->fetch_assoc();
$invid = $row_["Invoiceid"];
$custname = $row_["customerName"];
$invdate = $row_["invdate"];

$pdf->SetFont('Arial','B',20);
$pdf->Cell(10,10,'');
$pdf->Cell(10,10,'');
$pdf->Cell(40,10,'');
$pdf->Cell(10,10,'Invoice Voucher');
$pdf->Cell(60,10,'',0,1,'C');
$pdf->Cell(60,10,'',0,1,'C');


$pdf->SetTextColor(10, 9, 9);
$pdf->SetFont('Arial','',15);
$pdf->Cell(40,10,'Invoice Date:');
$pdf->SetFont('Arial','',13);
$pdf->Cell(40,10,$invdate,0);
$pdf->Cell(40,10,'',0);
$pdf->Cell(40,10,'',0);
$pdf->Cell(40,10,'',0,1);

$pdf->SetFont('Arial','',15);
$pdf->Cell(40,10,'Invoice Ref.:');
$pdf->SetFont('Arial','B',15);
$pdf->Cell(40,10,$invid,0);
$pdf->Cell(15,5,'',0);
$pdf->Cell(10,5,'',0);



$pdf->SetFont('Arial','B',30);
$pdf->SetTextColor(255, 3, 3);
$pdf->Cell(20,5,$voucherNo_,0);


$pdf->SetFont('Arial','',15);
$pdf->SetTextColor(10, 9, 9);
$pdf->Cell(15,5,'',0);
$pdf->Cell(10,5,'',0);
$pdf->Cell(40,10,'',0,1);
$pdf->Cell(40,10,'Cust. Name:');
$pdf->SetFont('Arial','',13);
$pdf->Cell(40,10,$custname,0);

$pdf->Cell(40,10,'',0,1);
$pdf->Cell(40,10,'',0,1);
$pdf->SetTextColor(10, 9, 9);
$pdf->SetFont('Arial','B',15);
$pdf->Cell(40,10,'_________________________________________________________________');

$ttaxamount = 0;
$tunamount = 0;
$tamountd = 0;
$tamount = 0;





        
        $query_ = "SELECT
						 format(iv.invoiceamount - it.taxamount,2) as 'ex',
						 format(iv.invoiceamount,2) as 'inv',
						 format(it.taxamount,2) as 'tax'
						FROM invoices iv

						left join invoicevoucher ivs on
						ivs.Invoiceno = iv.id

						left join custinfo ci on
						iv.customerId = ci.customerId

						left join invoicetrans it on
						iv.id = it.invoiceid

						where iv.invoicetype = 'ACCPAY' 
						and iv.invoicestatus = 'PAID'  
						and ivs.voucherno = '".$voucherNo_."' and iv.company = '".$orgid."'
						union

						select 
						format(total - totaltax,2), format(total,2), format(totaltax,2) 
						from banktrans 
						where voucher = '".$voucherNo_."' and company = '".$orgid."'

						union
                        select '0.00', format(amount,2), '0.00'
                        from payments
                        where voucher = '".$voucherNo_."' and company = '".$orgid."'

						";
        $result_ = $conn->query($query_);
        $row_ = $result_->fetch_assoc();
        $ttaxamount = $row_["tax"];
		$tunamount = $row_["ex"];
		$tamount = $row_["inv"];
              

$pdf->SetFont('Arial','B',30);
$pdf->Cell(15,5,'',0,1);
$pdf->Cell(15,5,'',0,1);
$pdf->Cell(15,5,'',0,1);
$pdf->Cell(15,5,'',0,1);
$pdf->Cell(10,5,'',0);


$pdf->SetFont('Arial','',12);
$pdf->Cell(40,5,'',0);
$pdf->Cell(20,5,'',0);
$pdf->Cell(40,5,'',0);
$pdf->Cell(60,5,'Tax Exempt Amount....',0);
$pdf->SetFont('Arial','',12);
$pdf->SetTextColor(10, 9, 9);
$pdf->Cell(20,5,$tunamount,0,0,'R');

$pdf->SetFont('Arial','B',30);
$pdf->Cell(15,5,'',0,1);

$pdf->Cell(15,5,'',0,1);
$pdf->Cell(10,5,'',0);

$pdf->SetFont('Arial','',12);
$pdf->Cell(40,5,'',0);
$pdf->Cell(20,5,'',0);
$pdf->Cell(40,5,'',0);
$pdf->SetTextColor(10, 9, 9);
$pdf->Cell(60,5,'Total VAT..........',0);
$pdf->SetFont('Arial','',12);
$pdf->SetTextColor(10, 9, 9);
$pdf->Cell(20,5,$ttaxamount,0,0,'R');


$pdf->SetFont('Arial','B',30);
$pdf->Cell(15,5,'',0,1);

$pdf->SetFont('Arial','',12);
$pdf->Cell(70,5,'',0);
$pdf->Cell(80,5,'',0);
$pdf->Cell(60,5,'__________________',0);
$pdf->Cell(10,5,'',0,1);
$pdf->Cell(15,5,'',0,1);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(40,5,'',0);
$pdf->Cell(30,5,'',0);
$pdf->Cell(40,5,'',0);
$pdf->SetTextColor(10, 9, 9);
$pdf->Cell(60,5,'Total Amount.....',0);
$pdf->SetFont('Arial','B',14);
$pdf->SetTextColor(255, 3, 3);
$pdf->Cell(20,5,$tamount,0,0,'R');


$pdf->SetFont('Arial','B',12);
$pdf->Cell(15,5,'',0,1);
$pdf->Cell(15,5,'',0,1);
$pdf->Cell(15,5,'',0,1);
$pdf->Cell(15,5,'',0,1);
$pdf->SetTextColor(120, 119, 119);
$pdf->Cell(15,5,'',0,1);
$pdf->Cell(15,5,'',0,1);
$pdf->Cell(60,5,'',0);
$pdf->Cell(60,5,'This is a system generated Report.',0,0,'C');


$pdf->Output('',''.$voucherNo_.'.pdf', false);
?>

