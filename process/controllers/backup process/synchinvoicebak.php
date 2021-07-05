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
  $apiResponse = $apiInstance->getInvoices($xeroTenantId, null, $where, null, null, null, null, null, null, null, null, null);

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

                                $query = "SELECT id  FROM invoices where id = '".$id."' group by id";
                                $result = $conn->query($query);
                                $row = $result->fetch_assoc();

                                $invid = $row["id"];
                                //echo  $invid;
                                if ($invid == '')
                                    {
                                      $sql = "INSERT INTO invoices (Invoiceid, id,customerId,invoiceamount,invoicedate,amounttype,invoicetype,invoicestatus, paidamount, invoicereference, invoiceduedate, invoicesubtotal, invoicetotaltax, invoicecurcode, invoiceamountdue, invoiceamountcredited, company) 
                                        values ('".$invacc."', '".$id."', '".$custId."','".$invamnt."', '".$invDate."', '".$invtaxtype."','".$invType."','". $invStatus ."','". $paidamount ."','". $refrence ."','". $duedate ."','". $subtotal ."','". $totaltax ."','". $curcode."','". $amountdue ."','". $amountcredited."','". $orgid."')";
                                        if(mysqli_query($conn,$sql))
                                        {
                                          //echo "New Rec Created";
                                        }
                                        else
                                        {
                                          echo "error".$sql."<br>".$conn->error;
                                        }
                                    }
                                else
                                    {
                                         $sql = "
                                                update invoices ivs
                                                left join invoicevoucher iv on
                                                ivs.Invoiceid = iv.Invoiceno
                                                set ivs.Invoiceamount = ".$invamnt." ,ivs.invoicedate = '".$invDate."',iv.InvoiceAmount = ".$invamnt.",ivs.invoicestatus = '".$invStatus."',ivs.paidamount = '".$paidamount."',ivs.invoicereference = '".$refrence."',ivs.invoiceduedate = '".$duedate."',ivs.invoicesubtotal = '".$subtotal."',ivs.invoicetotaltax = '".$totaltax."',ivs.invoicecurcode = '".$curcode."',ivs.invoiceamountdue = '".$amountdue."',ivs.invoiceamountcredited = '".$amountcredited."',ivs.company = '".$orgid."', amounttype = '".$invtaxtype."'

                                          where ivs.id = '".$id."'";
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
                    
                   }
                  
               }

          }
          
       }
    }
$query = "SELECT id  FROM invoices where date(invoicedate) between '".$frmdate."' and '".$todate."' group by id";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) 
    {
         $invid = $row["id"];
         $sql = "DELETE from invoicetrans where invoiceid = '".$invid."' ";
                        if(mysqli_query($conn,$sql))
                        {
                          //echo "New Rec Created";
                        }
                        else
                        {
                          echo "error".$sql."<br>".$conn->error;
                        }
    }




