<?php
	
	//API of xero
	  ini_set('display_errors', 'On');
  	//require __DIR__ . '/vendor/autoload.php';
	  require_once('controllers/config/xeroconfig.php');
	  require __DIR__ . '/controllers/vendor/autoload.php';
	  require_once('controllers/storage.php');
	  include("controllers/config/dbconn.php");

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
		
		$number = 1;
		$output = "";
		try {
		    $apiResponse = $apiInstance->getInvoices($xeroTenantId, $if_modified_since = null, null, $order = null, $i_ds = null, $invoice_numbers = null, $contact_i_ds = null, $statuses = 'AUTHORISED', $page = null, $include_archived = null, $created_by_my_app = null, $unitdp = null);
		    //echo $apiInstance->getInvoices($xeroTenantId, null, null, null, null, null, null, null, null, null, null, null);
		    $cnt =  count($apiResponse->getInvoices());

		    $arr  = (json_decode($apiResponse, true));
		    $invacc = "";
		    $invamnt = "";
		    $invDate = "";
		    $custId = "";
		    $custName = "";
		    $invRefnum = "";
		    $lineItems = "";
		    $paidamount = "";
		    $id = "";
		   
		  
		   $itm_ = "";
		    for ($i = 0; $i < $cnt; $i++)
		    {
		       if(isset($arr["Invoices"][$i]["InvoiceNumber"]))
		       {
		          $invacc =  $arr["Invoices"][$i]["InvoiceNumber"];
		          $id =  $arr["Invoices"][$i]["InvoiceID"];
		          
		          if ($invacc != '')
		          {
		            if(isset($arr["Invoices"][$i]["Contact"]["ContactID"]))
		               {   
		                   $custId = $arr["Invoices"][$i]["Contact"]["ContactID"];
		                  if(isset($arr["Invoices"][$i]["Date"]))
		                   {
		                       if(isset($arr["Invoices"][$i]["Total"]))
		                         {
		                         		$contactname = $arr["Invoices"][$i]["Contact"]["Name"];
		                                $invamnt =  $arr["Invoices"][$i]["Total"];
		                                $invtaxtype =  $arr["Invoices"][$i]["LineAmountTypes"];
		                                $invDate =  $arr["Invoices"][$i]["Date"];
		                                $invType =  $arr["Invoices"][$i]["Type"];
		                                $invStatus =  $arr["Invoices"][$i]["Status"];
		                                $paidamount =  $arr["Invoices"][$i]["AmountPaid"];
		                                $refrence =  $arr["Invoices"][$i]["Reference"];
		                                $duedate =  $arr["Invoices"][$i]["DueDate"];
		                                $subtotal =  $arr["Invoices"][$i]["SubTotal"];
		                                $totaltax =  $arr["Invoices"][$i]["TotalTax"];
		                                $curcode =  $arr["Invoices"][$i]["CurrencyCode"];
		                                $amountdue =  $arr["Invoices"][$i]["AmountDue"];
		                                $amountcredited =  $arr["Invoices"][$i]["AmountCredited"];
		                                if($invType == 'ACCREC'){

		                                	if($invStatus == "AUTHORISED"){
		                                	$output .= '<tr><td style="width:5%;">'.$number.'</td>
                                            <td style="width:22%;">'.$invacc.'</td>
                                            <td style="width:22%;">'.$contactname.'</td>
                                            <td style="width:22%;">'.$invStatus.'</td>
                                            <td style="width:22%;"><span>&#8369;</span>'.$invamnt.'.00</td>
                                            <td style="width:22%; display:none;">'.$id.'</td></tr>';
                                            $number++;
		                                	}
		                                
		                                }
		                                
		                                

		                          }
		                    
		                   }
		                  
		               }

		          }
		          
		       }
		    }

		    echo $output;
	        
		} catch (Exception $e) {
		    echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
		

	}

