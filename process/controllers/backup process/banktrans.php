<?php
// Date Parameter ->
session_start();
include_once('controls.php');
$numseqClass = new ControlsClass();
$orgid = "";
if(isset($_SESSION["organisationID"])){
    $orgid = $_SESSION["organisationID"];
}
//$frmdate = htmlentities($_GET['frmdate']);
//$todate = htmlentities($_GET['todate']);

$frmdate = '2021-04-01';
$todate = '2021-06-15';


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
  require_once('C:\Users\SysDev - PC3\vendor\autoload.php');
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

   $apiResponse =  $apiInstance -> getBankTransactions($xeroTenantId, $if_modified_since = null, $where.' AND Status =="AUTHORISED"', $order = null, $page = $curpage, $unitdp = null);
   //echo $apiInstance -> getBankTransactions($xeroTenantId, $if_modified_since = null, $where.' AND Status =="AUTHORISED"', $order = null, $page = $curpage, $unitdp = null);
    $cnt =  count($apiResponse->getBankTransactions());
    $arr  = (json_decode($apiResponse, true));
   $curpage ++;

   for ($i=0; $i < $cnt ; $i++) { 
     // code...
    $xerocontact = "";
    if(isset($arr["BankTransactions"][$i]["Contact"]["ContactID"])){
      $xerocontact = $arr["BankTransactions"][$i]["Contact"]["ContactID"];
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

    $insertbanktransheader = "CALL `insert_banktransaction_header`(
    '".$xerocontact."', 
    '".$arr["BankTransactions"][$i]["Date"]."', 
    '".$arr["BankTransactions"][$i]["Status"]."', 
    '".$xerobank."',
    ".$xerosubtotal.", 
    ".$xerototaltax.", 
    ".$xerototal.", 
    '".$arr["BankTransactions"][$i]["BankTransactionID"]."', 
    '".$arr["BankTransactions"][$i]["Type"]."', 
    '".$xeroreference."', 
    '".$numseqClass->getControlId($xeroreference)."', 
    '".$orgid."');";
        if(mysqli_query($conn,$insertbanktransheader))
        {

        }
        else
        {
          echo "error ".$insertbanktransheader."<br>".$conn->error;
        }

    $cnt2 = count($arr["BankTransactions"][$i]["LineItems"]);
    for ($j=0; $j < $cnt2; $j++) { 
      // code...
      $linedescription = "";
      if(isset($arr["BankTransactions"][$i]["LineItems"]["Description"])){
        $linedescription = $arr["BankTransactions"][$i]["LineItems"][$j]["Description"];
        $linedescription = str_replace("'", "", $linedescription);
      }
      $linetaxtype = "";
      if(isset($arr["BankTransactions"][$i]["LineItems"]["TaxType"])){
        $linetaxtype = $arr["BankTransactions"][$i]["LineItems"][$j]["TaxType"];
      }

      $insertlines = "CALL `insert_banktransaction_lines`(
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
        }

    }

   }

   $curpage++;
    $apiResponse =  $apiInstance -> getBankTransactions($xeroTenantId, $if_modified_since = null, $where.' AND Status =="AUTHORISED"', $order = null, $page = $curpage, $unitdp = null);
    $cnt =  count($apiResponse->getBankTransactions());
    if($cnt==0){
      $curpage = 0;
    }


  }while($curpage!=0);
?>