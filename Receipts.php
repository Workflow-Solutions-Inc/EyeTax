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

    <title>Receipts - Excel File Generation</title>

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
            <form action="process/controllers/Reports/Receipts.php" method="get">
            <div class="container-fluid">
                <div class="row">
                      <div class="col-md-12">
                            <div class="card">
                                <div class="header">
                                    <h4 class="title">Receipts Parameters</h4>
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
                                            <!-- <div class="form-group">
                                                <label for="Date"> Report Filter &nbsp</label><br>

                                                <input type="checkbox" id="Date" name="Date" value="1" checked="1">
                                                <label for="Date"> Date &nbsp</label>

                                                <input type="checkbox" id="customerName" name="customerName" value="1" checked="1">
                                                <label for="customerName"> Customer Name &nbsp</label>

                                                <input type="checkbox" id="Explanation" name="Explanation" value="1" checked="1">
                                                <label for="Explanation"> Explanation &nbsp</label>

                                                <input type="checkbox" id="tin" name="tin" value="1" checked="1">
                                                <label for="tin"> Tin &nbsp</label>

                                                <input type="checkbox" id="Address" name="Address" value="1" checked="1">
                                                <label for="Address"> Address &nbsp</label>

                                                <input type="checkbox" id="debit" name="debit" value="1" checked="1">
                                                <label for="debit"> Debit &nbsp</label>

                                                <input type="checkbox" id="credit" name="credit" value="1" checked="1">
                                                <label for="credit"> Credit &nbsp</label><br>

                                                <input type="checkbox" id="sundry" name="sundry" value="1" checked="1">
                                                <label for="sundry"> sundry &nbsp</label>
                                               
                                            </div> -->
                                            <div class="form-group">
                                                <label for="Date"> Report Filter &nbsp</label><br>

                                               <input type="checkbox" id="Date" name="Date" value="1" checked="1">
                                                <label for="Date"> Date &nbsp</label>

                                                <input type="checkbox" id="payee" name="payee" value="1" checked="1">
                                                <label for="payee"> payee &nbsp</label>

                                                <input type="checkbox" id="checkno" name="checkno" value="1" checked="1">
                                                <label for="checkno"> check no &nbsp</label>

                                                <input type="checkbox" id="voucherref" name="voucherref" value="1" checked="1">
                                                <label for="voucherref"> voucher reference &nbsp</label>

                                                <input type="checkbox" id="checkvouch" name="checkvouch" value="1" checked="1">
                                                <label for="checkvouch"> check voucher no. &nbsp</label>

                                                <input type="checkbox" id="apv" name="apv" value="1" checked="1">
                                                <label for="apv"> APV &nbsp</label>

                                                <input type="checkbox" id="tin" name="tin" value="1" checked="1">
                                                <label for="tin"> TIN &nbsp</label><br>

                                                <input type="checkbox" id="address" name="address" value="1" checked="1">
                                                <label for="address"> address &nbsp</label>

                                                <input type="checkbox" id="credit" name="credit" value="1" checked="1">
                                                <label for="credit"> debit &nbsp</label>

                                                <input type="checkbox" id="debit" name="debit" value="1" checked="1">
                                                <label for="debit"> credit &nbsp</label>
                                            </div>
                                        </div>
                                       <!-- <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="pull-right"> SAVE AS: </label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <select class="btn pull-right">
                                                    <option>PDF</option>
                                                    <option>CSV</option>
                                                </select>
                                            </div>
                                        </div> -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <button type="submit" class="btn  btn-fill pull-right">Generate Receipts</button>
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
                                <h4 class="title">Cash Receipts Book</h4>
                                
                                <p class="category">All Receivables</p>
                            </div>
                            <br>
                            <div class="col-md-12">
                                            <div class="form-group">
                                              <i class="pe-7s-search"></i>   <label>Search Here</label>
                                                <input type="text" class="form-control" placeholder="Search"  id = "searchboxPaid" onkeyup="searchStatusPaid()" style="width: 200px">
                                            </div>
                            </div>

                            <div class="container-fluid table-responsive table-full-width" style="height: 400px; overflow-y: scroll;">
                               <table id="datatable" class="table table-hover table-striped">
                                    <thead>
                                        <th>IC</th>
                                        <th>Invoice Date</th>
                                        <th>Invoice Reference</th>
                                        <th>Description</th>
                                        <th>Customer Name</th>
                                        <th>Tax Amount</th>
                                        <th>Amount</th>
                                    </thead>
                                    <tbody id="datapaid">
                                        
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

    <!-- Light Bootstrap Table DEMO methods, don't include it in your project! -->
    <script src="assets/js/demo.js"></script>




    <script type="text/javascript">
        $("#datatable tbody tr").click(function() {
            $("#datatable tbody tr").removeClass("rowselected")
            $(this).addClass("rowselected")
            //alert ($("#datatable tbody tr:eq("+ ($(this).index()) +") td:eq(1)").text())
            var inv = $("#datatable tbody tr:eq("+ ($(this).index()) +") td:eq(1)").text()
            let params = `status=no,location=no,toolbar=no,menubar=no,
width=1200,height=750,left=500,top=400`;
            let newWindow = open("../ExcelReports/Salesline.php?inv="+inv, 'Invoice N - ' + inv, params);
                myWindow= window.open();
        });


        $(document).ready(function(){

            //alert(1);
            getXeroDataReceive();

        });

        

        function getXeroDataReceive(){

            var action = "getReceive";
            $.ajax({
                            type: 'GET',
                            url: 'process/controllers/getBanktransactions.php',
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