//accred PAID

	else if($action == "getACCRECPAID"){
		
		$number = 1;
		$output = "";
		try {
		    $apiResponse = $apiInstance->getInvoices($xeroTenantId, null, null, null, null, null, null, null, null, null, null, null);
		    //echo $apiInstance->getInvoices($xeroTenantId, null, null, null, null, null, null, null, null, null, null, null);
		    $cnt =  count($apiResponse->getInvoices());

		    $arr  = (json_decode($apiResponse, true));
		    $invacc = "";
		    $invamnt = "";
		    $invDate = "";
		    $custId = "";
		    $custName = "";
		    $invRefnum = "";
		    $lineItems = "";
		    $paidamount = "";
		    $id = "";
		   
		  
		   $itm_ = "";
		    for ($i = 0; $i < $cnt; $i++)
		    {
		       if(isset($arr["Invoices"][$i]["InvoiceNumber"]))
		       {
		          $invacc =  $arr["Invoices"][$i]["InvoiceNumber"];
		          $id =  $arr["Invoices"][$i]["InvoiceID"];
		          
		          if ($invacc != '')
		          {
		            if(isset($arr["Invoices"][$i]["Contact"]["ContactID"]))
		               {   
		                   $custId = $arr["Invoices"][$i]["Contact"]["ContactID"];
		                  if(isset($arr["Invoices"][$i]["Date"]))
		                   {
		                       if(isset($arr["Invoices"][$i]["Total"]))
		                         {
		                         		$contactname = $arr["Invoices"][$i]["Contact"]["Name"];
		                                $invamnt =  $arr["Invoices"][$i]["Total"];
		                                $invtaxtype =  $arr["Invoices"][$i]["LineAmountTypes"];
		                                $invDate =  $arr["Invoices"][$i]["Date"];
		                                $invType =  $arr["Invoices"][$i]["Type"];
		                                $invStatus =  $arr["Invoices"][$i]["Status"];
		                                $paidamount =  $arr["Invoices"][$i]["AmountPaid"];
		                                $refrence =  $arr["Invoices"][$i]["Reference"];
		                                $duedate =  $arr["Invoices"][$i]["DueDate"];
		                                $subtotal =  $arr["Invoices"][$i]["SubTotal"];
		                                $totaltax =  $arr["Invoices"][$i]["TotalTax"];
		                                $curcode =  $arr["Invoices"][$i]["CurrencyCode"];
		                                $amountdue =  $arr["Invoices"][$i]["AmountDue"];
		                                $amountcredited =  $arr["Invoices"][$i]["AmountCredited"];

		                                
		                                if($invType == 'ACCREC'){
		                                	
		                                	if($invStatus == "PAID"){
		                                	$output .= '<tr><td style="width:5%;">'.$number.'</td>
                                            <td style="width:22%;">'.$invacc.'</td>
                                            <td style="width:22%;">'.$contactname.'</td>
                                            <td style="width:22%;">'.$totaltax.'.00</td>
                                            <td style="width:22%;"><span>&#8369;</span>'.$invamnt.'.00</td>
                                            <td style="width:22%; display:none;">'.$id.'</td></tr>';
                                            $number++;
		                                	}
		                                
		                                }
		                                

		                          }
		                    
		                   }
		                  
		               }

		          }
		          
		       }
		    }

		    echo $output;
	        
		} catch (Exception $e) {
		    echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
		

	}

//ACCPAY PENDING

	else if($action == "getACCPAY"){
		
		$number = 1;
		$output = "";
		try {
		    $apiResponse = $apiInstance->getInvoices($xeroTenantId, null, null, null, null, null, null, null, null, null, null, null);
		    //echo $apiInstance->getInvoices($xeroTenantId, null, null, null, null, null, null, null, null, null, null, null);
		    $cnt =  count($apiResponse->getInvoices());

		    $arr  = (json_decode($apiResponse, true));
		    $invacc = "";
		    $invamnt = "";
		    $invDate = "";
		    $custId = "";
		    $custName = "";
		    $invRefnum = "";
		    $lineItems = "";
		    $paidamount = "";
		    $id = "";
		   
		  
		   $itm_ = "";
		    for ($i = 0; $i < $cnt; $i++)
		    {
		       if(isset($arr["Invoices"][$i]["InvoiceNumber"]))
		       {
		          $invacc =  $arr["Invoices"][$i]["InvoiceNumber"];
		          $id =  $arr["Invoices"][$i]["InvoiceID"];
		          
		          if ($invacc != '')
		          {
		            if(isset($arr["Invoices"][$i]["Contact"]["ContactID"]))
		               {   
		                   $custId = $arr["Invoices"][$i]["Contact"]["ContactID"];
		                  if(isset($arr["Invoices"][$i]["Date"]))
		                   {
		                       if(isset($arr["Invoices"][$i]["Total"]))
		                         {
		                         		$contactname = $arr["Invoices"][$i]["Contact"]["Name"];
		                                $invamnt =  $arr["Invoices"][$i]["Total"];
		                                $invtaxtype =  $arr["Invoices"][$i]["LineAmountTypes"];
		                                $invDate =  $arr["Invoices"][$i]["Date"];
		                                $invType =  $arr["Invoices"][$i]["Type"];
		                                $invStatus =  $arr["Invoices"][$i]["Status"];
		                                $paidamount =  $arr["Invoices"][$i]["AmountPaid"];
		                                $refrence =  $arr["Invoices"][$i]["Reference"];
		                                $duedate =  $arr["Invoices"][$i]["DueDate"];
		                                $subtotal =  $arr["Invoices"][$i]["SubTotal"];
		                                $totaltax =  $arr["Invoices"][$i]["TotalTax"];
		                                $curcode =  $arr["Invoices"][$i]["CurrencyCode"];
		                                $amountdue =  $arr["Invoices"][$i]["AmountDue"];
		                                $amountcredited =  $arr["Invoices"][$i]["AmountCredited"];

		                                
		                                if($invType == 'ACCPAY'){
		                                	
		                                	if($invStatus == "AUTHORISED"){
		                                	$output .= '<tr><td style="width:5%;">'.$number.'</td>
                                            <td style="width:22%;">'.$invacc.'</td>
                                            <td style="width:22%;">'.$contactname.'</td>
                                            <td style="width:22%;">'.$invStatus.'</td>
                                            <td style="width:22%;"><span>&#8369;</span>'.$invamnt.'.00</td>
                                            <td style="width:22%; display:none;">'.$id.'</td></tr>';
                                            $number++;
		                                	}
		                                
		                                }
		                                

		                          }
		                    
		                   }
		                  
		               }

		          }
		          
		       }
		    }

		    echo $output;
	        
		} catch (Exception $e) {
		    echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
		

	}


//ACCPAY PAID

	else if($action == "getACCPAYPAID"){
		
		$number = 1;
		$output = "";
		try {
		    $apiResponse = $apiInstance->getInvoices($xeroTenantId, null, null, null, null, null, null, null, null, null, null, null);
		    //echo $apiInstance->getInvoices($xeroTenantId, null, null, null, null, null, null, null, null, null, null, null);
		    $cnt =  count($apiResponse->getInvoices());

		    $arr  = (json_decode($apiResponse, true));
		    $invacc = "";
		    $invamnt = "";
		    $invDate = "";
		    $custId = "";
		    $custName = "";
		    $invRefnum = "";
		    $lineItems = "";
		    $paidamount = "";
		    $id = "";
		   
		  
		   $itm_ = "";
		    for ($i = 0; $i < $cnt; $i++)
		    {
		       if(isset($arr["Invoices"][$i]["InvoiceNumber"]))
		       {
		          $invacc =  $arr["Invoices"][$i]["InvoiceNumber"];
		          $id =  $arr["Invoices"][$i]["InvoiceID"];
		          
		          if ($invacc != '')
		          {
		            if(isset($arr["Invoices"][$i]["Contact"]["ContactID"]))
		               {   
		                   $custId = $arr["Invoices"][$i]["Contact"]["ContactID"];
		                  if(isset($arr["Invoices"][$i]["Date"]))
		                   {
		                       if(isset($arr["Invoices"][$i]["Total"]))
		                         {
		                         		$contactname = $arr["Invoices"][$i]["Contact"]["Name"];
		                                $invamnt =  $arr["Invoices"][$i]["Total"];
		                                $invtaxtype =  $arr["Invoices"][$i]["LineAmountTypes"];
		                                $invDate =  $arr["Invoices"][$i]["Date"];
		                                $invType =  $arr["Invoices"][$i]["Type"];
		                                $invStatus =  $arr["Invoices"][$i]["Status"];
		                                $paidamount =  $arr["Invoices"][$i]["AmountPaid"];
		                                $refrence =  $arr["Invoices"][$i]["Reference"];
		                                $duedate =  $arr["Invoices"][$i]["DueDate"];
		                                $subtotal =  $arr["Invoices"][$i]["SubTotal"];
		                                $totaltax =  $arr["Invoices"][$i]["TotalTax"];
		                                $curcode =  $arr["Invoices"][$i]["CurrencyCode"];
		                                $amountdue =  $arr["Invoices"][$i]["AmountDue"];
		                                $amountcredited =  $arr["Invoices"][$i]["AmountCredited"];

		                                
		                                if($invType == 'ACCPAY'){
		                                	
		                                	if($invStatus == "PAID"){
			                                	$output .= '<tr><td style="width:5%;">'.$number.'</td>
	                                            <td style="width:22%;">'.$invacc.'</td>
	                                            <td style="width:22%;">'.$contactname.'</td>
	                                            <td style="width:22%;">'.$totaltax.'.00</td>
	                                            <td style="width:22%;"><span>&#8369;</span>'.$invamnt.'.00</td>
	                                            <td style="width:22%; display:none;">'.$id.'</td></tr>';
	                                            $number++;
		                                	}
		                                
		                                }
		                                

		                          }
		                    
		                   }
		                  
		               }

		          }
		          
		       }
		    }

		    echo $output;
	        
		} catch (Exception $e) {
		    echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
		

	}



	elseif ($action == "getDisbursement") {
		// code...
		$number = 1;
		$output = "";
		$apiResponse = $apiInstance->getInvoices($xeroTenantId, null, null, null, null, null, null, null, null, null, null, null);
		//echo $apiInstance->getInvoices($xeroTenantId, null, null, null, null, null, null, null, null, null, null, null);
		$cnt =  count($apiResponse->getInvoices());
		$arr  = (json_decode($apiResponse, true));
		for ($i = 0; $i < $cnt; $i++)
		    {
		       if(isset($arr["Invoices"][$i]["InvoiceNumber"]))
		       {
		          $invacc =  $arr["Invoices"][$i]["InvoiceNumber"];
		          $id =  $arr["Invoices"][$i]["InvoiceID"];
		          
		          if ($invacc != '')
		          {
		            if(isset($arr["Invoices"][$i]["Contact"]["ContactID"]))
		               {   
		                   $custId = $arr["Invoices"][$i]["Contact"]["ContactID"];
		                  if(isset($arr["Invoices"][$i]["Date"]))
		                   {
		                       if(isset($arr["Invoices"][$i]["Total"]))
		                         {
		                         		$contactname = $arr["Invoices"][$i]["Contact"]["Name"];
		                                $invamnt =  $arr["Invoices"][$i]["Total"];
		                                $invtaxtype =  $arr["Invoices"][$i]["LineAmountTypes"];
		                                $invDate =  $arr["Invoices"][$i]["Date"];
		                                $invType =  $arr["Invoices"][$i]["Type"];
		                                $invStatus =  $arr["Invoices"][$i]["Status"];
		                                $paidamount =  $arr["Invoices"][$i]["AmountPaid"];
		                                $refrence =  $arr["Invoices"][$i]["Reference"];
		                                $duedate =  $arr["Invoices"][$i]["DueDate"];
		                                $subtotal =  $arr["Invoices"][$i]["SubTotal"];
		                                $totaltax =  $arr["Invoices"][$i]["TotalTax"];
		                                $curcode =  $arr["Invoices"][$i]["CurrencyCode"];
		                                $amountdue =  $arr["Invoices"][$i]["AmountDue"];
		                                $amountcredited =  $arr["Invoices"][$i]["AmountCredited"];
		                                $miscamount = 0;
          								$rentamount = 0;
          								$sapaamount = 0;
          								$otacamount = 0;
          								$utilamount = 0;
          								$accpayamount = 0;
		                                if($invType == "ACCPAY" && $invStatus == "PAID"){

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
	                                            <td style="width:8%;">'.$invacc.'</td>
	                                            <td style="width:8%;">'.$invDate.'</td>
	                                            <td style="width:8%;">'.$contactname.'</td>
	                                            <td style="width:7%;">'.$invStatus.'</td>
	                                            
	                                            <td style="width:8%;"><span>&#8369;</span>'.$invamnt.'.00</td>
	                                            <td style="width:8%;"><span>&#8369;</span>'.$totaltax.'.00</td>
	                                            <td style="width:8%;"><span>&#8369;</span>'.$miscamount.'.00</td>
	                                            <td style="width:8%;"><span>&#8369;</span>'.$rentamount.'.00</td>
	                                            <td style="width:8%;"><span>&#8369;</span>'.$sapaamount.'.00</td>
	                                            <td style="width:8%;"><span>&#8369;</span>'.$utilamount.'.00</td>
	                                            <td style="width:8%;"><span>&#8369;</span>'.$otacamount.'.00</td>
	                                            <td style="width:8%;"><span>&#8369;</span>'.$accpayamount.'.00</td>
	                                            <td style="width:8%; display:none;">'.$id.'</td></tr>';
	                                            $number++;

		                                }

          								

		                                	
			                                	
		                                

		                          }
		                    
		                   }
		                  
		               }

		          }
		          
		       }
		    }
		    echo $output;

	}

	//receipts

	else if($action == "getRECEIPTS"){
		
		$number = 1;
		$output = "";
		$vstat = '';
		$jsonToarray = json_decode($apiInstance->getBankTransactions($xeroTenantId,null,null, null, null, null),true);
		$output.= '<pre>'.$apiInstance->getBankTransactions($xeroTenantId,null,null, null, null, null).'</pre>';
		$cnt =  count($jsonToarray["BankTransactions"]);
		$ref = '';
		try {
		    
		  for ($i=0; $i < $cnt; $i++) { 
		  $id =  $jsonToarray["BankTransactions"][$i]["BankTransactionID"];
		  $date = $jsonToarray["BankTransactions"][$i]["Date"];
		  $status = $jsonToarray["BankTransactions"][$i]["Status"];
		  $lineamount = $jsonToarray["BankTransactions"][$i]["LineAmountTypes"];

		  if(isset($jsonToarray["BankTransactions"][$i]["Contact"]["ContactID"]) )
		  {
		   $contact = $jsonToarray["BankTransactions"][$i]["Contact"]["ContactID"];
		   }
		   else
		   {
		    $contact = "";
		  }
		  if(isset($jsonToarray["BankTransactions"][$i]["SubTotal"]) )
		  {
		   $sub = $jsonToarray["BankTransactions"][$i]["SubTotal"];
		  }
		  else
		  {
		    $sub = "";
		  }
		  if(isset($jsonToarray["BankTransactions"][$i]["TotalTax"]) )
		  {
		   $tax = $jsonToarray["BankTransactions"][$i]["TotalTax"];
		  }
		  else
		  {
		    $tax = "";
		  }
		  if(isset($jsonToarray["BankTransactions"][$i]["Total"]) )
		  {
		   $total = $jsonToarray["BankTransactions"][$i]["Total"];
		  }
		  else
		  {
		    $total = "";
		  }

		  if(isset($jsonToarray["BankTransactions"][$i]["Type"]) )
		  {
		   $type = $jsonToarray["BankTransactions"][$i]["Type"];
		  }
		  else
		  {
		    $type = "";
		  }

		  if(isset($jsonToarray["BankTransactions"][$i]["Reference"]) )
		  {
		    $ref = $jsonToarray["BankTransactions"][$i]["Reference"];
		  }
		  else
		  {
		    $ref = "";
		  }

		  if($type=="SPEND"){
		          

		          
		          //inserttobank($contact,$date,$status,$lineamount,$sub,$tax,$total,$id,$type,$ref,$vouchername, $orgid);
		  }else{

		  }


		}

		    echo $output;
	        
		} catch (Exception $e) {
		    echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
		

	}

?>
