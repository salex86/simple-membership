<?php
    require_once('../lib/NycosAPI.php');
    $nycosAPI = new NycosAPI();

    $payment = $nycosAPI->postEventPayment($_REQUEST['orderid'],$_REQUEST['amount'],$_REQUEST['serial'],$_REQUEST["bookingId"]);

    print_r($payment);
           //$email = new Mailer();
            //$email->setDonationMessage($mainContact->firstName,$mainContact->keyname,$amount,$orderid,false);
            //$email->send($mainContact->emailAddress);
?>