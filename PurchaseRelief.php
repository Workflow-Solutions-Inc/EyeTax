<?php
require_once('inc/sidebardat.php');
require_once('inc/header.php');
include("process/controllers/config/dbconn.php");
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="assets/img/favicon.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Purchase Relief - DAT File Generation</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />


    <!-- Bootstrap core CSS     -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="assets/css/animate.min.css" rel="stylesheet"/>

    <!--  Light Bootstrap Table core CSS    -->
    <link href="assets/css/light-bootstrap-dashboard.css?v=1.4.0" rel="stylesheet"/>
        <link href="assets/css/demo.css" rel="stylesheet" />

    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />

</head>
<body>

<div class="wrapper">


       <div class="main-panel">

 
        <div class="content">
            <form action="Reports/NEWPurchaseRelief.php" method="get">
            <div class="container-fluid">
                <div class="row">
                      <div class="col-md-12">
                            <div class="card">
                                <div class="header">
                                    <h4 class="title">Purchase Relief Parameters</h4>
                                    <p class="category">Note: Please select a specific date before clicking the Generate button.</p>
                                </div>
                                <div class="content">
                               

                                 <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                              <i class="pe-7s-date"></i>   <label>From Date</label>
                                                <input type="date" name="frmdate" id="frmdate" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                             <i class="pe-7s-date"></i>   <label>To Date</label>
                                                <input type="date" name="todate" id="todate" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                               
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <button type="submit" class="btn  btn-fill pull-right">Generate Purchase Relief</button>
                                            </div>
                                        </div>

                                    </div>
                                
                             </div>     
                            </div>
                        </div>
                </div>
            </form>
        </div>
         


        
            <div class="container-fluid">
                <div class="row">
                      <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Paid Invoices (Payables)</h4>
                                
                                <p class="category">All Paid Invoices</p>
                            </div>

                            <div class="content table-responsive table-full-width">
                               <table id="datatable" class="table table-hover table-striped">

                                    <thead>
                                        <th>IC</th>
                                        <th>Invoice Number</th>
                                        <th>Customer Name</th>
                                        <th>Tax Amount</th>
                                        <th>Amount</th>
                                    </thead>
                                    <?php
                                    $query = "SELECT @rownum := @rownum + 1 AS num,iv.Invoiceid ,ci.customerName,format(sum(it.taxamount),2) as 'taxamount' , format(sum(iv.invoiceamount),2) as 'invoiceamount'  
                                                FROM invoices iv

                                              
                                                left join custinfo ci on
                                                iv.customerId = ci.customerId
                                                left join invoicetrans it on
                                                iv.Invoiceid = it.invoiceid
                                                ,(SELECT @rownum := 0) r

                                                where iv.invoicetype = 'ACCPAY' and iv.invoicestatus = 'PAID'
                                                group by iv.Invoiceid ,ci.customerName

                                                order by  @rownum := @rownum + 1  asc
                                                 ";
                                    $result = $conn->query($query);
                                    $rowclass = "rowA";
                                    $rowcnt = 0;
                                    $rowcnt2 = 0;
                                    $collection = '';
                                    while ($row = $result->fetch_assoc())
                                    { 
                                        ?>

                                            <td style="width:5%;"><?php echo $row['num'];?></td>
                                            <td style="width:22%;"><?php echo $row['Invoiceid'];?></td>
                                            <td style="width:22%;"><?php echo $row['customerName'];?></td>
                                            <td style="width:22%;"><span>&#8369;</span><?php echo " ".$row['taxamount'];?></td>
                                            <td style="width:22%;"><span>&#8369;</span><?php echo " ".$row['invoiceamount'];?></td>
                                            <td style="width:22%;">
                                                 <div class="form-group">
                                                    <button type="submit" class="btn btn-info ">View Details</button>
                                                </div>
                                            </td>
                                            
                                        </tr>
                                    <?php 
                                    }?>
                                </table>

                            </div>
                        </div>
                    </div>

                      <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Pending Payables</h4>
                                <p class="category">All Pending Payables Invoices cannot be viewed because of unsured status</p>
                            </div>
                             <div class="content table-responsive table-full-width">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th>IC</th>
                                        <th>Invoice Number</th>
                                        <th>Customer Name</th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                    </thead>
                                    <?php
                                    $query = "SELECT @rownum := @rownum + 1 AS num,Invoiceid ,ci.customerName,
                                                    case when invoicestatus = 'AUTHORISED' 
                                                            then 'UNPAID' else 'DRAFT' end as invoicestatus,
                                                     format(iv.invoiceamount,2) as 'invoiceamount'  
                                                    FROM invoices iv


                                                    left join custinfo ci on
                                                    iv.customerId = ci.customerId
                                                    ,(SELECT @rownum := 0) r

                                                    where iv.invoicetype = 'ACCPAY' and invoicestatus != 'PAID' 

                                                    group by Invoiceid,ci.customerName

                                                    order by  @rownum := @rownum + 1 

                                                 ";
                                    $result = $conn->query($query);
                                    $rowclass = "rowA";
                                    $rowcnt = 0;
                                    $rowcnt2 = 0;
                                    $collection = '';
                                    while ($row = $result->fetch_assoc())
                                    { 
                                        ?>

                                            <td style="width:5%;"><?php echo $row['num'];?></td>
                                            <td style="width:22%;"><?php echo $row['Invoiceid'];?></td>
                                            <td style="width:22%;"><?php echo $row['customerName'];?></td>
                                            <td style="width:22%;"><?php echo $row['invoicestatus'];?></td>
                                            <td style="width:22%;"><span>&#8369;</span><?php echo " ".$row['invoiceamount'];?></td>
                                            
                                        </tr>
                                    <?php 
                                    }?>
                                </table>

                            </div>
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

	<!-- Light Bootstrap Table DEMO methods, don't include it in your project! -->
	<script src="assets/js/demo.js"></script>
     <script type="text/javascript">
        $(document).ready(function(){

            demo.initChartist();

            $.notify({
                icon: 'pe-7s-cash',
                message: "Purchase Relief Report Selected."

            },{
                type: 'info',
                timer: 4000
            });

        });

    </script>


    <script type="text/javascript">
        $("#datatable tbody tr").click(function() {
            $("#datatable tbody tr").removeClass("rowselected")
            $(this).addClass("rowselected")
            //alert ($("#datatable tbody tr:eq("+ ($(this).index()) +") td:eq(1)").text())
            var inv = $("#datatable tbody tr:eq("+ ($(this).index()) +") td:eq(1)").text()
            let params = `status=no,location=no,toolbar=no,menubar=no,
width=1200,height=750,left=500,top=400`;
            let newWindow = open("../ExcelReports/PurchaseLine.php?inv="+inv, 'Purchase Line', params);
                myWindow= window.open();
        });
    </script>

</html>
