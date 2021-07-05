  <?php  
  session_start();
  //API of xero
    ini_set('display_errors', 'On');
    //require __DIR__ . '/vendor/autoload.php';
    require_once('../process/controllers/config/xeroconfig.php');
    require_once('C:\Users\SysDev - PC3\vendor\autoload.php');
    require_once('../process/controllers/storage.php');

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
include("../process/controllers/config/dbconn.php");
  $frmdate = htmlentities($_GET['frmdate']);
  $todate = htmlentities($_GET['todate']);
  $orgid = "";
  if(isset($_SESSION["organisationID"])){
      $orgid = $_SESSION["organisationID"];
      $orgname = $_SESSION["organisationName"];
  }
  $Date = "";
  $payee = "";
  $tin = "";
  $address = "";
  $checkvouch = "";
  $check = "";
  $particular = "";
  $accpay = "";
  $cash = "";
  $sundry = "";

  $frmdate = htmlentities($_GET['frmdate']);
  $todate = htmlentities($_GET['todate']);


  $newFromyr = substr($frmdate,0,-6);
  $newFrommonth = substr($frmdate,5,-3);
  $newFromday = substr($frmdate,8);

  $newToyr = substr($todate,0,-6);
  $newTomonth = substr($todate,5,-3);
  $newToday = substr($todate,8);

  if(isset($_GET['Date'])){
    $Date = $_GET['Date'];
  }else{
    $Date = 0;
  }

  if(isset($_GET['payee'])){
    $payee = $_GET['payee'];
  }else{
    $payee = 0;
  }

  if(isset($_GET['tin'])){
    $tin = $_GET['tin'];
  }else{
    $tin = 0;
  }

  if(isset($_GET['address'])){
    $address = $_GET['address'];
  }else{
    $address = 0;
  }

  if(isset($_GET['checkvouch'])){
    $checkvouch = $_GET['checkvouch'];
  }else{
    $checkvouch = 0;
  }

  if(isset($_GET['check'])){
    $check = $_GET['check'];
  }else{
    $check = 0;
  }

  if(isset($_GET['particular'])){
    $particular = $_GET['particular'];
  }else{
    $particular = 0;
  }

  if(isset($_GET['accpay'])){
    $accpay = $_GET['accpay'];
  }else{
    $accpay = 0;
  }

  if(isset($_GET['cash'])){
    $cash = $_GET['cash'];
  }else{
    $cash = 0;
  }

  if(isset($_GET['sundry'])){
    $sundry = $_GET['sundry'];
  }else{
    $sundry = 0;
  }


  $output = '';
  $output .= '
    <label><b>Company Name: '.$orgname.' </b></label><br>
    <label><b>Cash Disbursement Books</label></b><br>
    <label><b>As of: '.$frmdate.' to '.$todate.'</b></label><br><br>
    <table border = "1"> 
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th>DEBIT</th>
      <th colspan = "2">CREDIT</th>
      <th colspan = "4">SUNDRY</th>

    </table>
   <table border="1">  
                    <tr>  ';

                        if($Date == 1){
                          $output .= '  <th>DATE.</th>  ';
                        }
                        if($payee == 1){
                          $output .= '  <th>PAYEE</th>  ';
                        } 
                        // if($tin == 1){
                        //   $output .= '  <th>TIN</th>  ';
                        // } 
                        if($address == 1){
                          $output .= '  <th>ADDRESS</th>  ';
                        } 
                        if($checkvouch == 1){
                          $output .= '  <th>VOUCHER NO.</th>';
                        } 
                        if($check == 1){
                          $output .= '  <th>CHECK NO.</th>  ';
                        } 
                        if($particular == 1){
                          $output .= '  <th>APV</th>  ';
                        } 
                        if($accpay == 1){
                          $output .= '  <th>ACCOUNTS PAYABLE</th>  ';
                        } 
                        if($cash == 1){
                          $output .= '  <th>CASH IN BANK</th>  ';
                          $output .= '  <th>CASH IN BANK2</th>  ';
                        }
                        if($sundry == 1){
                          $output .= '  <th>ACCOUNT TITLE</th>';
                          $output .= '  <th>DEBIT</th>';
                          $output .= '  <th>CREDIT</th>';
                        } 
                
                   $output .= '   </tr>
    ';


    /*try{
      $where = 'Date >= DateTime('.$newFromyr.','.$newFrommonth.','.$newFromday.',00,00,00) AND Date <= DateTime('.$newToyr.','.$newTomonth.','.$newToday.',00,00,00)';
      $jsonToarray = json_decode($apiInstance->getBankTransactions($xeroTenantId,null,$where, null, null, null),true);
      //echo $apiInstance->getBankTransactions($xeroTenantId,null,$where, null, null, null);
      $cnt =  count($jsonToarray["BankTransactions"]);
      $ref = '';


      for ($i=0; $i < $cnt; $i++) { 
        $id =  $jsonToarray["BankTransactions"][$i]["BankTransactionID"];
        $date = $jsonToarray["BankTransactions"][$i]["Date"];
        $status = $jsonToarray["BankTransactions"][$i]["Status"];
        $type = $jsonToarray["BankTransactions"][$i]["Type"];
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
          if(isset($jsonToarray["BankTransactions"][$i]["Contact"]["Name"])){
            $contactname = $jsonToarray["BankTransactions"][$i]["Contact"]["Name"];
          }else{
            $contactname = "";
          }

          if(isset($jsonToarray["BankTransactions"][$i]["Contact"]["Tax"])){
            $taxnum = $jsonToarray["BankTransactions"][$i]["Contact"]["Tax"];
          }else{
            $taxnum = "";
          }

          $invaddress = "";
          if (isset($jsonToarray["BankTransactions"][$i]["Contact"]["Addresses"]["AddressLine1"])) {
            // code...
            $invaddress = $jsonToarray["BankTransactions"][$i]["Contact"]["Addresses"]["AddressLine1"];
          }else{
            $invaddress = "";
          }

          $bankaccountname = "";
          if (isset($jsonToarray["BankTransactions"][$i]["BankAccount"]["Name"])) {
            // code...
            $bankaccountname = $jsonToarray["BankTransactions"][$i]["BankAccount"]["Name"];
          }else{
            $bankaccountname = "";
          }

          if($type == "SPEND" && $status == "AUTHORISED"){
            $output .= '
              <tr>  ';
              if($Date == 1){
                $output .= '   <td>'.$date.'</td>  ';
              }
              if($payee == 1){
                $output .= '   <td>'.$contactname.'</td>  ';
              } 
              // if($tin == 1){
              //   $output .= '   <td>'.$taxnum.'</td> ';
              // } 
              if($address == 1){
                $output .= '   <td>'.$invaddress.'</td>  ';
              } 
              if($checkvouch == 1){
                $output .= '   <td>Blank</td>  ';
              } 
              if($check == 1){
               $output .= '   <td>'.$ref.'</td>  ';
              } 
              if($particular == 1){
                $output .= '   <td>Blank</td>  ';
              } 
              if($accpay == 1){
                $output .= '   <td span style="text-align: right;">'.$total.'.00</td>  ';
              } 
              if($cash == 1){
                $output .= '   <td span style="text-align: right;">'.$taxnum.'.00</td>';
                $output .= '   <td span style="text-align: right;"></td>';
              } 
              if($sundry == 1){
                $output .= '   <td>'.$bankaccountname.'</td>';
                $output .= '   <td span style="text-align: right;">'.$contactname.'</td>';
                $output .= '   <td span style="text-align: right;">'.$status.'</td>';
              }
                  
             $output .= '    </tr>';
          }

              
      }
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }*/
     $query2 = "select id
              from invoices
              where invoicetype = 'ACCPAY' and company = '".$orgid."' and invoicestatus = 'PAID' and date(invoicedate) between '".$frmdate."' and '".$todate."'";
      $result2 = $conn->query($query2);
      while ($row2 = $result2->fetch_assoc()) 
      { 

        $invoiceid = $row2["id"];
         $query = "call get_disbursement('".$invoiceid."');"; 
        $result = $conn->query($query);
          while ($row = $result->fetch_assoc()) 
          {
              $output .= '
                            <tr>  ';
                            if($Date == 1){
                              $output .= '   <td>'.$row["invoicedate"].'</td>  ';
                            }
                            if($payee == 1){
                              $output .= '   <td>'.$row["payee"].'</td>  ';
                            } 
                            if($tin == 1){
                              $output .= '   <td>'.$row["tin"].'</td> ';
                            } 
                            if($address == 1){
                              $output .= '   <td>'.$row["address"].'</td>  ';
                            } 
                            if($checkvouch == 1){
                              $output .= '   <td>'.$row["checkvouch"].'</td>  ';
                            } 
                            if($check == 1){
                             $output .= '   <td>'.$row["check"].'</td>  ';
                            } 
                            if($particular == 1){
                              $output .= '   <td>'.$row["apv"].'</td>  ';
                            } 
                            if($accpay == 1){
                              $output .= '   <td span style="text-align: right;">'.$row["accpayable"].'</td>  ';
                            } 
                            if($cash == 1){
                              $output .= '   <td span style="text-align: right;">'.$row["cash"].'</td>';
                              $output .= '   <td span style="text-align: right;">'.$row["cash2"].'</td>';
                            } 
                            if($sundry == 1){
                              $output .= '   <td>'.$row["particular2"].'</td>';
                              $output .= '   <td span style="text-align: right;">'.$row["debit"].'</td>';
                              $output .= '   <td span style="text-align: right;">'.$row["credit"].'</td>';
                            }
                                
                           $output .= '    </tr>';
          }
          $conn->close();
          include("../process/controllers/config/dbconn.php");
      }



      $query2 = "SELECT banktransid FROM banktrans where status != 'DELETED' and type = 'SPEND' and company = '$orgid' and date(btdate) between '$frmdate' and '$todate'";
      $result2 = $conn->query($query2);
      while ($row2 = $result2->fetch_assoc()) 
      { 
        $invoiceid = $row2["banktransid"];
         $query = "call get_disbursement2('".$invoiceid."');";
        $result = $conn->query($query);
          while ($row = $result->fetch_assoc()) 
          {
              $output .= '
                            <tr>  ';
                            if($Date == 1){
                              $output .= '   <td>'.$row["invoicedate"].'</td>  ';
                            }
                            if($payee == 1){
                              $output .= '   <td>'.$row["payee"].'</td>  ';
                            } 
                            if($tin == 1){
                              $output .= '   <td>'.$row["tin"].'</td> ';
                            } 
                            if($address == 1){
                              $output .= '   <td>'.$row["address"].'</td>  ';
                            } 
                            if($checkvouch == 1){
                              $output .= '   <td>'.$row["checkvouch"].'</td>  ';
                            } 
                            if($check == 1){
                             $output .= '   <td>'.$row["check"].'</td>  ';
                            } 
                            if($particular == 1){
                              $output .= '   <td>'.$row["apv"].'</td>  ';
                            } 
                            if($accpay == 1){
                              $output .= '   <td span style="text-align: right;">'.$row["accpayable"].'</td>  ';
                            } 
                            if($cash == 1){
                              $output .= '   <td span style="text-align: right;">'.$row["cash"].'</td>';
                              $output .= '   <td span style="text-align: right;">'.$row["cash2"].'</td>';
                            } 
                            if($sundry == 1){
                              $output .= '   <td>'.$row["particular2"].'</td>';
                              $output .= '   <td span style="text-align: right;">'.$row["debit"].'</td>';
                              $output .= '   <td span style="text-align: right;">'.$row["credit"].'</td>';
                            }
                                
                           $output .= '    </tr>';
          }
          $conn->close();
          include("../process/controllers/config/dbconn.php");
      }

      

  $reportTitle = "".$frmdate."-".$todate."";    
  $output .= '</table>';
  header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment; filename='.$orgname.'_Disbursement_'.$reportTitle.'.xls');
      echo $output ;




  ?>