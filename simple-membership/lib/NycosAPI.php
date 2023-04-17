<?php
require_once(SIMPLE_WP_MEMBERSHIP_PATH.'/lib/class/ContactConsent.class.php');
require_once(SIMPLE_WP_MEMBERSHIP_PATH.'/lib/class/Mailer.class.php');
require_once(SIMPLE_WP_MEMBERSHIP_PATH.'/lib/class/Communication.class.php');
require_once(SIMPLE_WP_MEMBERSHIP_PATH.'/lib/class/Attachment.class.php');
/**
 * NycosAPI
 *
 * Class for interaction with the Access CRM API
 *
 * @version 1.0
 * @author Stephen Alexander
 */
class NycosAPI
{
    public $bearer;
    public $titleArray;
    public $countries;
    public $eventStructures;

    public function __construct() {

		$this->bearer= $this::loginAPI()->accessToken;
        $this->titleArray = array("Mr", "Mrs", "Ms", "Miss", "Dr");
        $this->countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
        $this->eventStructures = array("TRAININGCOURSE","WORKSHOP","SEMINAR","ACTIVITY","ACCOMODATION");

	}

    public function getBearer(){

        return $this->bearer;
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

    public function postAPI($action, $data){
        $ch = curl_init();

		$this->bearer = $_SESSION['token'];
        curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/'.$action);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

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

        $result = json_decode($result);

        return json_decode($result);
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

    public function postMemberDonationPayment($transactionId,$amount,$serialNumber,$destinationCode){
        $batchDate = date('d-M-y', time());
        $batch = $this->getPaymentBatch($batchDate. "-Web%20Donation");

        if (empty($batch)){
            $batch = $this->createBatch($batchDate. "-Web Donation");
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/payments');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"paymentRef\": \"".$transactionId."\", \"paymentBatchId\": \"".$batch->paymentBatchId."\",
\"taxClaimable\": true, \"amount\": ".$amount.", \"date\": \"".date('Y-m-d')."\", \"paymentMethod\": \"Credit Card\",
\"sourceCode\": \"Web\", \"destinationCode\": \"".$destinationCode."\", \"narrative\": \"Donation from Website\",
\"incomeType\": \"DONATION\", \"receiptRequired\": false, \"incomeStream\": \"Personal\", \"serialNumber\": \"".$serialNumber."\" }");

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

    public function postMemberMembershipPayment($transactionId,$amount,$serialNumber,$membership){
        $batchDate = date('d-M-y', time());
        $batch = $this->getPaymentBatch($batchDate. "-Web%20Membership");

        if (empty($batch)){
            $batch = $this->createBatch($batchDate. "-Web Membership");
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/payments');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"paymentRef\": \"".$transactionId."\", \"paymentBatchId\": \"".$batch->paymentBatchId."\",
\"taxClaimable\": true, \"amount\": ".$amount.", \"date\": \"".date('Y-m-d')."\", \"paymentMethod\": \"Credit Card\",
\"sourceCode\": \"Web\", \"narrative\": \"Friends Payment from Website\",
\"incomeType\": \"MEMBERSHIP\", \"externalRef\": \"".$membership."\", \"externalRefType\": \"Membership\", \"receiptRequired\": false,
\"incomeStream\": \"Personal\", \"serialNumber\": \"".$serialNumber."\" }");

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

    public function postEventPayment($transactionId,$amount,$serialNumber,$eventRef){
        $ch = curl_init();
        $batchDate = date('d-M-y', time());
        $batch = $this->getPaymentBatch($batchDate. "-Web%20Event");

        if (empty($batch)){
            $batch = $this->createBatch($batchDate. "-Web Event");
        }

        curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/payments');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"paymentRef\": \"".$transactionId."\", \"paymentBatchId\": \"".$batch->paymentBatchId."\",
\"taxClaimable\": true, \"amount\": ".$amount.", \"date\": \"".date('Y-m-d')."\", \"paymentMethod\": \"Credit Card\",
\"sourceCode\": \"Web\", \"narrative\": \"Event payment from website\",
\"incomeType\": \"EVENT\", \"externalRef\": \"".$eventRef."\", \"externalRefType\": \"EventBooking\", \"receiptRequired\": false,
\"incomeStream\": \"Personal\", \"serialNumber\": \"".$serialNumber."\" }");

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

    public function postCurrentMembershipPayment($transactionId,$amount,$serialNumber,$membership){
        $batchDate = date('d-M-y', time());
        $batch = $this->getPaymentBatch($batchDate. "-Web%20Membership");

        if (empty($batch)){
            $batch = $this->createBatch($batchDate. "-Web Membership");
        }
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/payments');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"paymentRef\": \"".$transactionId."\", \"paymentBatchId\": \"".$batch->paymentBatchId."\",
\"taxClaimable\": true, \"amount\": ".$amount.", \"date\": \"".date('Y-m-d')."\", \"paymentMethod\": \"Credit Card\",
\"sourceCode\": \"Web\", \"narrative\": \"Current Membership Payment from Website\",
\"incomeType\": \"MEMBERSHIP\", \"externalRef\": \"".$membership."\", \"externalRefType\": \"Membership\", \"receiptRequired\": false,
\"incomeStream\": \"Personal\", \"serialNumber\": \"".$serialNumber."\" }");

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

    public function postGiftAid($serialNumber,$address,$town,$city,$county,$country,$postcode){

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/giftaiddeclarations');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"serialNumber\": \"".$serialNumber."\", \"declarationDate\": \"2022-07-28T00:00:00\", \"declarationType\": \"Web\", \"sourceCode\": \"Web\", \"address\": \"".$address."\", \"town\": \"".$town."\", \"county\": \"".$city."\", \"country\": \"".$county."\", \"postcode\": \"".$postcode."\"}");

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

    public function closeMemberGiftAid($serialNumber, $declarationId){

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/giftaiddeclarations');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"serialNumber\": \"".$serialNumber."\", \"declarationId\": \"".$declarationId."\", \"effectiveToDate\": \"".date('Y-m-d')."\"}");

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

    public function postMemberGiftAid($serialNumber){

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

    public function getPayments($serialNumber,$incomeType){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/payments?SerialNumber='.$serialNumber);
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


    public function getGiftAid($serialNumber){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/giftaiddeclarations?SerialNumber='.$serialNumber);
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

    public function getMembership($membershipId){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/memberships/'.$membershipId);
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

    public function getAttendeeType($typeId){

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/events/attendeeType/'.$typeId.'?IncludeCosts=true');
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

    public function getStructure($structureId){

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/events/structure/'.$structureId.'?IncludeCosts=true');
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

    public function getEventBooking($bookingId){

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/eventBooking/'.$bookingId);
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

    public function getEvent($eventId){

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/events/'.$eventId.'?IncludeAttendeeTypes=true&IncludeAccommodation=true&IncludeActivities=true&IncludeSeminars=true&IncludeWorkshops=true&IncludeTrainingCourses=true&IncludeEventCosts=true');
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

    public function getCommunications($serialNumber){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/communications?communicationType=Attachment&ExternalRef='.$serialNumber);
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
        $result = json_decode($result);
        return $result->data;
    }

    public function getAttachments($commId){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/communications/'.$commId.'?IncludeAttachments=true');
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
        $result = json_decode($result);
        return $result->attachments;
    }

    public function getMembershipSchemes(){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/membershipscheme?PublishToWeb=true&IncludeWebPaymentMethods=true');
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

    public function getMembershipScheme($schemeId){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/membershipscheme/'.$schemeId.'?IncludeBands=true');
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

    public function getMemberships($serialNumber){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/memberships?SerialNumber='.$serialNumber);
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

    public function getConsents($serialNumber){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/contactconsent?SerialNumber='.$serialNumber);
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

    public function getChildren($serialNumber){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/relationships?ParentSerialNumber='.$serialNumber);
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


    public function postContact($isNew,$serial,$title,$firstName,$surname,$address,$town,$county,$postcode,$country,$email,$mobile,$dayTel,$eveTel){

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/contacts');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"isNew\": \"".$isNew."\", \"serialNumber\": \"".$serial."\", \"title\": \"".$title."\",
\"firstName\": \"".$firstName."\", \"keyname\": \"".$surname."\", \"addressType\": \"Residential\", \"address\": \"".$address."\",
\"town\": \"".$town."\", \"county\": \"".$county."\", \"country\": \"".$country."\", \"postcode\": \"".$postcode."\",
\"emailAddress\": \"".$email."\", \"mobileNumber\" :\"".$mobile."\", \"dayTelephone\" : \"".$dayTel."\",
\"eveningTelephone\" : \"".$eveTel."\"}");

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

    public function postNewContact($title,$firstName,$surname,$address,$town,$county,$postcode,$country,$email,$mobile,$dayTel,$eveTel,$dob=null,$gender=null,$region=null){

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/contacts');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"title\": \"".$title."\",
\"firstName\": \"".$firstName."\", \"keyname\": \"".$surname."\", \"addressType\": \"Residential\", \"address\": \"".$address."\",
\"town\": \"".$town."\", \"county\": \"".$county."\", \"country\": \"".$country."\", \"postcode\": \"".$postcode."\",
\"emailAddress\": \"".$email."\", \"dateOfBirth\" :\"".$dob."\", \"gender\" :\"".$gender."\",  \"region\" :\"".$region."\", \"mobileNumber\" :\"".$mobile."\", \"dayTelephone\" : \"".$dayTel."\",
\"eveningTelephone\" : \"".$eveTel."\"}");

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

    public function post($data,$url){
        //url-ify the data for the POST
        $fields_string = json_encode($data);

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/'.$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

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


    public function postDebitMembership($serial,$schemeName,$period,$debitName,$sortCode,$accountNumber,$bankName,$bankAddress,$bankPost){
        $ch = curl_init();
        if (date('d') > 20){
            $debitStart = date('Y-m-d', strtotime('first day of next month'));
            $debitStart = date('Y-m-d', strtotime($debitStart. ' + 1 months'));
        } else {
            $debitStart = date('Y-m-d', strtotime('first day of next month'));
        }

        curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/memberships');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"serialNumber\": \"".$serial."\", \"schemeName\": \"".$schemeName."\", \"periodName\": \"Current\",
        \"bandName\": \"Member\", \"sourceCode\": \"Web\", \"receiptSerialNumber\": \"".$serial."\", \"receiptRequired\": true,
        \"startDate\": \"".date('Y-m-d')."\", \"paymentStartDate\": \"".$debitStart."\", \"startDate\": \"".$debitStart."\", \"paymentMethod\": \"Direct Debit\",
        \"paymentFrequency\": \"".$period."\", \"paymentDay\": 1, \"currency\": \"GBP\", \"taxClaimable\": true,
        \"payerSerialNumber\": \"".$serial."\", \"bankName\": \"".$bankName."\", \"bankAddress\": \"".$bankAddress."\",
        \"bankPostCode\": \"".$bankPost."\", \"accountName\": \"".$debitName."\", \"sortCode\": \"".$sortCode."\", \"accountNumber\": \"".$accountNumber."\",
        \"accountVerified\": \"".date('Y-m-d')."\", \"accountVerifiedBy\": \"Website\", \"ddiDateReceived\": \"".date('Y-m-d')."\",
        \"ddiMethod\": \"Internet\"}");

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

    public function postCreditMembership($serial,$schemeName,$period){
        $ch = curl_init();
        $date=date('Y-m-d');
        $firstDayNextMonth = date('Y-m-d', strtotime('first day of next month'));
        $paymentDay = date('d', strtotime($date));
        $expiry= date('Y-m-d', strtotime(' + 1 years'));
        $expiry=date('Y-m-d',strtotime('-1 day', strtotime($expiry)));
        if ($paymentDay>28){ $paymentDay=28;}

        curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/memberships');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"serialNumber\": \"".$serial."\", \"schemeName\": \"".$schemeName."\", \"periodName\": \"Current\",
        \"bandName\": \"Member\", \"sourceCode\": \"Web\", \"receiptSerialNumber\": \"".$serial."\", \"receiptRequired\": true,
        \"startDate\": \"".date('Y-m-d')."\", \"expiryDate\": \"".$expiry."\", \"paymentStartDate\": \"".date('Y-m-d')."\", \"paymentMethod\": \"Credit Card\",
        \"paymentFrequency\": \"Annually\", \"paymentDay\": ".$paymentDay.", \"currency\": \"GBP\", \"taxClaimable\": true,
        \"payerSerialNumber\": \"".$serial."\"}");

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

    public function postCreditMembershipPayment(){

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/payments');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"paymentRef\": \"TRANSACTION ID\", \"paymentBatchId\": \"BATCH ID\", \"amount\": 60, \"date\": \"2022-07-28T00:00:00\", \"paymentMethod\": \"Credit Card\", \"serialNumber\" : \"000001\", \"externalRef\" : \"MEMBERSHIP ID\", \"externalRefType\" : \"Membership\", \"incomeStream\": \"Personal\"}");

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

    public function createBatch($name){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/payments/batch');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"isApproved\": false, \"batchDescription\": \"".$name."\",\"batchType\": \"Quick Batch\", \"currency\": \"GBP\"}");

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

    public function getPaymentBatch($name){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://thankqwebapi.accessacloud.com/latest/api/payments/batch?BatchDescription='.$name.'&isApproved=false');
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
        $result = json_decode($result);
        return $result->data[0];

    }
}

class Workshops
{
	public $structureId;
	public $eventId;
	public $structureType;
	public $description;
	public $capacity;
	public $parentStructureId;
	public $startDate;
	public $startTime;
	public $endDate;
	public $endTime;
	public $placesTaken;
	public $placesRemaining;
	public $notes;
	public $created;
	public $createdBy;
	public $modified;
	public $modifiedBy;
	public $costs;
	public $subStructures;

	public function __construct($data)
	{
        if (is_array($data) || is_object($data))
        {
            // If yes, then foreach() will iterate over it.
            foreach ($data AS $key => $value) $this->{$key} = $value;
            //Do something.
        }

    }
}

class AttendeeTypes
{
	public $attendeeTypeId;
	public $eventId;
	public $attendeeType;
	public $maxAllowed;
	public $allocated;
	public $remaining;
	public $webName;
	public $webAvailableStart;
	public $webAvailableEnd;
	public $placesPerTicket;
	public $memberOnly;
	public $costs;
	public $customFields;

	public function __construct($data)
	{
           if (is_array($data) || is_object($data))
            {
            // If yes, then foreach() will iterate over it.
            foreach ($data AS $key => $value) $this->{$key} = $value;
                //Do something.
            }

    }
}

class TrainingCourses
{
	public $structureId;
	public $eventId;
	public $structureType;
	public $description;
	public $capacity;
	public $parentStructureId;
	public $startDate;
	public $startTime;
	public $endDate;
	public $endTime;
	public $placesTaken;
	public $placesRemaining;
	public $notes;
	public $created;
	public $createdBy;
	public $modified;
	public $modifiedBy;
	public $costs;
	public $subStructures;

	public function __construct($data)
	{
        if (is_array($data) || is_object($data))
        {
            // If yes, then foreach() will iterate over it.
            foreach ($data AS $key => $value) $this->{$key} = $value;
            //Do something.
        }

    }
}

class Activities
{
	public $structureId;
	public $eventId;
	public $structureType;
	public $description;
	public $capacity;
	public $parentStructureId;
	public $startDate;
	public $startTime;
	public $endDate;
	public $endTime;
	public $placesTaken;
	public $placesRemaining;
	public $notes;
	public $created;
	public $createdBy;
	public $modified;
	public $modifiedBy;
	public $costs;
	public $subStructures;

	public function __construct($data)
	{
        if (is_array($data) || is_object($data))
        {
            // If yes, then foreach() will iterate over it.
            foreach ($data AS $key => $value) $this->{$key} = $value;
            //Do something.
        }

    }
}

class Accommodation
{
	public $structureId;
	public $eventId;
	public $structureType;
	public $description;
	public $capacity;
	public $parentStructureId;
	public $startDate;
	public $startTime;
	public $endDate;
	public $endTime;
	public $placesTaken;
	public $placesRemaining;
	public $notes;
	public $created;
	public $createdBy;
	public $modified;
	public $modifiedBy;
	public $costs;
	public $subStructures;

	public function __construct($data)
	{
        if (is_array($data) || is_object($data))
        {
            // If yes, then foreach() will iterate over it.
            foreach ($data AS $key => $value) $this->{$key} = $value;
            //Do something.
        }

    }
}

class Seminars
{
	public $structureId;
	public $eventId;
	public $structureType;
	public $description;
	public $capacity;
	public $parentStructureId;
	public $startDate;
	public $startTime;
	public $endDate;
	public $endTime;
	public $placesTaken;
	public $placesRemaining;
	public $notes;
	public $created;
	public $createdBy;
	public $modified;
	public $modifiedBy;
	public $costs;
	public $subStructures;

	public function __construct($data)
	{
        if (is_array($data) || is_object($data))
        {
            // If yes, then foreach() will iterate over it.
            foreach ($data AS $key => $value) $this->{$key} = $value;
            //Do something.
        }

    }
}

class Events
{
	public $eventId;
	public $eventName;
	public $eventType;
	public $type;
	public $description;
	public $website;
	public $startDate;
	public $startTime;
	public $endDate;
	public $endTime;
	public $maxAllowed;
	public $maxAttendeesPerBooking;
	public $ticketsLeft;
	public $stageId;
	public $stageName;
	public $locationId;
	public $locationName;
	public $locationAddressLine;
	public $locationTown;
	public $locationCounty;
	public $locationPostCode;
	public $locationCountry;
	public $locationDayPhone;
	public $locationFax;
	public $locationEmailAddress;
	public $locationMobileNumber;
	public $mailingPreference;
	public $targetFees;
	public $targetAdmin;
	public $targetDonation;
	public $targetSponsorship;
	public $totalBudget;
	public $publishToWeb;
	public $publishOnWebUntil;
	public $webDescription;
	public $webSourceCode;
	public $displayDietaryRequirements;
	public $hideAttendeeDetails;
	public $publishSeminars;
	public $publishWorkshops;
	public $publishTrainingCourses;
	public $publishActivities;
	public $publishAccommodation;
	public $webBookingFinalUrl;
	public $justGivingEventId;
	public $jgCustomCodeSource;
	public $jgCustomCodeDestination;
	public $jgCustomCodeEventId;
	public $eventNotes;
	public $sourceNotes;
	public $attendeeTypeNotes;
	public $financialNotes;
	public $recordOwner;
	public $created;
	public $createdBy;
	public $modified;
	public $modifiedBy;
	/** @var AttendeeTypes[] */
	public $attendeeTypes;
	public $webPaymentTypes;
	public $accommodation;
	public $activities;
	public $budgets;
	public $seatingPlans;
	public $seminars;
	public $sellingSpace;
	/** @var TrainingCourses[] */
	public $trainingCourses;
	/** @var Workshops[] */
	public $workshops;
	public $keyContacts;
	public $customFields;
	public $eventProfiles;

	/**
     * @param AttendeeTypes[] $attendeeTypes
     * @param TrainingCourses[] $trainingCourses
     * @param Workshops[] $workshops
     */
	public function __construct($data)
	{
        foreach ($data AS $key => $value) $this->{$key} = $value;
    }
}


class Structures {
    public $bookingRef; //String
    public $structureId; //String

}
class AdditionalAttendees {

    public $attendeeType; //String
    public $badgeName; //String
    public $serialNumber; // String
    public $dietaryRequirements; //String
    public $specialNeeds; //String
    public $dateOfBirth;
    public $structures; //array( Structures )

    public function __construct($serialNumber, $attendeeType,$structures,$badgeName,$dob,$needs)
	{
        $this->attendeeType = $attendeeType;
        $this->serialNumber = $serialNumber;
        $this->dateOfBirth= $dob;
        $this->structures = $structures;
        $this->badgeName = $badgeName;
        $this->specialNeeds = $needs;
    }

}
class EventBooking {
    public $eventId; //String
    public $bookingId;
    public $bookingStatus; //String
    public $externalRef; //String
    public $bookingStage; //String
    public $serialNumber; //String
    public $mainAttendeeSerialNumber; //String
    public $sourceCode; //String
    public $attendeeType; //String
    public $subType; //String
    public $paymentType; //String
    public $currency; //String
    public $badgeName; //String
    public $dietaryRequirements; //String
    public $specialNeeds; //String
    public $hasSeatingPreference; //boolean
    public $seatingPreferenceDetails; //String
    public $attendanceStatus; //String
    public $attended; //String
    public $cancellationDate; //Date
    public $dateOfBirth;
    public $structures; //array( Structures )
    public $additionalAttendees; //array( AdditionalAttendees )

    public function __construct()
	{
        $this->sourceCode = "Web";
        $this->bookingStatus = "Booked";
        $this->paymentType = "Credit Card";
        $this->currency = "GBP";
        $this->bookingStage = "Confirmed";

    }

}

class Contact
{
	public $isNew;
	public $serialNumber;
	public $externalRef;
	public $dataHubId;
	public $contactType;
	public $primaryCategory;
	public $alumni;
	public $committee;
	public $volunteer;
	public $funder;
	public $prospect;
	public $title;
	public $firstName;
	public $otherInitial;
	public $keyname;
	public $previousName;
	public $defaultToAnonymous;
	public $postNominal;
	public $addressType;
	public $address;
	public $town;
	public $county;
	public $country;
	public $postcode;
	public $useAddressFrom;
	public $useAddressUntil;
	public $annualSeasonalAddress;
	public $notMailingAddress;
	public $latitude;
	public $longitude;
	public $region;
	public $letterSalutation;
	public $envelopeSalutation;
	public $dayTelephone;
	public $eveningTelephone;
	public $mobileNumber;
	public $emailAddress;
	public $emailAddressValidated;
	public $emailAddressValid;
	public $faxNumber;
	public $gender;
	public $dateOfBirth;
	public $dateOfBirthIsEstimated;
	public $dateOfDeath;
	public $dateOfDeathIsEstimated;
	public $website;
	public $maritalStatus;
	public $occupation;
	public $jobTitle;
	public $charityNumber;
	public $vatNumber;
	public $taxStatus;
	public $socialSecurity;
	public $nationality;
	public $ethnicity;
	public $sourceCode;
	public $origin;
	public $notes;
	public $preferredLanguage;
	public $preferredCommunicationChannel;
	public $preferredEmailFormat;
	public $mailingFrequency;
	public $goneAwayRating;
	public $emailReturnRating;
	public $phoneTpsOverride;
	public $blockPurchases;
	public $blockPurchasesReason;
	public $doNotContact;
	public $doNotContactReason;
	public $doNotContactSourceCode;
	public $doNotContactFrom;
	public $doNotContactUntil;
	public $doNotMail;
	public $doNotMailSourceCode;
	public $doNotEmail;
	public $doNotEmailSourceCode;
	public $doNotPhone;
	public $doNotPhoneSourceCode;
	public $doNotSms;
	public $doNotSmsSourceCode;
	public $doNotFax;
	public $doNotFaxSourceCode;
	public $committeeType;
	public $committeeSubType;
	public $committeeStartDate;
	public $committeeEndDate;
	public $volunteerSourceCode;
	public $volunteerEmergencyName;
	public $volunteerEmergencyNumber;
	public $volunteerSpecialRequirements;
	public $classOf;
	public $classOfElectedByStudent;
	public $funderUrl;
	public $funderDoNotReapply;
	public $funderCsrPolicy;
	public $funderNotes;
	public $funderValueAddedBenefits;
	public $constituency;
	public $devolvedConstituencyName;
	public $wardCode;
	public $wardName;
	public $authority;
	public $created;
	public $createdBy;
	public $modified;
	public $modifiedBy;
    public $consents;

	public function __construct($data)
    {
        foreach ($data AS $key => $value) $this->{$key} = $value;
    }
}