<?php
	
	//API of xero
  ini_set('display_errors', 'On');
  require __DIR__ . '/vendor/autoload.php';
  require_once('config/xeroconfig.php');
  require_once('storage.php');
  include("config/dbconn.php");

	  // Use this class to deserialize error caught
	  use XeroAPI\XeroPHP\AccountingObjectSerializer;

	  // Storage Classe uses sessions for storing token > extend to your DB of choice
	  $storage = new StorageClass();
	  $xeroTenantId = (string)$storage->getSession()['tenant_id'];

	  if ($storage->getHasExpired()) {
	    $provider = new \League\OAuth2\Client\Provider\GenericProvider([
	      'clientId'                => $clientid,   
	      'clientSecret'            => $clientsecret,
	      'redirectUri'             => $callback,
	      'urlAuthorize'            => 'https://login.xero.com/identity/connect/authorize',
	      'urlAccessToken'          => 'https://identity.xero.com/connect/token',
	      'urlResourceOwnerDetails' => 'https://api.xero.com/api.xro/2.0/Organisation'
	    ]);

	    $newAccessToken = $provider->getAccessToken('refresh_token', [
	      'refresh_token' => $storage->getRefreshToken()
	    ]);
	    
	    // Save my token, expiration and refresh token
	    $storage->setToken(
	        $newAccessToken->getToken(),
	        $newAccessToken->getExpires(), 
	        $xeroTenantId,
	        $newAccessToken->getRefreshToken(),
	        $newAccessToken->getValues()["id_token"] );
	  }

	  $config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken( (string)$storage->getSession()['token'] );
	  $config->setHost("https://api.xero.com/api.xro/2.0");        

	  $apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
	      new GuzzleHttp\Client(),
	      $config
	  );

	//API of xero

	$action = $_GET['action'];

//accrec authorized
	if($action == "getACCREC"){

		// get ACCREC UNPAID ->
		$number = 1;
		$output = "";
		$apiResponse = $apiInstance->getInvoices($xeroTenantId, $if_modified_since = null, $where = 'Type == "ACCREC"', $order = null, $i_ds = null, $invoice_numbers = null, $contact_i_ds = null, $statuses = 'AUTHORISED', $page = null, $include_archived = null, $created_by_my_app = null, $unitdp = null);
		$cnt =  count($apiResponse->getInvoices());
	    $arr  = (json_decode($apiResponse, true));
	    for ($i = 0; $i < $cnt; $i++)
		{
			$output .= '<tr><td style="width:5%;">'.$number.'</td>
						<td style="width:20%;">'.$arr["Invoices"][$i]["InvoiceNumber"].'</td>
                        <td style="width:20%;">'.$arr["Invoices"][$i]["Contact"]["Name"].'</td>
                        <td style="width:20%;">'.$arr["Invoices"][$i]["Date"].'</td>
                        <td style="width:20%;">'.$arr["Invoices"][$i]["Duedate"].'</td>
                        <td style="width:20%;"><span>&#8369;</span>'.$arr["Invoices"][$i]["PaidAmount"].'</td>
                        <td style="width:20%;"><span>&#8369;</span>'.$arr["Invoices"][$i]["AmountDue"].'</td>
                        <td style="width:20%;"><span>&#8369;</span>'.$arr["Invoices"][$i]["Status"].'</td>
                        <td style="width:20%; display:none;">'.$arr["Invoices"][$i]["InvoiceID"].'</td></tr>';
                        $number++;
		}
		echo $output;
		// <- get ACCREC UNPAID

	}

