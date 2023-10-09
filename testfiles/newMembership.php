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
}



?>