$query = "SELECT id  FROM invoices where date(invoicedate) between '".$frmdate."' and '".$todate."' group by id";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) 
    { 
          $invid = $row["id"];
          $itmResponse = $apiInstance->getInvoice($xeroTenantId,$invid);
          $itm_arr  = (json_decode($itmResponse, true));
          $cnt_itm = 0;
           //echo $itmResponse;
            foreach ($itm_arr["Invoices"][0]["LineItems"] as $value)
            { 
              $cnt_itm ++;
            }
          //echo $cnt_itm;
           for ($k = 0; $k < $cnt_itm; $k++)
            {
               if(isset($itm_arr["Invoices"][0]["LineItems"][$k]["TaxType"]) )
                {

                    $itm_ = $itm_arr["Invoices"][0]["LineItems"][$k]["TaxType"];
                    $itm_name = $itm_arr["Invoices"][0]["LineItems"][$k]["Description"];

                    if (isset($itm_arr["Invoices"][0]["LineItems"][$k]["TaxAmount"]))
                    {
                        $taxrateval_ = $itm_arr["Invoices"][0]["LineItems"][$k]["TaxAmount"];
                        $lineAmountItem = $itm_arr["Invoices"][0]["LineItems"][$k]["LineAmount"];
                        $itm_Acccode = $itm_arr["Invoices"][0]["LineItems"][$k]["AccountCode"];
                        $quantity = $itm_arr["Invoices"][0]["LineItems"][$k]["Quantity"];
                        $desc = $itm_arr["Invoices"][0]["LineItems"][$k]["Description"];
                        $UnitAmount = $itm_arr["Invoices"][0]["LineItems"][$k]["UnitAmount"];

                        $itemcode = "";
                        if(isset($itm_arr["Invoices"][0]["LineItems"][$k]["ItemCode"])){
                           $itemcode = $itm_arr["Invoices"][0]["LineItems"][$k]["ItemCode"];
                        }
                       

                        $itm_nametrim = str_replace(array("'"), '',$itm_name);
                        //echo $lineAmountItem."<br>";
                        $sql = "INSERT into invoicetrans (invoiceid,itemname,taxamount,taxtype,lineamount,accountcode, quantity, description, unitamount, company, itemcode)

                        VALUES ('".$invid."','".$itm_nametrim."',
                              ".$taxrateval_.",
                              '".$itm_."',".$lineAmountItem.",'".$itm_Acccode."','".$quantity."','".$desc."','".$UnitAmount."', '".$orgid."', '".$itemcode."')";
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
               
            }

    }


   




   $query = "SELECT * FROM invoices where invoicetype = 'ACCPAY' and (Invoiceamount-invoiceamountdue) != 0 and company = '".$orgid."' and date(invoicedate) between '".$frmdate."' and '".$todate."'";
            $result = $conn->query($query);
            while ($row = $result->fetch_assoc()) 
            { 
              $invid = $row["id"];
              $invno = $row["Invoiceid"];
              //echo $apiInstance->getInvoice($xeroTenantId,$invo);
              $jsonToarray = json_decode($apiInstance->getInvoice($xeroTenantId,$invno),true);
              $cnt =  count($jsonToarray["Invoices"][0]["Payments"]);
              //echo $cnt;

              for ($i=0; $i < $cnt ; $i++) { 
                $id = $jsonToarray["Invoices"][0]["Payments"][$i]["PaymentID"];
                $ref = $jsonToarray["Invoices"][0]["Payments"][$i]["Reference"];
                $Date = $jsonToarray["Invoices"][0]["Payments"][$i]["Date"];
                $amount = $jsonToarray["Invoices"][0]["Payments"][$i]["Amount"];
                inserttopayments($id,$Date,$ref,$amount,$invno, $orgid, $invid);
                //echo $amount;echo "<br>";
                echo $cnt;
              }
            }


function inserttopayments($param1,$param2,$param3,$param4,$param5, $param6, $param7)
 {
  include("config/dbconn.php");
          $vouchername = '';
          $payid = '';
          $query = "SELECT * FROM  payments where ID = '".$param1."'";
          $result = $conn->query($query);
          while ($row = $result->fetch_assoc()) 
          { 
            $payid = $row["ID"];
          }
          if($payid == ''){

                  $query = "SELECT * FROM  numbersequence WHERE prefix = '".$param3."'";
                  $result = $conn->query($query);
                  while ($row = $result->fetch_assoc()) 
                  { 
                    $vouchername = $row["prefix"]."-".$row["format"]."".$row["next"];
                    $vnum = $row["next"]+1;
                     $sql = "INSERT into payments values('".$param1."','".$param2."','".$param3."','".$param4."','".$param5."','".$vouchername."','".$param6."', '".$param7."')";
                        if(mysqli_query($conn,$sql))
                        {
                           //echo "New Rec Created";
                        }
                        else
                        {
                          echo "error".$sql."<br>".$conn->error;
                        }
                    $sql = "UPDATE numbersequence SET next = '".$vnum."' WHERE prefix = '".$param3."'";
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
                  //update here
              $sql = "UPDATE payments SET paymentDate = '".$param2."', reference = '".$param3."', amount = '".$param4."' WHERE ID = '".$param1."'";
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



 $query = "SELECT customerId  FROM invoices where company = '$orgid' and date(invoicedate) between '".$frmdate."' and '".$todate."' group by customerId";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) 
    { 
          $custIdsql = $row["customerId"];
          
          //$rows[] = $row;
          $sql = "delete from custinfo where customerId = '".$custIdsql."'";
        if(mysqli_query($conn,$sql))
        {
          //echo "New Rec Created";
        }
        else
        {
          echo "error".$sql."<br>".$conn->error;
        }


          $getCustAdd = $apiInstance->getContact($xeroTenantId, $custIdsql); 
        
          $custAdd_ = (json_decode($getCustAdd, true));

          if(isset($custAdd_["Contacts"][0]["Name"]) )
            {
              $custName_ = $custAdd_["Contacts"][0]["Name"];
            }
            else
            {
              $custName_ = "";
            }
            if(isset($custAdd_["Contacts"][0]["FirstName"]) )
            {
              $custName_fname = $custAdd_["Contacts"][0]["FirstName"];
            }
            else
            {
              $custName_fname = "";
            }
            if(isset($custAdd_["Contacts"][0]["LastName"]) )
            {
              $custName_lname = $custAdd_["Contacts"][0]["LastName"];
            }
            else
            {
              $custName_lname = "";
            }
             if(isset($custAdd_["Contacts"][0]["Addresses"][0]["AddressLine1"]) )
            {
              $custAdd_line1 = $custAdd_["Contacts"][0]["Addresses"][0]["AddressLine1"];
            }
            else
            {
              $custAdd_line1 = "";
            }


            if(isset($custAdd_["Contacts"][0]["Addresses"][0]["AddressLine2"]) )
            {
              $custAdd_line2 = $custAdd_["Contacts"][0]["Addresses"][0]["AddressLine2"];
            }
            else
            {
              $custAdd_line2 = "";
            }

            if(isset($custAdd_["Contacts"][0]["Addresses"][0]["AddressLine3"]) )
            {
              $custAdd_line3 = $custAdd_["Contacts"][0]["Addresses"][0]["AddressLine3"];
            }
            else
            {
              $custAdd_line3 = "";
            }

            if(isset($custAdd_["Contacts"][0]["Addresses"][0]["City"]) )
            {
              $custCity = $custAdd_["Contacts"][0]["Addresses"][0]["City"];
            }
            else
            {
              $custCity = "";
            }

            if(isset($custAdd_["Contacts"][0]["Addresses"][0]["Region"]) )
            {
              $custRegion = $custAdd_["Contacts"][0]["Addresses"][0]["Region"];
            }
            else
            {
              $custRegion = "";
            }

            if(isset($custAdd_["Contacts"][0]["Addresses"][0]["PostalCode"]) )
            {
              $custPostalCode = $custAdd_["Contacts"][0]["Addresses"][0]["PostalCode"];
            }
            else
            {
              $custPostalCode = "";
            }

            if(isset($custAdd_["Contacts"][0]["Addresses"][0]["Country"]) )
            {
               $custCountry = $custAdd_["Contacts"][0]["Addresses"][0]["Country"];
            }
            else
            {
              $custCountry = "";
            }

            if(isset($custAdd_["Contacts"][0]["Addresses"][0]["AttentionTo"]) )
            {
               $custAttentionTo = $custAdd_["Contacts"][0]["Addresses"][0]["AttentionTo"];
            }
            else
            {
              $custAttentionTo = "";
            }

            if(isset($custAdd_["Contacts"][0]["TaxNumber"][0]) )
            {
               $custTax = $custAdd_["Contacts"][0]["TaxNumber"];
            }
            else
            {
              $custTax = "";
            }
             $sql = "INSERT INTO custinfo
                          (customerId,customerName,addressline1,addressline2,addressline3,postalid,region,city,attentionto,taxtype,company)

                          VALUES ('".$custIdsql."',
                                  '".$custName_."',
                                  '".$custAdd_line1."',
                                  '".$custAdd_line2."',
                                  '".$custAdd_line3."',
                                  '".$custPostalCode."',
                                  '".$custRegion."',
                                  '".$custCity."',
                                  '".$custAttentionTo."',
                                  '".$custTax."',
                                  '".$orgid."')";
                  if(mysqli_query($conn,$sql))
                  {
                    //echo "New Rec Created";
                  }
                  else
                  {
                    echo "error".$sql."<br>".$conn->error;
                  }


    }

   $invoiceCount_ = 0;
 $voucherCount = '';
 $voucherCount_ = 0;
 $insideCount = 0;
 $plusOne = 21;
    $query = "SELECT i.id,i.invoiceamount FROM invoices i
              where i.id not in 
              (select invoiceno from invoicevoucher where invoiceno = i.id) and i.invoicetype = 'ACCPAY'
              group by i.id";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) 
    { 
         $invoiceCount_ ++;
    }

    $query = "SELECT count(*) + 1  as 'vchrcnt' FROM invoicevoucher";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) 
    { 
         $voucherCount = $row["vchrcnt"];
         $voucherCount_ = (float)$voucherCount;
         # $voucherCount_ = 20;
    }


$formattedNumber = '';
   //echo $invoiceCount_;
    for($voucherCount_; $invoiceCount_ ; $voucherCount_++) 
    {

         $insideCount++;
        
        if ($voucherCount_ > 0)
                {
                  if ($voucherCount_ <= 9)
                    {
                       $formattedNumber = 'APV-00000'."".(float)$voucherCount_;
                    }
                elseif ($voucherCount_ >= 10 && $voucherCount_<= 99)
                    {
                      $formattedNumber = 'APV-0000'."".(float)$voucherCount_;
                    }
                elseif ($voucherCount_ >= 100 && $voucherCount_ <= 999)
                    {
                      $formattedNumber = 'APV-000'."".(float)$voucherCount_;
                    }
                elseif ($voucherCount_ >= 1000 && $voucherCount_ <= 9999)
                    {
                      $formattedNumber = 'APV-00'."".$voucherCount_;
                    }
                elseif ($voucherCount_ >= 10000 && $voucherCount_ <= 99999)
                    {
                      $formattedNumber = 'APV-0'."".$voucherCount_;
                    }
                elseif ($voucherCount_ >= 100000 && $voucherCount_ <= 999999)
                    {
                      $formattedNumber = 'APV-'."".$voucherCount_;
                    }
                }
      
      
        if ($insideCount == $invoiceCount_ + 1)
           {
            break;
           }
        echo $formattedNumber."<br>";

         $sql = "INSERT INTO invoicevoucher
                          (voucherno,company)

                          VALUES ('".$formattedNumber."', '".$orgid."')";
                  if(mysqli_query($conn,$sql))
                  {
                    //echo "New Rec Created";
                  }
                  else
                  {
                    echo "error".$sql."<br>".$conn->error;
                  }

    }

          



 $sql = "CALL SP_GenerateVoucher()";
                  if(mysqli_query($conn,$sql))
                  {
                    //echo "New Rec Created";
                  }
                  else
                  {
                    echo "error".$sql."<br>".$conn->error;
                  }

     $sql_ = "delete from invoices where invoiceid  = 'AP'";
                  if(mysqli_query($conn,$sql_))
                  {
                    //echo "New Rec Created";
                  }
                  else
                  {
                    echo "error".$sql_."<br>".$conn->error;
                  }

//header('location: itaxUsers/userlogged.html');
header('location: ../../syncxerodata.php');

 
?>