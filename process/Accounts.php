<?php
	
	//API of xero
	  ini_set('display_errors', 'On');
  	  require __DIR__ . '/controllers/vendor/autoload.php';
	  require_once('controllers/config/xeroconfig.php');
	  //require_once('C:\Users\SysDev - PC3\vendor\autoload.php');
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


	if($action == "getAccounts"){
		
		try {
		    $number = 1;
			$accountoutput = "";
			$jsonToarray = json_decode($apiInstance->getAccounts($xeroTenantId,null,null,null),true);
	        $cnt2 =  count($jsonToarray["Accounts"]);
	        //echo $apiInstance->getAccounts($xeroTenantId,null,null,null);
	        for ($i=0; $i < $cnt2 ; $i++) { 

	        		//account ID
	                if(isset($jsonToarray["Accounts"][$i]["AccountID"])){
	                	$accid =  $jsonToarray["Accounts"][$i]["AccountID"];
	                }else{
	                	$accid =  "";
	                }

	                //account code
	                if(isset($jsonToarray["Accounts"][$i]["Code"])){
	                	$acccode =  $jsonToarray["Accounts"][$i]["Code"];
	                }else{
	                	$acccode =  "";
	                }

	                //account name
	                if(isset($jsonToarray["Accounts"][$i]["Name"])){
	                	$accname =  $jsonToarray["Accounts"][$i]["Name"];
	                }else{
	                	$accname =  "";
	                }

	                //account class
	                if(isset($jsonToarray["Accounts"][$i]["Class"])){
	                	$accclass =  $jsonToarray["Accounts"][$i]["Class"];
	                }else{
	                	$accclass =  "";
	                }

	                if($accclass == "LIABILITY" || $accclass == "ASSET"){
	                	$accountoutput .= '<tr><td style="width:25%;">'.$number.'</td>
	                                       <td style="width:25%;">'.$acccode.'</td>
	                                       <td style="width:25%;">'.$accname.'</td>
	                                       <td style="width:25%;">'.$accclass.'</td></tr>';
	                    $number++;
	                }
	                
	                //insertToAccounts($acccode,$accname,$accclass, $accid);
	        }
	        echo $accountoutput;
	        
		} catch (Exception $e) {
		    echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
		

	}





?>
