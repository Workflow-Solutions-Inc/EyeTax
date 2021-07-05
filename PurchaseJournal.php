<?php 
require_once('inc/sidebarexcelreports.php');
require_once('inc/header.php');
include("process/controllers/config/dbconn.php");
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="assets/img/favicon.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Purchase Journal - Excel File Generation</title>

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
            <form action="Reports/NewPurchasedJournal.php" method="get">
            <div class="container-fluid">
                <div class="row">
                      <div class="col-md-12">
                            <div class="card">
                                <div class="header">
                                    <h4 class="title">Purchase Journal Parameters</h4>
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
                                                <label for="Date"> Report Filter &nbsp</label><br>

                                               <input type="checkbox" id="Date" name="Date" value="1" checked="1">
                                                <label for="Date"> Date &nbsp</label>

                                                <input type="checkbox" id="payee" name="payee" value="1" checked="1">
                                                <label for="payee"> payee &nbsp</label>

                                               <!--  <input type="checkbox" id="tin" name="tin" value="1" checked="1">
                                                <label for="tin"> tin &nbsp</label>

                                                <input type="checkbox" id="address" name="address" value="1" checked="1">
                                                <label for="address"> Customer Name &nbsp</label> -->

                                                <input type="checkbox" id="apv" name="apv" value="1" checked="1">
                                                <label for="apv"> apv no. &nbsp</label>

                                                <input type="checkbox" id="Accounts" name="Accounts" value="1" checked="1">
                                                <label for="Accounts"> Accounts &nbsp</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <button type="submit" class="btn  btn-fill pull-right">Generate Purchase Journal</button>
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
                            <br>
                            <div class="col-md-12">
                                            <div class="form-group">
                                              <i class="pe-7s-search"></i>   <label>Search Here</label>
                                                <input type="text" class="form-control" placeholder="Search"  id = "searchboxPaid" onkeyup="searchStatusPaid()" style="width: 200px">
                                            </div>
                            </div>
                            <div class="content table-responsive table-full-width" style="height: 400px; overflow-y: scroll;">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th>IC</th>
                                        <th>Invoice Number</th>
                                        <th>Customer Name</th>
                                        <th>Date</th>
                                        <th>Due Date</th>
                                        <th>Paid</th>
                                        <th>Due</th>
                                        <th>Status</th>
                                    </thead>
                                    

                                    <tbody id="datapaid">
                                        
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>

                      <div class="col-md-12" style="display: none;">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Pending Payables</h4>
                                <p class="category">All Pending Sales Invoices cannot be viewed because of unsured status</p>
                            </div>
                            <br>
                            <div class="col-md-12">
                                            <div class="form-group">
                                              <i class="pe-7s-search"></i>   <label>Search Here</label>
                                                <input type="text" class="form-control" placeholder="Search"  id = "searchbox" onkeyup="searchStatus()" style="width: 200px">
                                            </div>
                            </div>
                             <div class="content table-responsive table-full-width" style="height: 400px; overflow-y: scroll;">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th>IC</th>
                                        <th>Invoice Date</th>
                                        <th>Invoice Number</th>
                                        <th>Customer Name</th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                    </thead>
                                    

                                    <tbody id="data">
                                        
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


<?php include("inc/footer.php"); ?>

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
	<script src="assets/js/demo.js"></script>



    <script type="text/javascript">
        $("#datatable tbody tr").click(function() {
            $("#datatable tbody tr").removeClass("rowselected")
            $(this).addClass("rowselected")
            //alert ($("#datatable tbody tr:eq("+ ($(this).index()) +") td:eq(1)").text())
            var inv = $("#datatable tbody tr:eq("+ ($(this).index()) +") td:eq(1)").text()
            var id = $("#datatable tbody tr:eq("+ ($(this).index()) +") td:eq(5)").text()
//             let params = `status=no,location=no,toolbar=no,menubar=no,
// width=1200,height=750,left=500,top=400`;
//             let newWindow = open("../ExcelReports/PurchaseLine.php?inv="+inv, 'Purchase Line', params);
//                 myWindow= window.open();

            window.open("../../itaxUsers/ExcelReports/PurchaseLine.php?inv="+inv+"&id="+id, "_blank");
        });

         $(document).ready(function(){

            //alert(1);
            //getXeroDataPaid();
            getXeroData();
            getXeroDataPaid();

        });

        function getXeroData(){

            var action = "getACCPAY";
            $.ajax({
                            type: 'GET',
                            url: 'process/controllers/getInvoices.php',
                            data:{action:action},

                            beforeSend:function(){

                            },
                            success: function(data){
                                //alert(data);
                                
                                    var container = document.getElementById('data');
                                    container.innerHTML = data;
                                
                            }

                }); 
        }

        function getXeroDataPaid(){

            var action = "getACCPAYPAID";
            $.ajax({
                            type: 'GET',
                            url: 'process/controllers/getInvoices.php',
                            data:{action:action},

                            beforeSend:function(){

                            },
                            success: function(data){
                                //alert(data);
                                
                                    var container = document.getElementById('datapaid');
                                    container.innerHTML = data;
                                
                            }

                }); 
        }

        function searchStatus()
        {
        //alert(document.querySelector('#searchBox').value.toUpperCase());
          const filter = document.querySelector('#searchbox').value.toUpperCase();
          const trs = document.querySelectorAll('#data tr');
          trs.forEach(tr => tr.style.display = [...tr.children].find(td => td.innerHTML.toUpperCase().includes(filter)) ? '' : 'none');
        }

        function searchStatusPaid()
        {
        //alert(document.querySelector('#searchBox').value.toUpperCase());
          const filter = document.querySelector('#searchboxPaid').value.toUpperCase();
          const trs = document.querySelectorAll('#datapaid tr');
          trs.forEach(tr => tr.style.display = [...tr.children].find(td => td.innerHTML.toUpperCase().includes(filter)) ? '' : 'none');
        }
    </script>

</html>