//accred PAID

	else if($action == "getACCRECPAID"){
		
		// get ACCREC PAID ->
		$number = 1;
		$output = "";
		$apiResponse = $apiInstance->getInvoices($xeroTenantId, $if_modified_since = null, $where = 'Type == "ACCREC"', $order = 'Date asc', $i_ds = null, $invoice_numbers = null, $contact_i_ds = null, $statuses = 'PAID,AUTHORISED', $page = null, $include_archived = null, $created_by_my_app = null, $unitdp = null);
		$cnt =  count($apiResponse->getInvoices());
	    $arr  = (json_decode($apiResponse, true));
	    for ($i = 0; $i < $cnt; $i++)
		{
			$invdate=date_create($arr["Invoices"][$i]["Date"]);
			$invduedate=date_create($arr["Invoices"][$i]["DueDate"]);
			$output .= '<tr><td style="width:5%;">'.$number.'</td>
						<td style="width:15%;">'.$arr["Invoices"][$i]["InvoiceNumber"].'</td>
                        <td style="width:15%;">'.$arr["Invoices"][$i]["Contact"]["Name"].'</td>
                        <td style="width:15%;">'.date_format($invdate,"Y/m/d").'</td>
                        <td style="width:15%;">'.date_format($invduedate,"Y/m/d").'</td>
                        <td style="width:15%;"><span>&#8369;</span>'.number_format($arr["Invoices"][$i]["AmountPaid"],2).'</td>
                        <td style="width:15%;"><span>&#8369;</span>'.number_format($arr["Invoices"][$i]["AmountDue"],2).'</td>
                        <td style="width:15%;">'.$arr["Invoices"][$i]["Status"].'</td>
                        <td style="width:15%; display:none;">'.$arr["Invoices"][$i]["InvoiceID"].'</td></tr>';
                        $number++;


		}
		echo $output;
		// <- get ACCREC PAID

	}

//ACCPAY PENDING

	else if($action == "getACCPAY"){
		
		// get ACCPAY PENDING ->
		$number = 1;
		$output = "";
		$apiResponse = $apiInstance->getInvoices($xeroTenantId, $if_modified_since = null, $where = 'Type == "ACCPAY"', $order = null, $i_ds = null, $invoice_numbers = null, $contact_i_ds = null, $statuses = 'AUTHORISED', $page = null, $include_archived = null, $created_by_my_app = null, $unitdp = null);
		$cnt =  count($apiResponse->getInvoices());
	    $arr  = (json_decode($apiResponse, true));
	    for ($i = 0; $i < $cnt; $i++)
		{
			$output .= '<tr><td style="width:5%;">'.$number.'</td>
						<td style="width:20%;">'.$arr["Invoices"][$i]["Date"].'</td>
                        <td style="width:20%;">'.$arr["Invoices"][$i]["InvoiceNumber"].'</td>
                        <td style="width:20%;">'.$arr["Invoices"][$i]["Contact"]["Name"].'</td>
                        <td style="width:20%;">UNPAID</td>
                        <td style="width:20%;"><span>&#8369;</span>'.$arr["Invoices"][$i]["Total"].'</td>
                        <td style="width:20%; display:none;">'.$arr["Invoices"][$i]["InvoiceID"].'</td></tr>';
                        $number++;
		}
		echo $output;
		// <- get ACCPAY PENDING
		

	}


//ACCPAY PAID

	else if($action == "getACCPAYPAID"){
		
		// get ACCPAY PAID ->
		$number = 1;
		$output = "";
		$apiResponse = $apiInstance->getInvoices($xeroTenantId, $if_modified_since = null, $where = 'Type == "ACCPAY"', $order = 'Date asc', $i_ds = null, $invoice_numbers = null, $contact_i_ds = null, $statuses = 'PAID,AUTHORISED', $page = null, $include_archived = null, $created_by_my_app = null, $unitdp = null);
		$cnt =  count($apiResponse->getInvoices());
	    $arr  = (json_decode($apiResponse, true));
	    for ($i = 0; $i < $cnt; $i++)
		{
			$invdate=date_create($arr["Invoices"][$i]["Date"]);
			$invduedate=date_create($arr["Invoices"][$i]["DueDate"]);
			$output .= '<tr><td style="width:5%;">'.$number.'</td>
						<td style="width:15%;">'.$arr["Invoices"][$i]["InvoiceNumber"].'</td>
                        <td style="width:15%;">'.$arr["Invoices"][$i]["Contact"]["Name"].'</td>
                        <td style="width:15%;">'.date_format($invdate,"Y/m/d").'</td>
                        <td style="width:15%;">'.date_format($invduedate,"Y/m/d").'</td>
                        <td style="width:15%;"><span>&#8369;</span>'.number_format($arr["Invoices"][$i]["AmountPaid"],2).'</td>
                        <td style="width:15%;"><span>&#8369;</span>'.number_format($arr["Invoices"][$i]["AmountDue"],2).'</td>
                        <td style="width:15%;">'.$arr["Invoices"][$i]["Status"].'</td>
                        <td style="width:15%; display:none;">'.$arr["Invoices"][$i]["InvoiceID"].'</td></tr>';
                        $number++;
		}
		echo $output;
		// <- get ACCPAY PAID
		

	}

