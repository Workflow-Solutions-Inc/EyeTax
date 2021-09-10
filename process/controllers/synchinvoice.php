<?php
// Date Parameter ->
session_start();
include_once('controls.php');
$numseqClass = new ControlsClass();
$orgid = "";
if(isset($_SESSION["organisationID"])){
    $orgid = $_SESSION["organisationID"];
}
$frmdate = $_GET['frmdate'];
$todate = $_GET['todate'];
// $frmdate = '2021-06-28';
// $todate = '2021-06-30';



$newFromyr = substr($frmdate,0,-6);
$newFrommonth = substr($frmdate,5,-3);
$newFromday = substr($frmdate,8);

$newToyr = substr($todate,0,-6);
$newTomonth = substr($todate,5,-3);
$newToday = substr($todate,8);
$where = 'Date >= DateTime('.$newFromyr.','.$newFrommonth.','.$newFromday.',00,00,00) AND Date <= DateTime('.$newToyr.','.$newTomonth.','.$newToday.',00,00,00)';
// <- Date Parameter

// API ->
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

  $curpage = 1;
  do {
    // <- API

    $apiResponse = $apiInstance->getInvoices($xeroTenantId, $if_modified_since = null, $where, $order = null, $i_ds = null, $invoice_numbers = null, $contact_i_ds = null, $statuses = 'AUTHORISED,PAID', $page = $curpage, $include_archived = null, $created_by_my_app = null, $unitdp = null);
   // echo '<pre>'.$apiInstance->getInvoices($xeroTenantId, $if_modified_since = null, $where, $order = null, $i_ds = null, $invoice_numbers = null, $contact_i_ds = null, $statuses = 'AUTHORISED,PAID', $page = $curpage, $include_archived = null, $created_by_my_app = null, $unitdp = null).'</pre>';

    $cnt =  count($apiResponse->getInvoices());
    $arr  = (json_decode($apiResponse, true));

    $ref = "";
    $due = "";
    for ($i = 0; $i < $cnt; $i++)
    {
      if(isset($arr["Invoices"][$i]["Reference"])){
        
        $ref = $arr["Invoices"][$i]["Reference"];
      }else{
        $ref = "";
      }
      if(isset($arr["Invoices"][$i]["DueDate"])){
        $due = $arr["Invoices"][$i]["DueDate"];
        
      }else{
        $due = "";
      }
      $inserttoinvoices = "CALL `insert_to_invoices`('".$arr["Invoices"][$i]["InvoiceNumber"]."', 
      '".$arr["Invoices"][$i]["InvoiceID"]."', 
      '".$arr["Invoices"][$i]["Contact"]['ContactID']."', 
      '".$arr["Invoices"][$i]["Date"]."', 
      '".$arr["Invoices"][$i]["LineAmountTypes"]."', 
      ".$arr["Invoices"][$i]["Total"].", 
      '".$arr["Invoices"][$i]["Type"]."', 
      '".$arr["Invoices"][$i]["Status"]."', 
      '".$ref."', 
      ".$arr["Invoices"][$i]["AmountPaid"].", 
      '".$due."', 
      ".$arr["Invoices"][$i]["SubTotal"].", 
      ".$arr["Invoices"][$i]["TotalTax"].", 
      '".$arr["Invoices"][$i]["CurrencyCode"]."', 
      ".$arr["Invoices"][$i]["AmountDue"].", 
      ".$arr["Invoices"][$i]["AmountCredited"].", 
      '".$orgid."');";
      if(mysqli_query($conn,$inserttoinvoices))
      {

      }
      else
      {
        //echo "error ".$inserttoinvoices."<br>".$conn->error;
      }

     $cnt2 = count($arr["Invoices"][$i]["LineItems"]);
     for ($j=0; $j < $cnt2; $j++) { 
      //echo $arr["Invoices"][$i]["LineItems"][$j]["LineAmount"];
      $inserttolines = "CALL `insert_to_invoiceslines`(
      '".$arr["Invoices"][$i]["InvoiceID"]."', 
      '".$arr["Invoices"][$i]["LineItems"][$j]["Description"]."', 
      ".$arr["Invoices"][$i]["LineItems"][$j]["TaxAmount"].", 
      '".$arr["Invoices"][$i]["LineItems"][$j]["TaxType"]."', 
      ".$arr["Invoices"][$i]["LineItems"][$j]["LineAmount"].", 
      '".$arr["Invoices"][$i]["LineItems"][$j]["AccountCode"]."', 
      '', 
      ".$arr["Invoices"][$i]["LineItems"][$j]["Quantity"].", 
      '".$arr["Invoices"][$i]["LineItems"][$j]["Description"]."', 
      ".$arr["Invoices"][$i]["LineItems"][$j]["UnitAmount"].", 
      '".$orgid."');
  ";
      if(mysqli_query($conn,$inserttolines))
      {

      }
      else
      {
        echo "error ".$inserttolines."<br>".$conn->error;
      }

     }

     // generate voucher ->
      if($arr["Invoices"][$i]["Type"] == 'ACCPAY'){
        $insertvoucher = "CALL `insert_invoice_voucher`(
        '".$arr["Invoices"][$i]["InvoiceID"]."', 
        '".$numseqClass->getControlId('APV')."', 
        '".$orgid."');";
        if(mysqli_query($conn,$insertvoucher))
        {

        }
        else
        {
          //echo "error ".$inserttolines."<br>".$conn->error;
        }



      }
      // <- generate voucher


    }
    $curpage++;
    $apiResponse = $apiInstance->getInvoices($xeroTenantId, $if_modified_since = null, $where, $order = null, $i_ds = null, $invoice_numbers = null, $contact_i_ds = null, $statuses = 'AUTHORISED,PAID', $page = $curpage, $include_archived = null, $created_by_my_app = null, $unitdp = null);
    $cnt =  count($apiResponse->getInvoices());
    if($cnt == 0){
      $curpage = 0;
    }
  } while ($curpage != 0);
  

  // payments
  
    $apiResponse = $apiInstance->getPayments($xeroTenantId, $if_modified_since = null, $where.' AND Status == "AUTHORISED" AND Reference != "WRITEOFF"', $order = null);
   // echo $apiInstance->getPayments($xeroTenantId, $if_modified_since = null, $where.' AND Status == "AUTHORISED"', $order = null, $page = $curpage );
    $cnt =  count($apiResponse->getPayments());
    $payments  = (json_decode($apiResponse, true));
    $curpage ++;

    


    for ($i=0; $i < $cnt ; $i++) { 
      $ref = "";
      $newref = "";
      if (isset($payments['Payments'][$i]['Reference'])){
        $ref = $payments['Payments'][$i]['Reference'];
        if(strpos($ref, '|') !== false){
          $explodestring = explode('|', str_replace(' ', '', $ref));
          $newref = $explodestring[1];
        }else{
          $newref = $payments['Payments'][$i]['Reference'];
        }   
      }
      //echo $payments['Payments'][$i]['Account']['Code'];
      $insertpayment = "CALL`insert_to_payments`(
      '".$payments['Payments'][$i]['Invoice']['InvoiceID']."', 
      '".$payments['Payments'][$i]['PaymentID']."', 
      '".$payments['Payments'][$i]['Date']."', 
      '".$ref."', 
      ".$payments['Payments'][$i]['Amount'].", 
      '".$numseqClass->getControlId($newref)."', 
      '".$payments['Payments'][$i]['Account']['Code']."', 
      '".$orgid."');";
      if(mysqli_query($conn,$insertpayment))
      {

      }
      else
      {
        echo "error ".$insertpayment."<br>".$conn->error;
      }
     
    }
echo 1;
//header('location: ../../syncxerodata.php');
?>