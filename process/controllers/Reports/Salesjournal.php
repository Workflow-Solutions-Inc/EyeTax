<?php  
session_start();
include("../config/dbconn.php");
$frmdate = htmlentities($_GET['frmdate']);
$todate = htmlentities($_GET['todate']);
$newFromyr = substr($frmdate,0,-6);
$newFrommonth = substr($frmdate,5,-3);
$newFromday = substr($frmdate,8);

$newToyr = substr($todate,0,-6);
$newTomonth = substr($todate,5,-3);
$newToday = substr($todate,8);
$where = 'Date >= DateTime('.$newFromyr.','.$newFrommonth.','.$newFromday.',00,00,00) AND Date <= DateTime('.$newToyr.','.$newTomonth.','.$newToday.',00,00,00)';
$orgid = "";
if(isset($_SESSION["organisationID"])){
    $orgid = $_SESSION["organisationID"];
    $orgname = $_SESSION["organisationName"];
}
if(isset($_GET['Date'])){
  $Date = $_GET['Date'];
}else{
  $Date = 0;
}

if(isset($_GET['invno'])){
  $invno = $_GET['invno'];
}else{
  $invno = 0;
}

if(isset($_GET['custname'])){
  $custname = $_GET['custname'];
}else{
  $custname = 0;
}

/*if(isset($_GET['tin'])){
  $tin = $_GET['tin'];
}else{
  $tin = 0;
}*/

if(isset($_GET['address'])){
  $address = $_GET['address'];
}else{
  $address = 0;
}

if(isset($_GET['accrec'])){
  $accrec = $_GET['accrec'];
}else{
  $accrec = 0;
}

if(isset($_GET['exsale'])){
  $exsale = $_GET['exsale'];
}else{
  $exsale = 0;
}

//new
if(isset($_GET['zerosale'])){
  $zerosale = $_GET['zerosale'];
}else{
  $zerosale = 0;
}

if(isset($_GET['vatsale'])){
  $vatsale = $_GET['vatsale'];
}else{
  $vatsale = 0;
}

if(isset($_GET['outvat'])){
  $outvat = $_GET['outvat'];
}else{
  $outvat = 0;
}

 ini_set('display_errors', 'On');
  require_once('../vendor/autoload.php');
  require_once('../config/xeroconfig.php');
  require_once('../storage.php');
  include("../config/dbconn.php");

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

$output = '';
                
 $output .= '
    <label><b>Company Name: '.$orgname.' </b></label><br>
    <label><b>Sales Journal Books</label></b><br>
    <label><b>For the month of: '.$frmdate.' to '.$todate.'</b></label><br><br>
    <table border = "1"> 
      <th></th>
      <th></th>
      <th></th>
      <th>DEBIT</th>
      <th colspan = "4">CREDIT</th>

    </table>
   <table border="1">  
                    <tr>  ';
                         
                      if($Date == 1){
                        $output .= ' <th>DATE</th>  ';
                      }
                      if($invno == 1){
                        $output .= ' <th>INVOICE NO.</th>  ';
                      } 
                      if($custname == 1){
                        $output .= ' <th>CUSTOMER NAME</th>  ';
                      } 
                      // if($tin == 1){
                      //   $output .= ' <th>TIN</th>  ';
                      // } 
                      if($address == 1){
                        $output .= ' <th>CUSTOMER</th>  ';
                      } 
                      if($accrec == 1){
                        $output .= ' <th>ACCOUNTS RECEIVABLE</th>  ';
                      } 
                      if($vatsale == 1){
                       $output .= ' <th>VATABLE SALES</th>  ';
                      } 
                      if($zerosale == 1){
                       $output .= ' <th>ZERO RATED SALES</th>  ';
                      } 
                      if($exsale == 1){
                        $output .= ' <th>EXEMPT SALES</th>';
                      } 
                      if($outvat == 1){
                        $output .= ' <th>OUTPUT VAT</th>  ';
                      } 
                         
  
                         
                   $output .= '  </tr>
  ';
 

    $apiResponse = $apiInstance->getInvoices($xeroTenantId, $if_modified_since = null, $where = 'Type == "ACCREC"', $order = null, $i_ds = null, $invoice_numbers = null, $contact_i_ds = null, $statuses = 'PAID,AUTHORISED', $page = null, $include_archived = null, $created_by_my_app = null, $unitdp = null);
    $cnt =  count($apiResponse->getInvoices());
      $arr  = (json_decode($apiResponse, true));
      for ($i = 0; $i < $cnt; $i++)
    {
       $output .= '
            <tr>  ';
                      if($Date == 1){
                        $output .= ' <td>'.$arr["Invoices"][$i]["Date"].'</td>  ';
                      }
                      if($invno == 1){
                        $output .= ' <td>'.$arr["Invoices"][$i]["InvoiceNumber"].'</td>  ';
                      } 
                      if($custname == 1){
                        $output .= ' <td>'.$arr["Invoices"][$i]["Contact"]["Name"].'</td>  ';
                      } 
                      /*if($tin == 1){
                        $output .= ' <td>'.$row2["tin"].'</td>  ';
                      } 
                      if($address == 1){
                        $output .= ' <td>'.$row2["address"].'</td>  ';
                      } */
                      if($accrec == 1){
                        $output .= ' <td>'.$arr["Invoices"][$i]["Total"].'</td>  ';
                      } 
                      
                      $cnt2 = count($arr["Invoices"][$i]["LineItems"]);
                      $zero = 0;
                      $exempt = 0;
                      $outputvat = 0;
                      $totalvatsale = 0;
                      for ($j=0; $j < $cnt2; $j++) 
                      { 
                        if($arr["Invoices"][$i]["LineItems"][$j]["TaxAmount"] > 0){
                          $outputvat += $arr["Invoices"][$i]["LineItems"][$j]["TaxAmount"];
                          $totalvatsale += $arr["Invoices"][$i]["LineItems"][$j]["LineAmount"] - $arr["Invoices"][$i]["LineItems"][$j]["TaxAmount"];
                        }else{
                          $zero += $arr["Invoices"][$i]["LineItems"][$j]["LineAmount"];
                        }
                      } 
                      if($vatsale == 1){
                        $output .= ' <td>'.$totalvatsale.'</td>';
                        
                      }
                      if($zerosale == 1){ 
                        $output .= ' <td>'.$zero.'</td>';
                      } 
                      if($exsale == 1){
                        $output .= ' <td>0</td>  ';
                      } 
                      if($outvat == 1){
                        $output .= ' <td>'.$outputvat.'</td>';
                      } 
                
               
           $output.=' </tr>
              ';
    }

$reportTitle = "".$frmdate."-".$todate."";    
$output .= '</table>';
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename='.$orgname.'_Sales_Journal_'.$reportTitle.'.xls');
echo $output ;
?>