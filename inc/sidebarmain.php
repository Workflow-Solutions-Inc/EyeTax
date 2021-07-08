<?php  
session_start();
require_once('inc/basepath.php');
$curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

if(isset($_SESSION['userid'])){
    
}else{
    header('Location: login.php');
}
?>
<!doctype html>
<html lang="en">
<body>


    <div class="sidebar" data-color="gray" data-image="assets/img/sidebar-4.jpg">

    <!--   you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple" -->


    	<div class="sidebar-wrapper">
            <div class="logo">
                <a href="http://www.creative-tim.com" class="simple-text">
                   Eye(i)Tax
                </a>
            </div>

            <ul class="nav">
                <li class="<?php if($curPageName=='userlogged.php'){echo 'active';}?>">
                    <a href="<?php echo $base_path; ?>userlogged.php">
                        <i class="pe-7s-browser"></i>
                        <p>Main</p>
                    </a>
                </li>
                <li class="<?php if($curPageName=='Receipts.php'){echo 'active';}?>">
                    <a href="<?php echo $base_path; ?>Receipts.php" id="excel">
                        <i class="pe-7s-display1"></i>
                        <p>Excel Files</p>
                    </a>
                </li>
                <!-- <li class="<?php if($curPageName=='SalesRelief.php'){echo 'active';}?>">
                    <a href="<?php echo $base_path; ?>SalesRelief.php">
                        <i class="pe-7s-note2"></i>
                        <p>DAT Files</p>
                    </a>
                </li> -->
                 <!-- <li>
                    <a href="<?php echo $base_path; ?>voucher.php">
                        <i class="pe-7s-note2"></i>
                        <p>Vouchers</p>
                    </a>
                </li> -->

                <li class="<?php if($curPageName=='Alphalist.php'){echo 'active';}?>">
                    <a href="<?php echo $base_path; ?>Alphalist.php">
                        <i class="pe-7s-news-paper"></i>
                        <p>Alphalist</p>
                    </a>
                </li>
                <li class="<?php if($curPageName=='syncxerodata.php'){echo 'active';}?>">
                    <a href="<?php echo $base_path; ?>syncxerodata.php">
                        <i class="pe-7s-science"></i>
                        <p>Sync Xero Data</p>
                    </a>
                </li>
            </ul>
    	</div>
    </div>




</body>



</html>
