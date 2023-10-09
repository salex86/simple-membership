<?php
require_once(SIMPLE_WP_MEMBERSHIP_PATH.'/lib/SagePay.php');
require_once(SIMPLE_WP_MEMBERSHIP_PATH.'/lib/NycosAPI.php');
session_start();
$nycosAPI = new NycosAPI();

$auth = SwpmAuth::get_instance();
$user_data = (array) $auth->userData;

extract($user_data, EXTR_SKIP);
$contact = $nycosAPI->getAPI('contacts/'.$extra_info,'');

if (empty($_REQUEST["bookingId"])){
    echo "You accesed this page from an unknown location";
    return;
}
if (empty($contact->serialNumber)){
    echo "you returned to this page in error";
    return;
}
if (empty($_SESSION["payStart"])){
    print "There has been an error or you have refreshed the page in error. Please contact NYCOS, quoting this message BookErr01";
    return;
}

$booking = $nycosAPI->getEventBooking($_REQUEST['bookingId']);


if($_REQUEST['crypt']){
    $sagePay = new SagePay();
    $responseArray = $sagePay->decode($_REQUEST['crypt']);

    $orderid = $responseArray['VendorTxCode'];
    $amount = $responseArray['Amount'];

    if ($_SESSION["paymentId"] == $orderid){
        $responseMessage = "Payment already made";
    } else {

        if($responseArray['Status'] === "OK"){

            $payment = $nycosAPI->postEventPayment($orderid,$amount,$contact->serialNumber,$_REQUEST['bookingId']);

            SwpmLog::log_simple_debug( 'Payment for existing Booking '.$_REQUEST['bookingId'].' made by '.print_r($contact,true), true );

            SwpmLog::log_simple_debug( 'Payment for existing Booking returns '.print_r($payment,true), true );
            //print $batchId;
            $_SESSION["paymentId"] = $orderid;
            $responseMessage = "Thank you for your booking, an email with confirmation will be on its way to you. You can find details of this booking in the My Bookings page. ";
        }elseif($responseArray['Status'] === "ABORT"){
            // Payment Cancelled
            $responseMessage = "Card Payment Aborted";
        }else{
            // Payment Failed
            $responseMessage = "Card Payment Failed";
        }
    }
}
$_SESSION["payStart"] = "";
?>

<h2 class="font-normal">Confirmation</h2>
<!-- Step 4 confirmation input fields -->
<div class="mt-3">
    <?= $responseMessage ?>
</div>
<a href="/nycos-bookings" id="backHome" class="btn btn-primary">View Bookings</a>
