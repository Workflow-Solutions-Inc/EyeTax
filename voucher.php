<?php
include('inc/sidebarexcelreports.php');
include('inc/header.php');
include("process/controllers/config/dbconn.php");
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="assets/img/favicon.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Voucher</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />


    <!-- Bootstrap core CSS     -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="assets/css/animate.min.css" rel="stylesheet"/>

    <!--  Light Bootstrap Table core CSS    -->
    <link href="assets/css/light-bootstrap-dashboard.css?v=1.4.0" rel="stylesheet"/>


    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="assets/css/demo.css" rel="stylesheet" />


    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />
</head>
<body>

<div class="wrapper">


    <div class="main-panel">


        
            <div class="container-fluid">
                <div class="row">
                      <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Invoice with Vouchers</h4>
                                
                                <p class="category">All customer Invoices with Voucher</p>
                            </div>

                            <div class="col-md-12">
                                            <div class="form-group">
                                              <i class="pe-7s-search"></i>   <label>Search Here</label>
                                                <input type="text" class="form-control" placeholder="Search"  id = "searchboxPaid" onkeyup="searchStatusPaid()" style="width: 200px">
                                            </div>
                            </div>

                            <div class="content table-responsive table-full-width" style="height: 500px; overflow-y: scroll;">
                               <table id="datatable" class="table table-hover table-striped">
                                     <thead>
                                        <th>IC</th>
                                        <th>Invoice Reference</th>
                                        <th>Customer Name</th>
                                        <th>Voucher</th>
                                        <th>Amount</th>
                                        <!-- <th> <button type="submit" id="viewvoucher" class="btn btn-info ">View Voucher</button></th> -->
                                        <!-- <th> <button type="submit" id="sendmail" class="btn btn-info ">Send to Email</button></th> -->
                                    </thead>
                                    <tbody id="datapaid">
                                    <?php
                                    $query = "

                                                SELECT reference as Invoiceid, ci.customerName, voucher as voucherno, format(total,2) as invoiceamount,btdate 
                                                from banktrans bt
                                                left join custinfo ci on ci.customerId = bt.contactid
                                                where voucher != '' and bt.company = '".$orgid."'

                                                union
                                                
                                                SELECT reference, 'FUND TRANSFER', voucher, format(amount,2) as total, btdate
                                                from banktransfer
                                                where voucher != '' and company = '".$orgid."'
                                                
                                                union
                                                
                                                SELECT inv.Invoiceid, ci.customerName , voucher, format(amount,2) as total, paymentDate
                                                from payments p
                                                left join invoices inv on inv.id = p.id
                                                left join custinfo ci on ci.customerId = inv.customerId
                                                where voucher != '' and p.company = '".$orgid."'

                                                order by btdate asc
                                                 ";
                                    $result = $conn->query($query);
                                    $rowclass = "rowA";
                                    $rowcnt = 0;
                                    $rowcnt2 = 0;
                                    $collection = '';
                                    while ($row = $result->fetch_assoc())
                                    { 
                                        $rowcnt++;
                                        ?>

                                            <td style="width:5%;"><?php echo $rowcnt;?></td>
                                            <td style="width:26%;"  id="inv" name="inv"><?php echo $row['Invoiceid'];?></td>
                                            <td style="width:26%;"><?php echo $row['customerName'];?></td>
                                            <td style="width:26%;"><?php echo $row['voucherno'];?></td>
                                            <td style="width:26%;"><span>&#8369;</span><?php echo " ".$row['invoiceamount'];?></td>
                                            <td style="width:0%;"></td>
                                            <td style="width:0%;"></td>
                                           
                                            
                                        </tr>
                                    <?php 
                                    }?>
                                </tbody>
                                </table>

                            </div>
                        </div>
                    </div>

                      <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Bills to Pay</h4>
                                <p class="category">All Bills to Pay with Voucher</p>
                            </div>
                             <div class="content table-responsive table-full-width" style="height: 500px; overflow-y: scroll;">
                                <table class="table table-hover table-striped">
                                   <table class="table table-hover table-striped">
                                    <thead>
                                        <th>IC</th>
                                        <th>Invoice Reference</th>
                                        <th>Voucher No</th>
                                        <th>Customer Name</th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                    </thead>
                                    <tbody>
                                        
                                    
                                    <?php
                                    $query = "SELECT Invoiceid ,ci.customerName, inv.Voucherno,
                                                    case 
                                                    when invoicestatus = 'AUTHORISED' then 'UNPAID' 
                                                    when invoicestatus = 'DRAFT' then 'DRAFT' 
                                                    else 'PAID' end as invoicestatus,
                                                     format(iv.invoiceamount,2) as 'invoiceamount'  
                                                    FROM invoices iv


                                                   left join custinfo ci on
                                                    iv.customerId = ci.customerId
                                                    
                                                    left join invoicevoucher inv on
                                                    inv.id = iv.id

                                                    where iv.invoicetype = 'ACCPAY' and iv.company = '".$orgid."'

                                                    group by Invoiceid,ci.customerName,invoicestatus,iv.invoiceamount
                                                    
                                                    order by Voucherno

                                                 ";
                                    $result = $conn->query($query);
                                    $rowclass = "rowA";
                                    $rowcnt = 1;
                                    $rowcnt2 = 0;
                                    $collection = '';
                                    while ($row = $result->fetch_assoc())
                                    { 
                                        ?>

                                            <td style="width:5%;"><?php echo $rowcnt++;?></td>
                                            <td style="width:20%;"><?php echo $row['Invoiceid'];?></td>
                                            <td style="width:20%;"><?php echo $row['Voucherno'];?></td>
                                            <td style="width:20%;"><?php echo $row['customerName'];?></td>
                                            <td style="width:20%;"><?php echo $row['invoicestatus'];?></td>
                                            <td style="width:20%;"><span>&#8369;</span><?php echo " ".$row['invoiceamount'];?></td>
                                            
                                        </tr>
                                    <?php 
                                    }?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
<?php include("inc/footer.php"); ?>

</body>

    <!--   Core JS Files   -->
    <script src="assets/js/jquery.3.2.1.min.js" type="text/javascript"></script>
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>

	<!--  Charts Plugin -->
	<script src="assets/js/chartist.min.js"></script>

    <!--  Notifications Plugin    -->
    <script src="assets/js/bootstrap-notify.js"></script>

    <!--  Google Maps Plugin    -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>

    <!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
	<script src="assets/js/light-bootstrap-dashboard.js?v=1.4.0"></script>

   <script src="assets/js/demo.js"></script>

   <script type="text/javascript">
       function searchStatusPaid()
        {
        //alert(document.querySelector('#searchBox').value.toUpperCase());
          const filter = document.querySelector('#searchboxPaid').value.toUpperCase();
          const trs = document.querySelectorAll('#datapaid tr');
          trs.forEach(tr => tr.style.display = [...tr.children].find(td => td.innerHTML.toUpperCase().includes(filter)) ? '' : 'none');
        }
   </script>
  
</html>
