<?php

class NycosPOST{

	public function __construct() {
		$this->bearer= $this::loginAPI()->accessToken;
	}


	public function loginAPI(){
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/account/signin');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"userName\": \"ed6d78a8-ad30-4d29-aab7-a10cf9603965\", \"password\": \"A_Ce231!6v4V5zM\"}");

		$headers = array();
		$headers[] = 'Accept: text/plain';
		$headers[] = 'X-Api-Key: 91C4A6B7-92A8-4B42-8E78-221E8B64E561';
		$headers[] = 'Content-Type: application/json-patch+json';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);

		return json_decode($result);
	}

	public function postContactConsent($serialNumber,$channel,$status){

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/contactconsent');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"serialNumber\": \"".$serialNumber."\", \"channel\": \"".$channel."\",
		\"status\": \"".$status."\",\"purpose\": \"Events\",\"sourceCode\": \"Web\"}");

		$headers = array();
		$headers[] = 'Accept: text/plain';
		$headers[] = 'X-Api-Key: 91C4A6B7-92A8-4B42-8E78-221E8B64E561';
		$headers[] = 'Authorization: Bearer '.$this->bearer;
		$headers[] = 'Content-Type: application/json-patch+json';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);
		return json_decode($result);
	}

    public function getAPI($action, $data){
		$ch = curl_init();

        $url = 'https://thankqwebapi.accessacloud.com/latest/api/'.$action;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        $headers = array();
        $headers[] = 'Accept: text/plain';
        $headers[] = 'X-Api-Key: 91C4A6B7-92A8-4B42-8E78-221E8B64E561';
        $headers[] = 'Authorization: Bearer '.$this->bearer;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

		//SwpmLog::log_simple_debug("CURL TOKEN::".$this->bearer." CURL DATA::". $data."  CURL GET ACTION::". $getUrl." CURL RESULT::", true);

        return json_decode($result);
    }


    public function postContactPrefAddress($serialNumber,$parentSerial){

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/contactaddresses');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, '{
  "serialNumber": "'.$serialNumber.'",
  "addressId": "'.$parentSerial.'",
  "preferredAddress": true,
  "preferredEmail": true,
  "preferredMobile": true,
  "preferredDayTelephone": true,
  "preferredEveningTelephone": true,
 "preferredFaxNumber": true
}');

		$headers = array();
		$headers[] = 'Accept: text/plain';
		$headers[] = 'X-Api-Key: 91C4A6B7-92A8-4B42-8E78-221E8B64E561';
		$headers[] = 'Authorization: Bearer '.$this->bearer;
		$headers[] = 'Content-Type: application/json-patch+json';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);
		return json_decode($result);
	}


    public function postContactNumbers($serialNumber,$day,$evening,$mobile){

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/contactaddresses');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, '{
  "serialNumber": "'.$serialNumber.'",
  "addressType": "Emergency Contact",
  "dayTelephone": "'.$day.'",
  "eveningTelephone": "'.$evening.'",
  "mobileNumber": "'.$mobile.'"
}');

		$headers = array();
		$headers[] = 'Accept: text/plain';
		$headers[] = 'X-Api-Key: 91C4A6B7-92A8-4B42-8E78-221E8B64E561';
		$headers[] = 'Authorization: Bearer '.$this->bearer;
		$headers[] = 'Content-Type: application/json-patch+json';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);
		return json_decode($result);
	}


	public function postContactProfile($serialNumber,$category,$name,$value){

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/contactprofiles');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, '{
  "serialNumber": "'.$serialNumber.'",
  "category": "'.$category.'",
  "name": "'.$name.'",
  "value": "'.$value.'"
}');

		$headers = array();
		$headers[] = 'Accept: text/plain';
		$headers[] = 'X-Api-Key: 91C4A6B7-92A8-4B42-8E78-221E8B64E561';
		$headers[] = 'Authorization: Bearer '.$this->bearer;
		$headers[] = 'Content-Type: application/json-patch+json';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);
		return json_decode($result);
	}

    public function postMemberProfile($serialNumber,$value,$externalRef){

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/contactprofiles');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, '{
  "serialNumber": "'.$serialNumber.'",
  "category": "Regional Choirs",
  "name": "Payment Plan",
  "value": "'.$value.'",
"externalRef": "'.$externalRef.'",
"externalRefType": "MembershipHeader",
  "useOnlyForExternal": false
}');

		$headers = array();
		$headers[] = 'Accept: text/plain';
		$headers[] = 'X-Api-Key: 91C4A6B7-92A8-4B42-8E78-221E8B64E561';
		$headers[] = 'Authorization: Bearer '.$this->bearer;
		$headers[] = 'Content-Type: application/json-patch+json';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);
		return json_decode($result);
	}

	public function putCreditMembership($detailId,$scheme,$reason,$amount){
		$ch = curl_init();
		$date=date('Y-m-d');
		$firstDayNextMonth = date('Y-m-d', strtotime('first day of next month'));
		//$expiry= date('Y-m-d', strtotime(' + 1 years'));
		//$expiry=date('Y-m-d',strtotime('-1 day', strtotime($expiry)));


		if (empty($amount)){
            $extraString = '  "customFields": [
	{
	   "customFieldName": "RenewalBandId",
      "customFieldValue": "'.$scheme.'"
}]';
        } else {

            $extraString = '"customFields": [
  {      "customFieldName": "RenewalBandId",
      "customFieldValue": "'.$scheme.'"
   },
{
"customFieldName": "RenewalDiscountAmount",
      "customFieldValue": "'.$amount.'"
},
{
    "customFieldName": "DiscountReason",
      "customFieldValue": "'.$reason.'"
}]';
        }

		$curlString = "{ ".$extraString." }";
		
        curl_setopt_array($ch, array(
          CURLOPT_URL => 'https://thankqwebapi.accessacloud.com/latest/api/memberships/'.$detailId,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'PUT',
          CURLOPT_POSTFIELDS =>$curlString));

		$headers = array();
		$headers[] = 'Accept: text/plain';
		$headers[] = 'X-Api-Key: 91C4A6B7-92A8-4B42-8E78-221E8B64E561';
		$headers[] = 'Authorization: Bearer '.$this->bearer;
		$headers[] = 'Content-Type: application/json-patch+json';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);
		return json_decode($result);
	}

    public function postGiftAid($serialNumber){

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/giftaiddeclarations');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"serialNumber\": \"".$serialNumber."\", \"declarationDate\": \"".date('Y-m-d')."\", \"declarationType\": \"Web\", \"sourceCode\": \"Web\"}");

        $headers = array();
        $headers[] = 'Accept: text/plain';
        $headers[] = 'X-Api-Key: 91C4A6B7-92A8-4B42-8E78-221E8B64E561';
        $headers[] = 'Authorization: Bearer '.$this->bearer;
        $headers[] = 'Content-Type: application/json-patch+json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return json_decode($result);
    }
}

