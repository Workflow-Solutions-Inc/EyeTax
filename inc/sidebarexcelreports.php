<?php  
require_once('inc/basepath.php');
$curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1); 
?>
<!doctype html>
<html lang="en">
<body>


    <div class="sidebar" data-color="gray" data-image="assets/img/sidebar-4.jpg">

    <!--   you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple" -->


    	<div class="sidebar-wrapper">
            <div class="logo">
                <label class="simple-text">Excel Reports</label>
            </div>

            <ul class="nav">
                
                <!--  <li   class="<?php if($curPageName=='SalesJournal.php'){echo 'active';}?>">
                     <a href="SalesJournal.php">
                        <i class="pe-7s-cash"></i>
                        <p>Sales Journal</p> </a>
                </li>
                <li class="<?php if($curPageName=='PurchaseJournal.php'){echo 'active';}?>">
                    <a href="PurchaseJournal.php">
                        <i class="pe-7s-credit"></i>
                        <p>Purchase Journal</p>   </a>
                </li> -->
                <li class="<?php if($curPageName=='Receipts.php'){echo 'active';}?>">   
                     <a href="Receipts.php">
                        <i class="pe-7s-news-paper"></i>
                        <p>Cash Receipts Book</p> </a>
                </li>
                <li class="<?php if($curPageName=='Disbursement.php'){echo 'active';}?>">
                    <a href="Disbursement.php">
                        <i class="pe-7s-share"></i>
                        <p>Cash Disbursements Book</p>   </a>
                
                </li>
                <li class="<?php if($curPageName=='voucher.php'){echo 'active';}?>">
                    <a href="voucher.php">
                        <i class="pe-7s-note2"></i>
                        <p>Vouchers</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>




</body>



</html>
