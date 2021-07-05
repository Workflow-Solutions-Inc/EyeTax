<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="assets/img/favicon.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Purchase Line</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />


    <!-- Bootstrap core CSS     -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="assets/css/animate.min.css" rel="stylesheet"/>

    <!--  Light Bootstrap Table core CSS    -->
    <link href="assets/css/light-bootstrap-dashboard.css?v=1.4.0" rel="stylesheet"/>

    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />

</head>
<body>

<div class="wrapper">
    <div class="sidebar" data-color="gray" data-image="assets/img/sidebar-4.jpg">

    <!--

        Tip 1: you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple"
        Tip 2: you can also add an image using data-image tag

    -->

    	<div class="sidebar-wrapper">
            <div class="logo">
                <label class="simple-text">Purchase Transaction</label>
            </div>

    	</div>
    </div>

       <div class="main-panel">
        <nav class="navbar navbar-default navbar-fixed">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">Purchase Journal Line</a>
                </div>
               
            </div>
        </nav>
 
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                      <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Invoice Reference:  <?php $invno = $_GET["inv"]; echo $invno;?> |  Status: PAID </h4>
                                <p class="category">Item lines</p>
                            </div>
                             <div class="content table-responsive table-full-width">
                                <table id="datatable" class="table table-hover table-striped">
                                    <thead>
                                        <th>DATE</th>
                                        <th>PAYEE</th>
                                        <th>TIN</th>
                                        <th>ADDRESS</th>
                                        <th>APV</th>
                                        <th>ACCOUNT TITLE</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                    </thead>
                                    <?php
                                    include("process/controllers/config/dbconn.php");
                                    $invno = $_GET["inv"];
                                    $id = $_GET["id"];
                                   /* $query = "SELECT 
                                                  date(iv.invoicedate) as 'invdate',
                                                  concat(ifnull(ci.customerName,''),' - ',concat(ifnull(ci.addressline1,''),
                                                  ifnull(ci.addressline2,''),ifnull(ci.addressline3,''))) as 'cust',
                                                  iv.Invoiceid,
                                                  ifnull(invoicereference,'') as invoicereference,
                                                  'VAT',
                                                  case when it.taxtype != 'NONE' then format(sum(it.taxamount),2) else '0.00' end as '12VAT',
                                                  case when it.taxtype = 'NONE' then format(sum(it.taxamount),2) else '0.00' end as 'VATEX',
                                                  format(sum(iv.Invoiceamount),2) as 'invamount',
                                                  format(iv.paidamount,2) as 'paidamount',
                                                  format(iv.Invoiceamount-iv.paidamount,2) as 'account'
                                                  FROM invoices iv
                                                  left join invoicetrans it on
                                                  iv.Invoiceid = it.invoiceid
                                                  left join custinfo ci on
                                                  iv.customerId = ci.customerId
                                                  left join accounts ac on
                                                  it.accountcode = ac.accountcode
                                                  where iv.invoicetype = 'ACCREC' and iv.invoiceid = '".$invno."'
                                                  group by iv.invoicedate,invoicereference,iv.Invoiceid";*/
                                    $query = "call get_purchasedjournal('".$id."');";
                                    $result = $conn->query($query);
                                    while ($row = $result->fetch_assoc())
                                    {  $invoicedate = $row["invoicedate"];
                                        $CustomerName = $row["CustomerName"];
                                        $address = $row["address"];
                                        $Voucherno = $row["Voucherno"];
                                        $tin = $row["tin"];
                                        $acctitle = $row["acctitle"];
                                        $debit = $row["debit"];
                                        $credit = $row["credit"];
                                       
                                            ?>
                                                <tr>  
                                                   <td><?php echo $invoicedate;?></td>   
                                                   <td><?php echo $CustomerName;?></td>  
                                                   <td><?php echo $tin;?></td>  
                                                   <td><?php echo $address;?></td>  
                                                   <td><?php echo $Voucherno;?></td>  
                                                   <td><?php echo $acctitle;?></td>  
                                                   <td><?php echo number_format($debit,2);?></td>  
                                                   <td><?php echo number_format($credit,2);?></td>  
                                                </tr>
                                                
                                            <?php 
                                      
                                    }?>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>



        <footer class="footer">
            <div class="container-fluid">
                <nav class="pull-left">
                    <ul>
                        <li>
                            <a href="#">
                              
                            </a>
                        </li>

                    </ul>
                </nav>
                <p class="copyright pull-right">
                    &copy; <script>document.write(new Date().getFullYear())</script>
                </p>
            </div>
        </footer>

    </div>
</div>


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
</html>
