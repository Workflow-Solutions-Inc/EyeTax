<?php 
include("process/controllers/config/dbconn.php");
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="assets/img/favicon.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Account Line</title>

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
                <label class="simple-text">Update Account</label>
            </div>

    	</div>
    </div>

       <div class="main-panel">
        <nav class="navbar navbar-default navbar-fixed">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">Account Line</a>
                </div>
               
            </div>
        </nav>
 
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                      <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Account Code:  <?php $invno = $_GET["inv"]; echo $invno;?></h4>
                                <p class="category">Item lines</p>
                            </div>
                             <div class="content table-responsive table-full-width">
                                <table id="datatable" class="table table-hover table-striped">
                                    <thead>
                                        <th>Account Code</th>
                                        <th>Account Name</th>
                                        <th>Account Type</th>
                                    </thead>
                                    <?php
                                    $invno = $_GET["inv"];
                                    $query = "SELECT * FROM accounts where accountcode= '".$invno."'";
                                    $result = $conn->query($query);
                                    while ($row = $result->fetch_assoc())
                                    { 
                                        
                                                  ?>

                                                            <tr>
                                                            <td style="width:15%;"><?php echo $row["accountcode"];?></td>
                                                            <td style="width:15%;"><?php echo $row["accountname"];?></td>
                                                            <td style="width:15%;">
                                                                <div>
                                                                        <select name="acctype" id="acctype" onchange='sample();'>
                                                                          <option value=""></option>
                                                                          <option value="1">Cash Asset</option>
                                                                          <option value="2">non-Cash Asset</option>
                                                                        </select>
                                                                </div>
                                                            </td>
                                            <?php 
                                      
                                    }?>
                                </table>

                            </div>
                        </div>
                        <div class="header">
                                <Button class="title pull-right" onClick="Save();">Save</button>
                            </div>
                        <span class="temporary-container-input">
                                    <input type="hidden" id="hide">
                                    <div style="display:none;width:1%;"><textarea id="t2" ><?php echo $invno;?></textarea></div>
                        </span>
                    </div>
                </div>
            </div>
            </form>
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

	<!-- Light Bootstrap Table DEMO methods, don't include it in your project! -->
    <script src="js/ajax.js"></script>
     <script type="text/javascript">
        

        function Save()
        {
            var selval = document.getElementById('acctype').value;
            var code = document.getElementById('t2').value;
            //alert(code);
            $.ajax({    
                            type: 'GET',
                            url: 'process/Accountlineprocess.php',
                            data:{code:code, selval:selval},
                            beforeSend:function(){
                            //alert(proof);
                                
                            },
                            success: function(data){
                            alert("Account Type has been updated");
                            location.reload();              
                            }
                    });
                     
        }

    </script>
</html>
