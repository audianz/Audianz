<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");

// following files need to be included
require_once("./lib/config_paytm.php");
require_once("./lib/encdec_paytm.php");

$checkSum = "";
$paramList = array();

// Create an array having all required parameters for creating checksum.
$paramList["CHANNEL_ID"] = $_POST["CHANNEL_ID"];
$paramList["CUST_ID"] = $_POST["CUST_ID"];
$paramList["EMAIL"] = $_POST["EMAIL"];
$paramList["INDUSTRY_TYPE_ID"] = $_POST["INDUSTRY_TYPE_ID"];
$paramList["MID"] = $_POST["MID"];
$paramList["MOBILE_NO"] = $_POST["MOBILE_NO"];
$paramList["ORDER_ID"] = $_POST["ORDER_ID"];
$paramList["THEME"] = $_POST["THEME"];
$paramList["TXN_AMOUNT"] = $_POST["TXN_AMOUNT"];
$paramList["WEBSITE"] = $_POST["WEBSITE"];

//Here checksum string will return by getChecksumFromArray() function.
$checkSum = getChecksumFromArray($paramList,PAYTM_MERCHANT_KEY);

 echo json_encode(array("CHECKSUMHASH" => $checkSum,"ORDER_ID" => $paramList["ORDER_ID"], "payt_STATUS" => "1"));

  //Sample response return to SDK
 
//  {"CHECKSUMHASH":"GhAJV057opOCD3KJuVWesQ9pUxMtyUGLPAiIRtkEQXBeSws2hYvxaj7jRn33rTYGRLx2TosFkgReyCslu4OUj\/A85AvNC6E4wUP+CZnrBGM=","ORDER_ID":"asgasfgasfsdfhl7","payt_STATUS":"1"} 
 

?>
