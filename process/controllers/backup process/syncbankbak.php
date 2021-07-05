<?php session_start();
$orgid = "";
if(isset($_SESSION["organisationID"])){
  $orgid = $_SESSION["organisationID"];
}
$frmdate = htmlentities($_GET['frmdate']);
$todate = htmlentities($_GET['todate']);


$newFromyr = substr($frmdate,0,-6);
$newFrommonth = substr($frmdate,5,-3);
$newFromday = substr($frmdate,8);

$newToyr = substr($todate,0,-6);
$newTomonth = substr($todate,5,-3);
$newToday = substr($todate,8);


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



$taxnumval = 0;
$taxnumval_  = 0;
$_taxnumval = 0;
$taxrateval = 0;


$where = 'Date >= DateTime('.$newFromyr.','.$newFrommonth.','.$newFromday.',00,00,00) AND Date <= DateTime('.$newToyr.','.$newTomonth.','.$newToday.',00,00,00)';



$vstat = '';
$jsonToarray = json_decode($apiInstance->getBankTransactions($xeroTenantId,null,$where, null, null, null),true);
//echo $apiInstance->getBankTransactions($xeroTenantId,null,$where, null, null, null);
$cnt =  count($jsonToarray["BankTransactions"]);
$ref = '';


for ($i=0; $i < $cnt; $i++) { 
  $id =  $jsonToarray["BankTransactions"][$i]["BankTransactionID"];
  deletebanktransline($id); 
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

  if($status=="AUTHORISED"){
          $banktransid = $id;
          $jsonToarray2 = json_decode($apiInstance->getBankTransaction($xeroTenantId, $banktransid),true);
          //echo $apiInstance->getBankTransaction($xeroTenantId, $banktransid);
        for ($j=0; $j < count($jsonToarray2["BankTransactions"][0]["LineItems"]); $j++) { 
          $LineItemID =  $jsonToarray2["BankTransactions"][0]["LineItems"][$j]["LineItemID"];
          $Description =  $jsonToarray2["BankTransactions"][0]["LineItems"][$j]["Description"];
          $Quantity =  $jsonToarray2["BankTransactions"][0]["LineItems"][$j]["Quantity"];
          $UnitAmount =  $jsonToarray2["BankTransactions"][0]["LineItems"][$j]["UnitAmount"];
          $AccountCode =  $jsonToarray2["BankTransactions"][0]["LineItems"][$j]["AccountCode"];
          if(isset($jsonToarray2["BankTransactions"][0]["LineItems"][$j]["TaxType"]) )
          {
            $TaxType = $jsonToarray2["BankTransactions"][0]["LineItems"][$j]["TaxType"];
          }
          else
          {
            $TaxType = "";
          }
          if(isset($jsonToarray2["BankTransactions"][0]["LineItems"][$j]["TaxAmount"]) )
          {
            $TaxAmount =$jsonToarray2["BankTransactions"][0]["LineItems"][$j]["TaxAmount"];
          }
          else
          {
            $TaxAmount = "";
          }
          $LineAmount =  $jsonToarray2["BankTransactions"][0]["LineItems"][$j]["LineAmount"];
                //$vouchername = "";


          //echo $UnitAmount; echo "<br>";
          banktransline($id,$LineItemID,$Description,$Quantity,$UnitAmount,$AccountCode,$TaxType,$TaxAmount,$LineAmount,$orgid);
          

        }

          
          inserttobank($contact,$date,$status,$lineamount,$sub,$tax,$total,$id,$type,$ref,$vouchername, $orgid);
  }else{

  }


}

function inserttobank($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9,$param10,$param11, $param12)
{

  $vouchername = '';
  $vnum = 0;
  $bankid = '';
  include("config/dbconn.php");
  $query = "SELECT * FROM  banktrans where banktransid = '".$param8."'";
  $result = $conn->query($query);
  while ($row = $result->fetch_assoc()) 
  { 
    $bankid = $row["banktransid"];
  }
          //echo $bankid,'jonald';
  if($bankid == ''){

    $query = "SELECT * FROM  numbersequence WHERE prefix = '".$param10."'";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) 
    {
      $vouchername = $row["prefix"]."-".$row["format"]."".$row["next"];
      $vnum = $row["next"]+1;
    } 

    if($vouchername = ''){
      $sql = "INSERT into banktrans values('".$param1."','".$param2."','".$param3."','".$param4."','".$param5."','".$param6."','".$param7."','".$param8."','".$param9."','".$param10."','".$vouchername."','".$param12."')";
      if(mysqli_query($conn,$sql))
      {
                           //echo "New Rec Created";
      }
      else
      {
        echo "error".$sql."<br>".$conn->error;
      }
    }else{
      $sql = "INSERT into banktrans values('".$param1."','".$param2."','".$param3."','".$param4."','".$param5."','".$param6."','".$param7."','".$param8."','".$param9."','".$param10."','".$vouchername."','".$param12."')";
      if(mysqli_query($conn,$sql))
      {
                           //echo "New Rec Created";
      }
      else
      {
        echo "error".$sql."<br>".$conn->error;
      }

      $sql = "UPDATE numbersequence SET next = '".$vnum."' WHERE prefix = '".$param10."'";
      if(mysqli_query($conn,$sql))
      {
                             //echo "New Rec Created";
      }
      else
      {
        echo "error".$sql."<br>".$conn->error;
      }
    }            

  }else{
    $sql = "UPDATE banktrans SET contactid = '".$param1."',btdate = '".$param2."', status = '".$param3."', lineamount = '".$param4."', subtotal = '".$param5."', totaltax = '".$param6."', total = '".$param7."', reference = '".$param10."'   WHERE banktransid = '".$param8."'";
    if(mysqli_query($conn,$sql))
    {
                             //echo "New Rec Created";
    }
    else
    {
      echo "error".$sql."<br>".$conn->error;
    }
  }
          
}

function banktransline($banktransid,$LineItemID,$Description,$Quantity,$UnitAmount,$AccountCode,$TaxType,$TaxAmount,$LineAmount,$orgid){
  include("config/dbconn.php");
  $sql = "INSERT into banktransline 
          values('".$banktransid."','".$LineItemID."','".$Description."','".$Quantity."','".$UnitAmount."','".$AccountCode."','".$TaxType."','".$TaxAmount."','".$LineAmount."','".$orgid."')";
                          if(mysqli_query($conn,$sql))
                          {
                             //echo "New Rec Created";
                          }
                          else
                          {
                            echo "error".$sql."<br>".$conn->error;
                          }
}


function deletebanktransline($banktransid){
  include("config/dbconn.php");
  $sql = "DELETE FROM banktransline where banktransid = '$banktransid'";
                          if(mysqli_query($conn,$sql))
                          {
                             //echo "New Rec Created";
                          }
                          else
                          {
                            echo "error".$sql."<br>".$conn->error;
                          }
}

//header('location: itaxUsers/userlogged.html');
header('location: ../../syncxerodata.php');


?>