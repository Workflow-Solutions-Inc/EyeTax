<?php 

require_once('inc/sidebaralphalist.php');
require_once('inc/header.php');

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <title>Alphalist</title>

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
                                    <h4 class="title">Quarterly Alphalist</h4>
                                    <p class="category">Quarterly Alphalist to Payees</p>
                                </div>
                                <div class="content">
                               

                                 <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                              <i class="pe-7s-date"></i>   <label>Year</label>
                                                <input type="year" name="month" id="month" class="form-control" required>
                                            </div>
                                        </div>
                                        <!--<div class="col-md-3">
                                            <div class="form-group">
                                               <label>Amended Return</label>
                                                <select name="amendret" id="amendret" class="form-control">
                                                  <option value="1">Yes</option>
                                                  <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                              <label>No. of sheets Attached</label>
                                                <input type="Number" name="sheets" id="sheets" value = "0" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                               <label>Any Taxes withheld?</label>
                                               <select name="anytax" id="anytax" class="form-control">
                                                  <option value="1">Yes</option>
                                                  <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div>-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                              <label>Quarter</label>
                                                 <select name="quart" id="quart" class="form-control">
                                                  <option value="1">1st</option>
                                                  <option value="4">2nd</option>
                                                  <option value="7">3rd</option>
                                                  <option value="10">3rd</option>
                                                </select>
                                            </div>
                                        </div>
                                       <!-- <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Are There any Payees Availing of tax relief under special law or International tax treaty?</label>
                                               <select name="taxtreaty" id="taxtreaty" class="form-control">
                                                  <option value="1">Yes</option>
                                                  <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                               <label>Type</label>
                                                 <select name="type" id="type" class="form-control">
                                                    <option value="1600">1600</option>
                                                <option value="1601E">1601E</option>
                                                  <option value="1601F">1601F</option>
                                                  <option value="1600">1604E</option>
                                                </select>
                                            </div>
                                        </div>-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <button class="btn  btn-fill pull-right" onclick="GenerateReport();">Genereate File</button>
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
                                    <h4 class="title">Annual Alphalist</h4>
                                    <p class="category">Annual Alphalist to Payees</p>
                                </div>
                                <div class="content">
                               

                                 <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                              <i class="pe-7s-date"></i>   <label>Year</label>
                                                <input type="year" name="month2" id="month2" class="form-control" required>
                                            </div>
                                        </div>
                                        <!--<div class="col-md-3">
                                            <div class="form-group">
                                               <label>Amended Return</label>
                                                <select name="amendret" id="amendret" class="form-control">
                                                  <option value="1">Yes</option>
                                                  <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                              <label>No. of sheets Attached</label>
                                                <input type="Number" name="sheets" id="sheets" value = "0" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                               <label>Any Taxes withheld?</label>
                                               <select name="anytax" id="anytax" class="form-control">
                                                  <option value="1">Yes</option>
                                                  <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div>-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                              <label>SCHEDULE</label>
                                                 <select name="schedule" id="schedule" class="form-control" onchange="getdescriptions()">
                                                  <option value="0">Schedule 3</option>
                                                  <option value="1">Schedule 4</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                              <label>DESCRIPTION OF SCHEDULE</label>
                                                <LABEL class="form-control" id="descriptions">ALPHALIST OF OTHER PAYEES WHOSE INCOME PAYMENTS ARE EXEMPT FROM WITHHOLDING TAX BUT SUBJECT TO INCOME TAX</LABEL>
                                            </div>
                                        </div>
                                       <!-- <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Are There any Payees Availing of tax relief under special law or International tax treaty?</label>
                                               <select name="taxtreaty" id="taxtreaty" class="form-control">
                                                  <option value="1">Yes</option>
                                                  <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                               <label>Type</label>
                                                 <select name="type" id="type" class="form-control">
                                                    <option value="1600">1600</option>
                                                <option value="1601E">1601E</option>
                                                  <option value="1601F">1601F</option>
                                                  <option value="1600">1604E</option>
                                                </select>
                                            </div>
                                        </div>-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <button class="btn  btn-fill pull-right" onclick="GenerateReport2()">Genereate File</button>
                                            </div>
                                        </div>

                                    </div>
                                
                             </div>     
                            </div>
                        </div>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                      <div class="col-md-12">
                            <div class="card">
                                <div class="header">
                                    <h4 class="title">SLSP</h4>
                                    <p class="category">Sales Transaction & Purchase Transaction</p>
                                </div>
                                <div class="content">
                               

                                 <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                              <i class="pe-7s-date"></i>   <label>Year</label>
                                                <input type="year" name="month3" id="month3" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                              <label>Quarter</label>
                                                 <select name="quart2" id="quart2" class="form-control">
                                                  <option value="1">1st</option>
                                                  <option value="4">2nd</option>
                                                  <option value="7">3rd</option>
                                                  <option value="10">4th</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                               <label>Type</label>
                                                 <select name="type" id="slsp" class="form-control">
                                                    <option value="sls">Summary List of Sales</option>
                                                <option value="slp">Summary List of Purchases</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <button class="btn  btn-fill pull-right" onclick="GenerateSalesPurchase();">Genereate File</button>
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
     <script type="text/javascript">
        var inv = '';
       $("#datatable tbody tr").click(function() {
            $("#datatable tbody tr").removeClass("rowselected")
            $(this).addClass("rowselected")
            //alert ($("#datatable tbody tr:eq("+ ($(this).index()) +") td:eq(1)").text())

           
             inv = $("#datatable tbody tr:eq("+ ($(this).index()) +") td:eq(3)").text()
           /* let params = `status=no,location=no,toolbar=no,menubar=no,
                            width=1200,height=700,left=500,top=100`;
            let newWindow = open("../Vouchers/voucherdetails.php?vnno="+ inv, 'Voucher No - ' + inv, params);
                myWindow= window.open();*/
            
            

        });



    

        /*$("#btn btn-info").click(function()
                {
                   // $(this).addClass("rowselected")
                    $("#datatable tbody tr").removeClass("rowselected")
                     alert($("#datatable tbody tr:eq("+ ($(this).index()) +") td:eq(3)").text());
                  //  alert($("#datatable tbody tr:eq("+ ($(this).index()) +") td:eq(3)").text());
                });*/
    </script>
   <script src="assets/js/demo.js"></script>
     <script type="text/javascript">

        function GenerateReport(){
            //alert(type);
            var monthyear = document.getElementById("month").value; 
            var quart = document.getElementById("quart").value;
            window.open('Reports/BIR1601E.php?monthyear='+monthyear+'&quart='+quart, "_blank");
            //alert(taxtreaty);
            //window.open('Reports/13thmonthreport.php?id='+so+'&yr='+locYear+'&fromdate='+locfromdate+'&todate='+loctodate+'&dataareaid='+di, "_blank");

        }

        function GenerateReport2(){
            //alert(type);
            var monthyear = document.getElementById("month2").value; 
            var schedule = document.getElementById("schedule").value;
            if(schedule==0){
                window.open('Reports/Schedule3.php?monthyear='+monthyear+'&schedule='+schedule, "_blank");
            }else if(schedule==1){
                window.open('Reports/Schedule4.php?monthyear='+monthyear+'&schedule='+schedule, "_blank");
            }

        }

        function GenerateSalesPurchase(){
            var type = document.getElementById("slsp").value;
            var year = document.getElementById("month3").value; 
            var quart = document.getElementById("quart2").value; 
            //alert(type);
            if(type == "sls"){
                 window.open('Reports/Quarterofsalesphp.php?month3='+year+'&quart='+quart, "_blank");
            }else if(type == "slp"){
                 window.open('Reports/Quarterofpurchase.php?month3='+year+'&quart='+quart, "_blank");
            }
        }

        function getdescriptions(){
            var schedule = document.getElementById("schedule").value;
            if(schedule==0){
                document.getElementById("descriptions").innerHTML  = "ALPHALIST OF OTHER PAYEES WHOSE INCOME PAYMENTS ARE EXEMPT FROM WITHHOLDING TAX BUT SUBJECT TO INCOME TAX";
            }else if(schedule==1){
                document.getElementById("descriptions").innerHTML  = "ALPHALIST OF PAYEES SUBJECT TO EXPANDED WITHHOLDING TAX";
            }
            
        }

        // $(document).ready(function(){

        //     demo.initChartist();

        //     $.notify({
        //         icon: 'pe-7s-cash',
        //         message: "Alphalist Selected."

        //     },{ 
        //         type: 'info',
        //         timer: 4000
        //     });

        // });

    </script>
  
</html>