// GET DISBURSEMENT

	elseif ($action == "getDisbursement") {
		
		// get ACCPAY PAID ->
		$number = 1;
		$output = "";
		$apiResponse = $apiInstance->getInvoices($xeroTenantId, $if_modified_since = null, $where = 'Type == "ACCPAY"', $order = null, $i_ds = null, $invoice_numbers = null, $contact_i_ds = null, $statuses = 'PAID', $page = null, $include_archived = null, $created_by_my_app = null, $unitdp = null);
		$cnt =  count($apiResponse->getInvoices());
	    $arr  = (json_decode($apiResponse, true));
	    for ($i = 0; $i < $cnt; $i++)
		{
			$miscamount = 0;
			$rentamount = 0;
			$sapaamount = 0;
			$otacamount = 0;
			$utilamount = 0;
			$accpayamount = 0;
			$cnt2 = count($arr["Invoices"][$i]["LineItems"]);
			for ($j=0; $j < $cnt2; $j++) { 
			 if($arr["Invoices"][$i]["LineItems"][$j]["AccountCode"]=="523"){
			 	$miscamount+=$arr["Invoices"][$i]["LineItems"][$j]["LineAmount"];
			 }
			 if($arr["Invoices"][$i]["LineItems"][$j]["AccountCode"]=="469"){
			 	$rentamount+=$arr["Invoices"][$i]["LineItems"][$j]["LineAmount"];
			 }
			 if($arr["Invoices"][$i]["LineItems"][$j]["AccountCode"]=="477"){
			 	$sapaamount+=$arr["Invoices"][$i]["LineItems"][$j]["LineAmount"];
			 }
			 if($arr["Invoices"][$i]["LineItems"][$j]["AccountCode"]=="514"){
			 	$utilamount+=$arr["Invoices"][$i]["LineItems"][$j]["LineAmount"];
			 }
			 if($arr["Invoices"][$i]["LineItems"][$j]["AccountCode"]=="507"){
			 	$otacamount+=$arr["Invoices"][$i]["LineItems"][$j]["LineAmount"];
			 }
			 if($arr["Invoices"][$i]["LineItems"][$j]["AccountCode"]!="507" && 
			 	$arr["Invoices"][$i]["LineItems"][$j]["AccountCode"]!="514" &&
				$arr["Invoices"][$i]["LineItems"][$j]["AccountCode"]!="477" &&
				$arr["Invoices"][$i]["LineItems"][$j]["AccountCode"]!="469" &&
				$arr["Invoices"][$i]["LineItems"][$j]["AccountCode"]!="523"){

			 	$accpayamount += $arr["Invoices"][$i]["LineItems"][$j]["LineAmount"];

			 }
			 
		}
			$output .= '<tr><td style="width:5%;">'.$number.'</td>
	                    <td style="width:8%;">'.$arr["Invoices"][$i]["InvoiceNumber"].'</td>
	                    <td style="width:8%;">'.$arr["Invoices"][$i]["Date"].'</td>
	                    <td style="width:8%;">'.$arr["Invoices"][$i]["Contact"]["Name"].'</td>
	                    <td style="width:7%;">'.$arr["Invoices"][$i]["Status"].'</td>
	                    
	                    <td style="width:8%;"><span>&#8369;</span>'.number_format($arr["Invoices"][$i]["Total"],2).'</td>
	                    <td style="width:8%;"><span>&#8369;</span>'.number_format($arr["Invoices"][$i]["TotalTax"],2).'</td>
	                    <td style="width:8%;"><span>&#8369;</span>'.number_format($miscamount,2).'</td>
	                    <td style="width:8%;"><span>&#8369;</span>'.number_format($rentamount,2).'</td>
	                    <td style="width:8%;"><span>&#8369;</span>'.number_format($sapaamount,2).'</td>
	                    <td style="width:8%;"><span>&#8369;</span>'.number_format($utilamount,2).'</td>
	                    <td style="width:8%;"><span>&#8369;</span>'.number_format($otacamount,2).'</td>
	                    <td style="width:8%;"><span>&#8369;</span>'.number_format($accpayamount,2).'</td>
	                    <td style="width:8%; display:none;">'.$arr["Invoices"][$i]["InvoiceID"].'</td></tr>';
	                    $number++;
		}

		$curpage = 1;
		  do {
		    // <- API

		   $apiResponse =  $apiInstance -> getBankTransactions($xeroTenantId, $if_modified_since = null, 'Status =="AUTHORISED" AND Reference != "DM"', $order = 'Date asc', $page = $curpage, $unitdp = null);
		   //echo $apiInstance -> getBankTransactions($xeroTenantId, $if_modified_since = null, $where.' AND Status =="AUTHORISED"', $order = null, $page = $curpage, $unitdp = null);
		    $cnt =  count($apiResponse->getBankTransactions());
		    $arr  = (json_decode($apiResponse, true));
		   $curpage ++;

		   for ($i=0; $i < $cnt ; $i++) { 
		     // code...
		    $xerocontact = "";
		    if(isset($arr["BankTransactions"][$i]["Contact"]["Name"])){
		      $xerocontact = $arr["BankTransactions"][$i]["Contact"]["Name"];
		    }
		    $xerosubtotal = 0;
		    if(isset($arr["BankTransactions"][$i]["SubTotal"])){
		      $xerosubtotal = $arr["BankTransactions"][$i]["SubTotal"];
		    }
		    $xerototaltax = 0;
		    if(isset($arr["BankTransactions"][$i]["TotalTax"])){
		      $xerototaltax = $arr["BankTransactions"][$i]["TotalTax"];
		    }
		    $xerototal = 0;
		    if(isset($arr["BankTransactions"][$i]["Total"])){
		      $xerototal = $arr["BankTransactions"][$i]["Total"];
		    }
		    $xeroreference = "";
		    if(isset($arr["BankTransactions"][$i]["Reference"])){
		      $xeroreference = $arr["BankTransactions"][$i]["Reference"];
		    }
		    $xerobank = "";
		    if(isset($arr["BankTransactions"][$i]["BankAccount"]["Code"])){
		      $xerobank = $arr["BankTransactions"][$i]["BankAccount"]["Code"];
		    }

		    $xeroreference = "";
		      if (isset($arr["BankTransactions"][$i]["Reference"])){
		        $xeroreference = $arr["BankTransactions"][$i]["Reference"];
		        if(strpos($xeroreference, '|') !== false){
		          $explodestring = explode('|', str_replace(' ', '', $xeroreference));
		          $newref = $explodestring[1];
		        }else{
		          $newref = $arr["BankTransactions"][$i]["Reference"];
		        }   
		      }

		    
		    $miscamount = 0;
			$rentamount = 0;
			$sapaamount = 0;
			$otacamount = 0;
			$utilamount = 0;
			$accpayamount = 0;
		    $cnt2 = count($arr["BankTransactions"][$i]["LineItems"]);
		    for ($j=0; $j < $cnt2; $j++) { 
		      // code...
		      $linedescription = "";
		      if(isset($arr["BankTransactions"][$i]["LineItems"][$j]["Description"])){
		        $linedescription = $arr["BankTransactions"][$i]["LineItems"][$j]["Description"];
		        $linedescription = str_replace("'", "", $linedescription);
		      }
		      $linetaxtype = "";
		      if(isset($arr["BankTransactions"][$i]["LineItems"]["TaxType"])){
		        $linetaxtype = $arr["BankTransactions"][$i]["LineItems"][$j]["TaxType"];
		      }

		      if($arr["BankTransactions"][$i]["LineItems"][$j]["AccountCode"]=="523"){
			 	$miscamount+=$arr["BankTransactions"][$i]["LineItems"][$j]["LineAmount"];
			 }
			 if($arr["BankTransactions"][$i]["LineItems"][$j]["AccountCode"]=="469"){
			 	$rentamount+=$arr["BankTransactions"][$i]["LineItems"][$j]["LineAmount"];
			 }
			 if($arr["BankTransactions"][$i]["LineItems"][$j]["AccountCode"]=="477"){
			 	$sapaamount+=$arr["BankTransactions"][$i]["LineItems"][$j]["LineAmount"];
			 }
			 if($arr["BankTransactions"][$i]["LineItems"][$j]["AccountCode"]=="514"){
			 	$utilamount+=$arr["BankTransactions"][$i]["LineItems"][$j]["LineAmount"];
			 }
			 if($arr["BankTransactions"][$i]["LineItems"][$j]["AccountCode"]=="507"){
			 	$otacamount+=$arr["BankTransactions"][$i]["LineItems"][$j]["LineAmount"];
			 }
			 if($arr["BankTransactions"][$i]["LineItems"][$j]["AccountCode"]!="507" && 
			 	$arr["BankTransactions"][$i]["LineItems"][$j]["AccountCode"]!="514" &&
				$arr["BankTransactions"][$i]["LineItems"][$j]["AccountCode"]!="477" &&
				$arr["BankTransactions"][$i]["LineItems"][$j]["AccountCode"]!="469" &&
				$arr["BankTransactions"][$i]["LineItems"][$j]["AccountCode"]!="523"){

			 	$accpayamount += $arr["BankTransactions"][$i]["LineItems"][$j]["LineAmount"];

			 }

		      /*$insertlines = "CALL `insert_banktransaction_lines`(
		      '".$arr["BankTransactions"][$i]["BankTransactionID"]."', 
		      '".$arr["BankTransactions"][$i]["LineItems"][$j]["LineItemID"]."', 
		      '".$linedescription."', 
		      ".$arr["BankTransactions"][$i]["LineItems"][$j]["Quantity"].", 
		      ".$arr["BankTransactions"][$i]["LineItems"][$j]["UnitAmount"].", 
		      '".$arr["BankTransactions"][$i]["LineItems"][$j]["AccountCode"]."', 
		      '".$linetaxtype."', 
		      ".$arr["BankTransactions"][$i]["LineItems"][$j]["TaxAmount"].", 
		      ".$arr["BankTransactions"][$i]["LineItems"][$j]["LineAmount"].", 
		      '".$orgid."');
		";
		      if(mysqli_query($conn,$insertlines))
		        {

		        }
		        else
		        {
		          echo "error ".$insertlines."<br>".$conn->error;
		        }*/

		    }

		    $output .= '<tr><td style="width:5%;">'.$number.'</td>
	                    <td style="width:8%;">'.$xeroreference.'</td>
	                    <td style="width:8%;">'.$arr["BankTransactions"][$i]["Date"].'</td>
	                    <td style="width:8%;">'.$xerocontact.'</td>
	                    <td style="width:7%;">PAID</td>
	                    
	                    <td style="width:8%;"><span>&#8369;</span>'.number_format($arr["BankTransactions"][$i]["Total"],2).'</td>
	                    <td style="width:8%;"><span>&#8369;</span>'.number_format(0,2).'</td>
	                    <td style="width:8%;"><span>&#8369;</span>'.number_format($miscamount,2).'</td>
	                    <td style="width:8%;"><span>&#8369;</span>'.number_format($rentamount,2).'</td>
	                    <td style="width:8%;"><span>&#8369;</span>'.number_format($sapaamount,2).'</td>
	                    <td style="width:8%;"><span>&#8369;</span>'.number_format($utilamount,2).'</td>
	                    <td style="width:8%;"><span>&#8369;</span>'.number_format($otacamount,2).'</td>
	                    <td style="width:8%;"><span>&#8369;</span>'.number_format($accpayamount,2).'</td></tr>';
	                    $number++;

		   }

		   $curpage++;
		    $apiResponse =  $apiInstance -> getBankTransactions($xeroTenantId, $if_modified_since = null, 'Status =="AUTHORISED"', $order = null, $page = $curpage, $unitdp = null);
		    $cnt =  count($apiResponse->getBankTransactions());
		    if($cnt==0){
		      $curpage = 0;
		    }


		  }while($curpage!=0);
		echo $output;
		// <- get ACCPAY PAID
		

	}



	

?>