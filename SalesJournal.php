<?php 
require_once('inc/sidebarexcelreports.php');
require_once('inc/header.php');
include("process/controllers/config/dbconn.php");
?>
<style type="text/css">
    .modal-dialog,
.modal-content {
    /* 80% of window height */
    height: auto;
    width: auto;
}

</style>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<link rel="icon" type="image/png" href="assets/img/favicon.png">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

<title>Sales Journal - Excel File Generation</title>

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
        <form action="process/controllers/Reports/Salesjournal.php" method="get">
        <div class="container-fluid">
            <div class="row">
                  <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Sales Journal Parameters</h4>
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
                                    
                                    <div class="col-md-6" id="myCheckBox">
                                        <div class="form-group">
                                            <label for="Date"> Report Filter &nbsp</label><br>
                                            
                                           <input type="checkbox" id="Date" name="Date" value="1"  checked="1">
                                            <label for="Date"> Date &nbsp</label>

                                            <input type="checkbox" id="invno" name="invno" value="1" checked="1"> 
                                            <label for="invno"> Invoice No. &nbsp</label>

                                            <input type="checkbox" id="custname" name="custname" value="1" checked="1">
                                            <label for="custname"> Customer Name &nbsp</label>

                                            <!-- <input type="checkbox" id="Subtotal" name="Subtotal" value="1">
                                            <label for="Subtotal"> Sub Total &nbsp</label>

                                            <input type="checkbox" id="tin" name="tin" value="1" checked="1">
                                            <label for="tin"> Tin &nbsp</label> 

                                            <input type="checkbox" id="address" name="address" value="1" checked="1">
                                            <label for="address"> address &nbsp</label>-->

                                            <input type="checkbox" id="accrec" name="accrec" value="1" checked="1">
                                            <label for="accrec"> Accounts Receivable &nbsp</label><br>

                                            <input type="checkbox" id="exsale" name="exsale" value="1" checked="1">
                                            <label for="exsale"> Exempt Sales &nbsp</label>

                                            <input type="checkbox" id="zerosale" name="zerosale" value="1" checked="1">
                                            <label for="zerosale"> Zero Rated Sale &nbsp</label>

                                            <input type="checkbox" id="vatsale" name="vatsale" value="1" checked="1">
                                            <label for="vatsale"> Vatable Sales &nbsp</label>

                                            <input type="checkbox" id="outvat" name="outvat" value="1" checked="1">
                                            <label for="outvat"> Output Vat &nbsp</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="submit" class="btn  btn-fill pull-right">Generate Sales Journal</button>
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
                            <h4 class="title">Invoices (Receivables)</h4>
                            
                            <p class="category">All Accounts Receivable</p>
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
                            <h4 class="title">Pending Receivables</h4>
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
<div id="myModal" class="modal fade" role="dialog" aria-hidden="true" tabindex="-1">
<div class="modal-dialog modal-dialog-centered" role="document">

<!-- Modal content-->
<div class="modal-content" >
  <div class="modal-header">
    <h4 class="modal-title">Modal Header</h4>
  </div>
  <div class="modal-body">
    <div class="row" >
        <div class="card">
            <div class="header">
                <h4 class="title">Invoice Number:  5 |  Status: PAID </h4>
                
            </div>
            <div class="col-md-12">
                jonald arjon cruz
            </div>
            <br>
            <div class="col-md-12">
                <p class="category">Item lines</p>
            </div>
             <div>
                <table class="table table-hover table-striped">
                    <thead>
                       <tr>  
                         <th>DATE</th>  
                         <th>INVOICE NO.</th>  
                         <th>CUSTOMER NAME</th>  
                         <th>TIN</th>  
                         <th>ADDRESS</th>
                    </tr>
                    </thead>
                    
                </table>

            </div>
        </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary">Send Email</button>
  </div>
</div>

</div>
</div>

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

        //alert(1);
        getXeroDataPaid();
        getXeroData();
        //$('#myModal').modal('toggle');

    });

    function getXeroData(){

        var action = "getACCREC";
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

        var action = "getACCRECPAID";
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
