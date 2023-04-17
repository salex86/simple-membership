<?php
require_once(SIMPLE_WP_MEMBERSHIP_PATH.'/lib/SagePay.php');
require_once(SIMPLE_WP_MEMBERSHIP_PATH.'/lib/NycosAPI.php');
session_start();
$nycosAPI = new NycosAPI();

$auth = SwpmAuth::get_instance();
$user_data = (array) $auth->userData;

extract($user_data, EXTR_SKIP);
$contact = $nycosAPI->getAPI('contacts/'.$extra_info,'');

$txCode= rand(0,32000)*rand(0,32000);

if (empty($_REQUEST["bookingId"])){
    echo "You accesed this page from an unknown location";
    return;
}

$booking = $nycosAPI->getEventBooking($_REQUEST['bookingId']);

$txCode= rand(0,32000)*rand(0,32000);

//check if Booking is set

    $sagePay = new SagePay();
    $sagePay->setCurrency('GBP');
    $sagePay->setAmount($_REQUEST['amount']); // where to get amount of the membership type from?????
    $sagePay->setVendorTxCode($txCode);
    $sagePay->setDescription("Partial payment for" . $booking->eventId);
    $sagePay->setBillingSurname($contact->keyname);
    $sagePay->setBillingFirstnames($contact->firstName);
    $sagePay->setBillingCity($contact->town);
    $sagePay->setBillingPostCode($contact->postcode);
    $sagePay->setBillingAddress1($contact->address);
    $sagePay->setBillingCountry('GB');
    $sagePay->setDeliverySameAsBilling();
    $sagePay->setSuccessURL('https://nycos.co.uk/nycos-booking-payment?bookingId='.$_REQUEST['bookingId']);
    $sagePay->setFailureURL('https://nycos.co.uk/nycos-booking-payment?bookingId='.$_REQUEST['bookingId']);
?>

<div class="mt-3">
    <fieldset>
        <legend>Review Your Booking Payment</legend>
        <table class="autoSummary">
            <tbody>
                <tr>
                    <td class="left">Our reference:</td>
                    <td id="ctl00_cp1_PLEDGEID" class="right">
                        <?= $booking->eventId ?>
                    </td>
                </tr>
                <tr>
                    <td class="left">Left to pay:</td>
                    <td id="ctl00_cp1_DESCRIPTION" class="right">
                        <?= $booking->outstanding ?>
                    </td>
                </tr>
                <tr>
                    <td class="left">Total to pay:</td>
                    <td id="ctl00_cp1_TOTALAMOUNT" class="right">
                      &pound; <?= $booking->invoiced ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <br />

    </fieldset>
</div>

<div class="mt-3">
  
    <form method="POST" id="SagePayForm" action="https://live.sagepay.com/gateway/service/vspform-register.vsp">
        <input type="hidden" name="VPSProtocol" value="3.00" />
        <input type="hidden" name="TxType" value="PAYMENT" />
        <input type="hidden" name="Vendor" value="NYCOS" />
        <input type="hidden" name="Crypt" value="<?php  echo $sagePay->getCrypt(); ?>" />
        <button class="btn btn-primary submit-btn" type="submit">Next</button>
    </form>
</div>