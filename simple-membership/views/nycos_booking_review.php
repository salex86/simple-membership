<?php
require_once(SIMPLE_WP_MEMBERSHIP_PATH.'/lib/ElavonAPI.php');
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

$elavonAPI = new ElavonAPI();

$order = $elavonAPI->CreateOrder($_REQUEST["amount"],"donationRef")->href;
$session = $elavonAPI->CreatePaymentSession($order);

print_r($session);
?>
<script src="https://uat.hpp.converge.eu.elavonaws.com/client/library.js"></script>
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
                    <td class="left">Balance:</td>
                    <td id="ctl00_cp1_DESCRIPTION" class="right">
                        &pound; <?= $booking->outstanding ?>
                    </td>
                </tr>
                <tr>
                    <td class="left">Currently Paying</td>
                    <td id="ctl00_cp1_TOTALAMOUNT" class="right">
                      &pound; <?= $_REQUEST['amount'] ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <br />

    </fieldset>
</div>

<div class="mt-3">
    <div id="payBox" class="alert alert-warning" role="alert">
        <h4 class="alert-heading">WARNING</h4>
        <button id="payBtn" class="btn btn-primary submit-btn" onclick="onClickHandler();" type="submit">Submit</button>
        <p>Do not leave the processing page until your payment has been confirmed.</p>
    </div>
    <form id="dataForm">
        <input type="hidden" id="serial" value="<?= $contact->serialNumber; ?>" />
        <input type="hidden" id="amount" value="<?= $_REQUEST['amount']; ?>" />
        <input type="hidden" id="dest" value="<?=$_REQUEST["bookingId"]; ?>" />
        <input type="hidden" id="orderid" value="<?= $session->id ?>" />
    </form>

</div>

<script type="text/javascript">
    const MessageTypes = window.ConvergeLightbox.MessageTypes;

    const submitData = (data) => {
      // send data to your server
      console.log(data);
    };

    function confirmPayment() {
        var formdata = new FormData();
        formdata.append("serial", document.getElementById('serial').value);
        formdata.append("orderid", document.getElementById('orderid').value);
        formdata.append("amount", document.getElementById('amount').value);
        formdata.append("bookingId", document.getElementById('bookingId').value);

        var requestOptions = {
          method: 'POST',
          body: formdata,
          redirect: 'follow'
        };

        fetch("wp-content/plugins/simple-membership/ajax/submitEeventPayment.php", requestOptions)
          .then(response => response.text())
          .then(result => console.log(result))
          .catch(error => console.log('error', error));


        let btn = document.getElementById('payBox');
        btn.innerHTML = "Thank you for making your payment <a href='nycos-home' class='btn btn-primary'>Back</a>";
    }

    let lightbox;

    function onClickHandler() {
      // do work to create a sessionId
      const sessionId = '<?= $session->id ?>';
      if (!lightbox) {
        lightbox = new window.ConvergeLightbox({
          sessionId: sessionId,
          onReady: (error) =>
            error
              ? console.error('Lightbox failed to load')
              : lightbox.show(),
          messageHandler: (message, defaultAction) => {
            switch (message.type) {
              case MessageTypes.transactionCreated:
                submitData({
                  sessionId: message.sessionId,
                });
                    confirmPayment();
                break;
              case MessageTypes.hostedCardCreated:
                submitData({
                  convergePaymentToken: message.hostedCard,
                  hostedCard: message.hostedCard,
                  sessionId: message.sessionId,
                });
                break;
            }
            defaultAction();
          },
        });
      } else {
        lightbox.show();
      }
    }
</script>