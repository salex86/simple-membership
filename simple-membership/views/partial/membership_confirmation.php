<?php
require_once(SIMPLE_WP_MEMBERSHIP_PATH.'/lib/NycosAPI.php');
$nycosAPI = new NycosAPI();

if($_REQUEST['crypt']){
    $sagePay = new SagePay();
    $responseArray = $sagePay->decode($_REQUEST['crypt']);

    $orderid = $responseArray['VendorTxCode'];
    $amount = $responseArray['Amount'];

    if ($_SESSION["paymentId"] == $orderid){
        $responseMessage = "Payment already made";
    } else {
        $mainContact = new Contact($_SESSION["contactDetails"]);

        SwpmLog::log_simple_debug( 'Payment for new membership made by '.print_r($mainContact,true), true );

        $mainContact = $nycosAPI->postNewContact($mainContact->title,$mainContact->firstName,$mainContact->keyname,
                $mainContact->address,$mainContact->town,$mainContact->county,$mainContact->postcode,$mainContact->country,$mainContact->emailAddress,$mainContact->mobileNumber,
                $mainContact->dayTelephone,$mainContact->eveningTelephone);


        if (empty($contact->serialNumber)){
            $theContact = new Contact( $_SESSION["contactDetails"]);
            if (array_key_exists("Mail",$theContact->consent)){
                $nycosAPI->postContactConsent($mainContact->serialNumber,"Mail","Granted");
            } else {
                $nycosAPI->postContactConsent($mainContact->serialNumber,"Mail","Denied");
            }
            if (array_key_exists("Email",$theContact->consent)){
                $nycosAPI->postContactConsent($mainContact->serialNumber,"Email","Granted");
            } else {
                $nycosAPI->postContactConsent($mainContact->serialNumber,"Email","Denied");
            }
        }

        //Check status of response

        if($responseArray['Status'] === "OK"){
            // Success
            $membership = $nycosAPI->postCreditMembership($mainContact->serialNumber,$_REQUEST['schemename'],$_REQUEST['period']);


            $payment = $nycosAPI->postMemberMembershipPayment($orderid,$amount,$mainContact->serialNumber,$membership->membershipId);

            if ($_REQUEST["giftaid"]=="on"){
                print "create giftaid";
                $nycosAPI->postMemberGiftAid($mainContact->serialNumber);
            }
            $email = new Mailer();
            $email->setNewMembershipMessage($mainContact,$_REQUEST['schemename'],$membership->membershipId,"Credit Card",$amount);
            $email->send($mainContact->emailAddress);
            //print $batchId;
            $_SESSION["paymentId"] = $orderid;
            $responseMessage = "Credit Card membership created";
            $_SESSION["paymentId"] = $orderid;
        }elseif($responseArray['Status'] === "ABORT"){
            // Payment Cancelled

            $responseMessage = "Card Payment Aborted";
        }else{
            // Payment Failed
            $responseMessage = "Card Payment Failed";
        }
    }
} else if ($_REQUEST['accountNumber']){

    //post contact and consents
    $mainContact = new Contact($_SESSION["contactDetails"]);

    $mainContact = $nycosAPI->postNewContact($mainContact->title,$mainContact->firstName,$mainContact->keyname,
            $mainContact->address,$mainContact->town,$mainContact->county,$mainContact->postcode,$mainContact->country,$mainContact->emailAddress,$mainContact->mobileNumber,
            $mainContact->dayTelephone,$mainContact->eveningTelephone);

    $theContact = new Contact( $_SESSION["contactDetails"]);

    if (array_key_exists("Mail",$theContact->consent)){
        $nycosAPI->postContactConsent($mainContact->serialNumber,"Mail","Granted");
    } else {
        $nycosAPI->postContactConsent($mainContact->serialNumber,"Mail","Denied");
    }
    if (array_key_exists("Email",$theContact->consent)){
        $nycosAPI->postContactConsent($mainContact->serialNumber,"Email","Granted");
    } else {
        $nycosAPI->postContactConsent($mainContact->serialNumber,"Email","Denied");
    }
    //Check status of response
    //direct debit so create new membership

    $membership = $nycosAPI->postDebitMembership($mainContact->serialNumber,$_REQUEST['schemename'],$_REQUEST['period'],$_REQUEST['accountName'],$_REQUEST['sortCode'],$_REQUEST['accountNumber'],
        $_REQUEST['bankName'],$_REQUEST['bankAddress'],$_REQUEST['bankPostCode']);
    $responseMessage = "Direct Debit membership created";

    if ($_REQUEST["giftaid"] == "on"){
        print "create giftaid";
        $nycosAPI->postMemberGiftAid($mainContact->serialNumber);
    }

    $email = new Mailer();
    $email->setNewMembershipMessage($mainContact,$_REQUEST['schemename'],$membership->membershipId,"Direct Debit",$amount);
    $email->send($mainContact->emailAddress);
}

?>

<h2 class="font-normal">Confirmation</h2>
<!-- Step 4 confirmation input fields -->
<div class="mt-3">
    <?= $responseMessage ?>
</div>
