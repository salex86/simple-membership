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

    public function getMemberships($schemeId){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/memberships?schemeId='.$schemeId.'&IncludeAllRecords=true');
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
        return json_decode($result);
    }

}

$nycosPost = new NycosPOST();
$members = $nycosPost->getMemberships($_REQUEST['schemeID']);

print "<table id='memberTable'><thead><tr><th>Photo Permission</th><th>Firstname</th><th>Surname</th><th>Section</th><th>D.O.B</th><th>Medical Notes</th><th>Parent Title</th><th>Parent First Name</th><th>Parent Surame</th><th>Parent Address</th><th>Parent Tel (Day)</th><th>Parent Tel (Eve)</th><th>Parent Mobile</th><th>Emergency Tel (Day)</th><th>Emergency Tel (Eve)</th><th>Emergency Mobile</th><th>Parent Email</th></tr></thead>";
print "<tbody>";
foreach($members->data as $key => $member){
    if ($member->membershipStatus == "Active" or $member->membershipStatus == "Lapsed"){
        $contact = $nycosPost->getAPI('contacts/'.$member->serialNumber.'?includeProfiles=true&IncludeAddresses=true',"");
        $permission;
        $addressDay;
        $addressEve;
        $addressMobile;
        foreach($contact->profiles as $profile){
            if ($profile->name == "Photo Permission"){
                $permission =$profile->value;
            }
        }
        foreach($contact->addresses as $address){
            if ($address->addressType == "Emergency Contact"){
                  $addressEve =$address->eveningTelephone;
                  $addressDay = $address->dayTelephone;
                  $addressMobile = $address->mobileNumber;
            }
        }

        //print_r($member);
        $parent = $nycosPost->getAPI('contacts/'.$member->payerSerialNumber,'');
        print "<tr><td>".$permission."</td><td>". $contact->firstName."</td><td>". $contact->keyname."</td><td>".$member->bandName."</td><td>".date('d/m/Y',strtotime($contact->dateOfBirth))."</td><td>".$member->customFields->MEDICALNOTES."</td><td>".$parent->title."</td><td>".$parent->firstName."</td><td>".$parent->keyname."</td><td>".$parent->address."</td><td>".$parent->dayTelephone."</td><td>".$parent->eveningTelephone."</td><td>".$parent->mobileNumber."</td><td>".$addressDay."</td><td>".$addressEve."</td><td>".$addressMobile."</td><td>".$parent->emailAddress."</td>";
        print "</tr>";
    }
}

print "</tbody></table>";



?>