// start AJAX code use local library above for oauth
$nycosPost = new NycosPOST();

if (empty($_REQUEST['discountAmount'])) {
    $discountAmount = 0;
} else {
    $discountAmount = $_REQUEST['discountAmount'];
}

if ($_REQUEST['isAccepted'] == "No"){
    $data = $nycosPost->postContactProfile($_REQUEST["serialNumber"],"Regional Choirs",
			"Membership Renewal", "Refused");
	
    exit;
} else {

    if ($_REQUEST['moreChild'] == "Yes" AND $_REQUEST['furtherReduction'] == "No" ){
		
        $data3 = $nycosPost->putCreditMembership($_REQUEST["membershipId"],$_REQUEST["bandName"],
        "Sibling Discount",$discountAmount);
       
    } else {
	
        $data3 = $nycosPost->putCreditMembership($_REQUEST["membershipId"],$_REQUEST["bandName"],
        "Financial Assistance",$discountAmount);
      
    }
	print_r($data3);
	if (stripos($_REQUEST['installments'], "Three")!== false){
		$data = $nycosPost->postMemberProfile($_REQUEST["serialNumber"],"Instalments",$_REQUEST["membershipId"]);
    } else {
        $data = $nycosPost->postMemberProfile($_REQUEST["serialNumber"],"Full",$_REQUEST["membershipId"]);

    }

    if ($_REQUEST['photoPermission']=="Yes"){
        $data = $nycosPost->postContactProfile($_REQUEST["serialNumber"],"Regional Choirs",
       "Photo Permission", "Yes");
    } else {
        $data = $nycosPost->postContactProfile($_REQUEST["serialNumber"],"Regional Choirs",
      "Photo Permission", "No");
    }
    if (!empty($_REQUEST['medicalNotes'])){
        $data = $nycosPost->postContactProfile($_REQUEST["serialNumber"],"Regional Choirs",
           "Medical Notes", $_REQUEST["medicalNotes"]);
    }

    if (!empty($_REQUEST['mailMarketing'])){
        $data = $nycosPost->postContactConsent($_REQUEST["parentSerialNumber"],"Mail", "Granted");
    }
    if (!empty($_REQUEST['emailMarketing'])){
        $data = $nycosPost->postContactConsent($_REQUEST["parentSerialNumber"],"Email", "Granted");
    }


    $pos = strpos($_REQUEST['giftAid'], "Yes");

    if ($pos !== false) {
        $data = $nycosPost->postGiftAid($_REQUEST['parentSerialNumber']);
        echo "in gad";
    }
}

?>