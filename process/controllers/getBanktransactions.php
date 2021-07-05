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
if($action == "getReceive"){

	// get RECEIVABLES ->
	$number = 1;
	$output = "";
  $curpage = 1;
  do {
	$apiResponse =  $apiInstance -> getBankTransactions($xeroTenantId, $if_modified_since = null, $where = 'Type =="RECEIVE" AND Status =="AUTHORISED"', $order = 'Date asc', $page = $curpage, $unitdp = null);
    $cnt =  count($apiResponse->getBankTransactions());
    $arr  = (json_decode($apiResponse, true));

   for ($i=0; $i < $cnt ; $i++) 
   { 
    $bankdate=date_create($arr["BankTransactions"][$i]["Date"]);
   	$output .= '<tr><td style="width:5%;">'.$number.'</td>
				        <td style="width:10%;">'.date_format($bankdate,"Y/m/d").'</td>
                <td style="width:10%;">'.$arr["BankTransactions"][$i]["Reference"].'</td>
                <td style="width:30%;">'.$arr["BankTransactions"][$i]["LineItems"][0]["Description"].'</td>
                <td style="width:15%;">'.$arr["BankTransactions"][$i]["Contact"]["Name"].'</td>
                <td style="width:15%;"><span>&#8369;</span>'.number_format($arr["BankTransactions"][$i]["TotalTax"],2).'</td>
                <td style="width:15%;"><span>&#8369;</span>'.number_format($arr["BankTransactions"][$i]["Total"],2).'</td>
                <td style="width:15%; display:none;">'.$arr["BankTransactions"][$i]["BankTransactionID"].'</td></tr>';
                $number++;
   }
   $curpage++;
    $apiResponse =  $apiInstance -> getBankTransactions($xeroTenantId, $if_modified_since = null, $where = 'Type =="RECEIVE" AND Status =="AUTHORISED"', $order = 'Date asc', $page = $curpage, $unitdp = null);
    $cnt =  count($apiResponse->getBankTransactions());
    if($cnt==0){
      $curpage = 0;
    }
 }while ($curpage!=0);
  
	// <- get RECEIVABLES

   // PAYMENTS

 $apiResponse = $apiInstance->getPayments($xeroTenantId, $if_modified_since = null, 'Status == "AUTHORISED" AND Reference != "WRITEOFF"', $order = null, $page = 1);
    // echo $apiInstance->getPayments($xeroTenantId, $if_modified_since = null,'Status == "AUTHORISED"', $order = null, $page = $curpage );
    $cnt =  count($apiResponse->getPayments());
    $payments  = (json_decode($apiResponse, true));

   for ($i=0; $i < $cnt ; $i++) 
   { 
    $reference = "";
    if(isset($payments['Payments'][$i]['Reference']))
    {
      $reference = $payments['Payments'][$i]['Reference'];
    }
    $paydate=date_create($payments['Payments'][$i]['Date']);
    $output .= '<tr><td style="width:5%;">'.$number.'</td>
                <td style="width:10%;">'.date_format($paydate,"Y/m/d").'</td>
                <td style="width:10%;">'.$reference.'</td>
                <td style="width:30%;">'.date_format($paydate,"Y/m/d").' '.$payments['Payments'][$i]['Invoice']['Contact']['Name'].' '.number_format($payments['Payments'][$i]['Amount'],2).'</td>
                <td style="width:15%;">'.$payments['Payments'][$i]['Invoice']['Contact']['Name'].'</td>
                <td style="width:15%;"><span>&#8369;</span>'.number_format(0,2).'</td>
                <td style="width:15%;"><span>&#8369;</span>'.number_format($payments['Payments'][$i]['Amount'],2).'</td></tr>';
                $number++;
   }
// PAYMENTS
  echo $output;

}



?>