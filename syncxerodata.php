<?php
include('inc/sidebarmain.php');
include('inc/header.php');

?>
<!doctype html>
    <html lang="en">
    <head>
       <meta charset="utf-8" />
       <link rel="icon" type="image/png" href="assets/img/favicon.png">
       <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

       <title>Integrations</title>

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


            <div class="content">
                <div>
                    <div class="container-fluid">
                        <div class="row">
                          <div class="col-md-12">
                            <div class="card">
                                <div class="header">
                                    <h4 class="title">Bills to Pay / Invoices</h4>
                                    <p class="category">Note: Please select a specific date before clicking the Generate button.</p>
                                </div>
                                <div class="content">
                                 

                                   <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                          <i class="pe-7s-date"></i>   <label>From Date</label>
                                          <input type="date" name="frmdate" id="invfrmdate" class="form-control">
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="form-group">
                                       <i class="pe-7s-date"></i>   <label>To Date</label>
                                       <input type="date" name="todate" id="invtodate" class="form-control">
                                   </div>
                               </div>
                               <div class="col-md-6">
                                <div class="form-group">
                                 
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <button onclick="syncinvoice()" id="invbtn" class="btn  btn-fill pull-right">Synchronize</button>
                                </div>
                            </div>

                        </div>
                        
                    </div>     
                </div>
            </div>
        </div>
    </div>
</div>



<div class="content">
    <div>
        <div class="container-fluid">
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Transfer & Receive Money</h4>
                        <p class="category">Note: Please select a specific date before clicking the Generate button.</p>
                    </div>
                    <div class="content">
                     

                       <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                              <i class="pe-7s-date"></i>   <label>From Date</label>
                              <input type="date" name="frmdate" id="bankfrmdate" class="form-control" required>
                          </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                           <i class="pe-7s-date"></i>   <label>To Date</label>
                           <input type="date" name="todate" id="banktodate" class="form-control" required>
                       </div>
                   </div>
                   <div class="col-md-6">
                    <div class="form-group">
                     
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <button id="bankbtn"  onclick="syncbanktransaction()" class="btn  btn-fill pull-right">Synchronize</button>
                    </div>
                </div>

            </div>
            
        </div>     
    </div>
</div>
</div>
</div>
</div>

<div class="content">
    <div>
        <div class="container-fluid">
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Items / Accounts</h4>
                        <p class="category">Note: Please click the button to synchronize all items and accounts from xero.</p>
                    </div>
                    <div class="content">
                     

                       <div class="row">
                        
                        <div class="col-md-6">
                            <div class="form-group">
                             
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <button onclick="syncprere()" id="prerebtn" class="btn  btn-fill pull-right">Synchronize</button>
                            </div>
                        </div>

                    </div>
                    
                </div>     
            </div>
        </div>
    </div>
</div>
</div>


<div class="content">
    <div>
        <div class="container-fluid">
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Customers</h4>
                        <p class="category">Note: Please click the button to synchronize all customers from xero.</p>
                    </div>
                    <div class="content">
                     

                       <div class="row">
                        
                        <div class="col-md-6">
                            <div class="form-group">
                             
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <button onclick="synccontacts()" id="custbtn" class="btn  btn-fill pull-right">Synchronize</button>
                            </div>
                        </div>

                    </div>
                    
                </div>     
            </div>
        </div>
    </div>
</div>





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

<script src="assets/js/demo.js"></script>


</html>

<script type="text/javascript">
    function syncinvoice(){

        var frmdate = document.getElementById("invfrmdate").value;
        var todate = document.getElementById("invtodate").value;

        if(frmdate == "" || todate == ""){
            $.notify({
                icon: 'pe-7s-close',
                message: "From date and to date must not be empty"

            },{
                type: 'danger',
                timer: 500
            });
        }else{
            $.ajax({
                    type: 'GET',
                    url: 'process/controllers/synchinvoice.php',
                    data:{frmdate:frmdate,todate:todate},

                    beforeSend:function(){
                        document.getElementById("invbtn").innerHTML = '<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Loading';
                        disablebuttons();
                    },
                    alert(data);
                    success: function(data){
                        if(data==1){
                            notifysuccess();
                        }else{
                           notifyfailed();
                        }
                        document.getElementById("invbtn").innerHTML = 'Synchronize';
                        enablebuttons();
                        
                    }

                }); 
        }
        
    }


    function syncbanktransaction(){

        var frmdate = document.getElementById("bankfrmdate").value;
        var todate = document.getElementById("banktodate").value;

        if(frmdate == "" || todate == ""){
            $.notify({
                icon: 'pe-7s-close',
                message: "From date and to date must not be empty"

            },{
                type: 'danger',
                timer: 500
            });
        }else{
            $.ajax({
                    type: 'GET',
                    url: 'process/controllers/syncbank.php',
                    data:{frmdate:frmdate,todate:todate},

                    beforeSend:function(){
                        document.getElementById("bankbtn").innerHTML = '<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Loading';
                        disablebuttons();
                    },
                    success: function(data){

                        if(data==1){
                            notifysuccess();
                        }else{
                           notifyfailed();
                        }
                        document.getElementById("bankbtn").innerHTML = 'Synchronize';
                        enablebuttons();
                        
                    }

                }); 
        }
        
    }

     function synccontacts(){

        
            $.ajax({
                    type: 'GET',
                    url: 'process/controllers/synccustomer.php',
                    data:{},

                    beforeSend:function(){
                        document.getElementById("custbtn").innerHTML = '<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Loading';
                        disablebuttons();
                    },
                    success: function(data){
                        
                        if(data==1){
                            notifysuccess();
                        }else{
                           notifyfailed();
                        }
                        document.getElementById("custbtn").innerHTML = 'Synchronize';
                        enablebuttons();
                        
                    }

                }); 
        
        
    }

    function syncprere(){

        
            $.ajax({
                    type: 'GET',
                    url: 'process/controllers/syncprere.php',
                    data:{},

                    beforeSend:function(){
                        document.getElementById("prerebtn").innerHTML = '<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Loading';
                        disablebuttons();
                    },
                    success: function(data){
                        
                        if(data==1){
                            notifysuccess();
                        }else{
                           notifyfailed();
                        }
                        document.getElementById("prerebtn").innerHTML = 'Synchronize';
                        enablebuttons();
                        
                    }

                }); 
        
        
    }

    function notifysuccess(){
        $.notify({
            icon: 'pe-7s-check',
            message: "Data from xero synched successfully"

        },{
            type: 'success',
            timer: 500
        });
    }

    function notifyfailed(){
         $.notify({
            icon: 'pe-7s-close',
            message: "Synchronization Failed, Please reconnect to xero."

        },{
            type: 'danger',
            timer: 500
        });
    }

    function disablebuttons(){
        document.getElementById("bankbtn").disabled = true;
        document.getElementById("custbtn").disabled = true;
        document.getElementById("prerebtn").disabled = true;
        document.getElementById("invbtn").disabled = true;
    }

    function enablebuttons(){
        document.getElementById("bankbtn").disabled = false;
        document.getElementById("custbtn").disabled = false;
        document.getElementById("prerebtn").disabled = false;
        document.getElementById("invbtn").disabled = false;
    }
</script>
