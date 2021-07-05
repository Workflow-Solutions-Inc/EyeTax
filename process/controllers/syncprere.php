<?php 

session_start();
$orgid = "";
if(isset($_SESSION["organisationID"])){
    $orgid = $_SESSION["organisationID"];
}

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

    #$where = 'Date >= DateTime(2021,01,01,00,00,00) AND Date <= DateTime(2021,1,12,00,00,00)';




$jsonToarray = json_decode($apiInstance->getItems($xeroTenantId, $if_modified_since = '2021-01-01', null, $order = null, $unitdp = null),true);
//echo $apiInstance->getItems($xeroTenantId, $if_modified_since = '2020-01-01', null, $order = null, $unitdp = null);
$cnt =  count($jsonToarray["Items"]);
//echo $cnt;
for ($i=0; $i < $cnt; $i++) { 

    $id = "";
    if(isset($jsonToarray["Items"][$i]["ItemID"])){
      $id = $jsonToarray["Items"][$i]["ItemID"];
    }

    $purchaseddetails = "";
    if(isset($jsonToarray["Items"][$i]["PurchaseDetails"]["AccountCode"])){
      $purchaseddetails = $jsonToarray["Items"][$i]["PurchaseDetails"]["AccountCode"];
    }

    $code = "";
    if(isset($jsonToarray["Items"][$i]["Code"])){
      $code = $jsonToarray["Items"][$i]["Code"];
    }
    
    $name = "";
    if(isset($jsonToarray["Items"][$i]["Name"])){
      $name = $jsonToarray["Items"][$i]["Name"];
    }

    $sold = "";
    if(isset($jsonToarray["Items"][$i]["IsSold"])){
      $sold = $jsonToarray["Items"][$i]["IsSold"];
    }

    $purchased = "";
    if(isset($jsonToarray["Items"][$i]["IsPurchased"])){
      $purchased = $jsonToarray["Items"][$i]["IsPurchased"];
    }

    $desc = "";
    if(isset($jsonToarray["Items"][$i]["Description"])){
      $desc = $jsonToarray["Items"][$i]["Description"];
      $desc = str_replace("'", " ", $desc);
    }
    
    $purdesc = "";
    if(isset($jsonToarray["Items"][$i]["PurchaseDescription"])){
      $purdesc = $jsonToarray["Items"][$i]["PurchaseDescription"];
      $purdesc = str_replace("'", " ", $purdesc);
    }
    
    $purchasedprice = 0;
    if(isset($jsonToarray["Items"][$i]["PurchaseDetails"]["UnitPrice"])){
      $purchasedprice = $jsonToarray["Items"][$i]["PurchaseDetails"]["UnitPrice"];
    }  

    $salesdetails = "";
    if(isset($jsonToarray["Items"][$i]["SalesDetails"]["AccountCode"])){
      $salesdetails = $jsonToarray["Items"][$i]["SalesDetails"]["AccountCode"];
    } 

    $salesprice = 0;
    if(isset($jsonToarray["Items"][$i]["SalesDetails"]["UnitPrice"])){
      $salesprice = $jsonToarray["Items"][$i]["SalesDetails"]["UnitPrice"];
    }  

    //echo $purchaseddetails;
    inserttoitems($id,$code,$name,$sold,$purchased,$desc,$purdesc,$purchaseddetails,$salesdetails,$purchasedprice,$salesprice,$orgid);

  }

  function inserttoitems($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9,$param10,$param11, $param12){
    include("config/dbconn.php");
    $id = "";
    $query = "SELECT * FROM items where ID = '".$param1."'";
          $result = $conn->query($query);
          while ($row = $result->fetch_assoc()) 
          { 
            $id = $row["ID"];
          }
          if($id == ""){
            $sql = "INSERT into items values('".$param1."','".$param2."','".$param3."','".$param4."','".$param5."','".$param6."','".$param7."','".$param8."','".$param10."','".$param9."','".$param11."','".$param12."')";
                        if(mysqli_query($conn,$sql))
                        {
                           //echo "New Rec Created";
                        }
                        else
                        {
                          echo "error".$sql."<br>".$conn->error;
                        }
          }else{
            $sql = "UPDATE items SET code = '".$param2."', name = '".$param3."', sold = '".$param4."', purchased = '".$param5."', description = '".$param6."', purchaseddesc = '".$param7."', purchaseddetails = '".$param8."', salesdetails = '".$param9."', purchasedprice = '".$param10."', salesprice = '".$param11."' WHERE ID = '".$param1."'";
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


        $jsonToarray = json_decode($apiInstance->getAccounts($xeroTenantId,null,null,null),true);
        $cnt2 =  count($jsonToarray["Accounts"]);
        //echo $apiInstance->getAccounts($xeroTenantId,null,null,null);
        for ($i=0; $i < $cnt2 ; $i++) { 
                $accid =  $jsonToarray["Accounts"][$i]["AccountID"];
                $acccode =  $jsonToarray["Accounts"][$i]["Code"];
                $accname = $jsonToarray["Accounts"][$i]["Name"];
                $accclass = $jsonToarray["Accounts"][$i]["Class"];
                insertToAccounts($acccode,$accname,$accclass, $accid);
        }

function insertToAccounts($param1,$param2,$param3, $param4){
    include("config/dbconn.php");
                  $chkr = "";
                  $query = "select * from accounts where accountID = '".$param4."'";
                  $result = $conn->query($query);
                  while ($row = $result->fetch_assoc()){
                    $chkr = $row["accountID"];
                  }
                    
                    if($chkr==""){
                        $sql = "INSERT into accounts (accountID ,accountcode, accountname, accountclass) values('".$param4."','".$param1."','".$param2."','".$param3."')";
                                  if(mysqli_query($conn,$sql))
                                  {
                                     //echo "New Rec Created";
                                  }
                                  else
                                  {
                                    echo "error".$sql."<br>".$conn->error;
                                  }
                         
                    }else{
                        $sql = "update accounts set accountname = '".$param2."', accountclass = '".$param3."' where accountcode = '".$param1."'";
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


?>