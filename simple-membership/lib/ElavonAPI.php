<?php

class Order
{
	public string $href;
	public string $id;
	public string $createdAt;
	public string $modifiedAt;
	public string $merchant;
	public Total $total;
	public string $description;
	public array $items;
	public $shipTo;
	public $shopperEmailAddress;
	public $shopperReference;
	public $orderReference;
	public $customReference;
	public $customFields;
}

class Total
{
	public string $amount;
	public string $currencyCode;
}

/**
 * ElavonAPI
 *
 * Class for interaction with the Access CRM API
 *
 * @version 1.0
 * @author Stephen Alexander
 */
class ElavonAPI
{
    public $apiUrl;
    public $merchantId;
    public $privateKey;

    public function __construct(){
        $this->apiUrl = "https://uat.api.converge.eu.elavonaws.com/";
        $this->merchantId = "jdkt2crcpp232ptfr246xvryfy7v";
        $this->privateKey = "sk_9wvfg2kkm48cj43m8877ykwwbf69";
    }

    public function CreatePaymentSession($orderRef){

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->apiUrl.'payment-sessions',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
    "order": "'.$orderRef.'",
    "originUrl": "https://*.riversidetma.com",
  "doCreateTransaction": true,
    "hppType": "lightbox"
}',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'  ),
        ));
        curl_setopt($curl, CURLOPT_USERPWD, $this->merchantId . ":" . $this->privateKey);

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);

    }

    public function CreateOrder($total,$ref){
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->apiUrl.'orders',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
  "total": {
    "amount": "'.$total.'",
    "currencyCode": "GBP"
  },
  "description": "'.$ref.'",
  "orderReference": "'.$ref.'"
}',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json;charset=UTF-8'),
        ));
        curl_setopt($curl, CURLOPT_USERPWD, $this->merchantId . ":" . $this->privateKey);
        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);

    }
}


?>