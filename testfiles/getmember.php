<?php

class NycosAPI{

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
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"userName\": \"4361fe19-80c1-4c78-a14a-c681f1ea7092\", \"password\": \"WF$26-6VifY-_wF\"}");

        $headers = array();
        $headers[] = 'Accept: text/plain';
        $headers[] = 'X-Api-Key: 723F1FDA-7813-4738-88AB-E7914CF1A44E';
        $headers[] = 'Content-Type: application/json-patch+json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        return json_decode($result);
    }

    public function getMembershipScheme($schemeId){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/membershipscheme/'.$schemeId.'?IncludeBands=true');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        $headers = array();
        $headers[] = 'Accept: text/plain';
        $headers[] = 'X-Api-Key: 723F1FDA-7813-4738-88AB-E7914CF1A44E';
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


// start AJAX code use local library above for oauth
$nycosAPI = new NycosAPI();
$data = $nycosAPI->getMembershipScheme($_REQUEST["scheme"]);
echo print_r($data);
?>