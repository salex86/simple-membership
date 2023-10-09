<?php
    require_once('../lib/NycosAPI.php');
    $nycosAPI = new NycosAPI();

    $payment = $nycosAPI->postMemberDonationPayment($_REQUEST['orderid'],$_REQUEST['amount'],$_REQUEST['serial'],$_REQUEST["dest"]);
    if ($_REQUEST["giftaid"]){
        print "create giftaid";
        $nycosAPI->postMemberGiftAid($_REQUEST['serial']);
    }

    print_r($payment);
            //$email = new Mailer();
            //$email->setDonationMessage($mainContact->firstName,$mainContact->keyname,$amount,$orderid,false);
            //$email->send($mainContact->emailAddress);
?>