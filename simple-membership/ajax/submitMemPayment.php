<?php
    require_once('../lib/NycosAPI.php');
    $nycosAPI = new NycosAPI();

    $payment = $nycosAPI->postCurrentMembershipPayment($_REQUEST['orderid'],$_REQUEST['amount'],$_REQUEST['serial'],$_REQUEST["membershipId"]);

    print_r($payment);
            //$email = new Mailer();
            //$email->setDonationMessage($mainContact->firstName,$mainContact->keyname,$amount,$orderid,false);
            //$email->send($mainContact->emailAddress);
?>