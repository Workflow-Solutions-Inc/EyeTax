<?php 
require_once('inc/header.php');
include("process/controllers/config/dbconn.php");
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="assets/img/favicon.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Accounts</title>

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
    <div class="sidebar" data-color="gray" data-image="assets/img/sidebar-4.jpg">

    	<div class="sidebar-wrapper">
            <div class="logo">
                <label class="simple-text">Accounts</label>
            </div>

            <ul class="nav">
               
                 <li class="active">
                     <a href="Accounts.php">
                        <i class="pe-7s-note2"></i>
                        <p>Accounts</p> </a>
                </li>
            </ul>
    	</div>
    </div>

       <div class="main-panel">

            <div class="container-fluid">
                <div class="row">
                      <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Accounts List</h4>
                            </div>

                            <div class="content table-responsive table-full-width">
                               <table id="datatable" class="table table-hover table-striped">

                                    <thead>
                                        <th>IC</th>
                                        <th>Account Code</th>
                                        <th>Account Name</th>
                                        <th>Account Type</th>
                                    </thead>
                                    <tbody id="accountdata">
                                        
                                    </tbody>
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

            //alert(1);
            getAccountFromXero();

        });

        function getAccountFromXero(){

            var action = "getAccounts";
            $.ajax({
                            type: 'GET',
                            url: 'process/Accounts.php',
                            data:{action:action},

                            beforeSend:function(){

                            },
                            success: function(data){
                                //alert(data);
                                if(data=="error"){
                                    alert(1);
                                }else{
                                    var container = document.getElementById('accountdata');
                                    container.innerHTML = data;
                                }
                                
                            }

                }); 
        }

    </script>




</html>
