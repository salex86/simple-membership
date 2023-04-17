<?php
if($_REQUEST['crypt']){
    $sagePay = new SagePay();
    $responseArray = $sagePay->decode($_REQUEST['crypt']);

    $orderid = $responseArray['VendorTxCode'];
    $amount = $responseArray['Amount'];

    if ($_SESSION["paymentId"] == $orderid){
        $responseMessage = "Payment already made";
    } else {

        if($responseArray['Status'] === "OK"){

            $payment = $nycosAPI->postCurrentMembershipPayment($orderid,$amount,$contact->serialNumber,$membership->membershipId);

            SwpmLog::log_simple_debug( 'Payment for existing membership made by '.print_r($contact,true), true );

            SwpmLog::log_simple_debug( 'Payment for existing membership returns '.print_r($payment,true), true );
            //print $batchId;
            $_SESSION["paymentId"] = $orderid;
            $responseMessage = "Payment for membership successfull";
        }elseif($responseArray['Status'] === "ABORT"){
            // Payment Cancelled
            $responseMessage = "Card Payment Aborted";
        }else{
            // Payment Failed
            $responseMessage = "Card Payment Failed";
        }
    }
}
?>

<h2 class="font-normal">Confirmation</h2>
<!-- Step 4 confirmation input fields -->
<div class="mt-3">
    <?= $responseMessage ?>
</div>