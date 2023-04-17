<?php

if($_REQUEST['crypt']){
    $sagePay = new SagePay();
    $responseArray = $sagePay->decode($_REQUEST['crypt']);

    $mainContact = new Contact($_SESSION["contactDetails"]);

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

    $orderid = $responseArray['VendorTxCode'];
    $amount = $responseArray['Amount'];
    //Check status of response
   

    if ($_SESSION["paymentId"] == $orderid){
        $responseMessage = "Donation already made";
    } else {
        if($responseArray['Status'] === "OK"){
            // Success

            $payment = $nycosAPI->postMemberDonationPayment($orderid,$amount,$mainContact->serialNumber,$_REQUEST["dest"]);
            if ($_REQUEST["giftaid"]){
                print "create giftaid";
                $nycosAPI->postMemberGiftAid($mainContact->serialNumber);
            }
            $email = new Mailer();
            $email->setDonationMessage($mainContact->firstName,$mainContact->keyname,$amount,$orderid,false);
            $email->send($mainContact->emailAddress);
            
            //print $batchId;
            $_SESSION["paymentId"] = $orderid;
            $responseMessage = "Thank you for your donation";
        }elseif($responseArray['Status'] === "ABORT"){
            // Payment Cancelled
            $responseMessage = "Donation Payment Aborted";
        }else{
            // Payment Failed
            $responseMessage = "Donation Payment Failed";
        }
    }
}

?>

<h2 class="font-normal">Confirmation</h2>
<!-- Step 3 input fields -->
<div class="mt-3">
    <?=$responseMessage?>
</div>