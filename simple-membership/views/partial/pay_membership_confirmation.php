<?php
require_once(SIMPLE_WP_MEMBERSHIP_PATH.'/lib/ElavonAPI.php');

$elavonAPI = new ElavonAPI();

$order = $elavonAPI->CreateOrder($_REQUEST['amount'],$_REQUEST['membershipDetailId'])->href;
$session = $elavonAPI->CreatePaymentSession($order);

SwpmLog::log_simple_debug( 'Payment for existing membership made by '.print_r($_REQUEST,true), true );

?>
<script src="https://uat.hpp.converge.eu.elavonaws.com/client/library.js"></script>

<h2 class="font-normal">Payment</h2>
<!-- Step 4 confirmation input fields -->
<div class="mt-3">
   <p>Pay <?= $_REQUEST["amount"] ?> </p>
</div>
<div id="payBox" class="alert alert-warning" role="alert">
    <h4 class="alert-heading">Payment</h4>
    <button id="payBtn" class="btn btn-primary submit-btn" onclick="onClickHandler();" type="submit">Submit</button>

</div>
<form id="dataForm">
    <input type="hidden" id="serial" value="<?= $contact->serialNumber; ?>" />
    <input type="hidden" id="amount" value=" <?= $_REQUEST['amount']; ?>" />
    <input type="hidden" id="membershipDetailId" value="<?=  $_REQUEST['membershipDetailId']; ?>" />
    <input type="hidden" id="orderid" value="<?= $_REQUEST['membershipDetailId']; ?>" />
</form>

<a href="/nycos-memberships" id="backHome" class="btn btn-primary">View Memberships</a>


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
        formdata.append("membershipId", document.getElementById('membershipDetailId').value);

        var requestOptions = {
          method: 'POST',
          body: formdata,
          redirect: 'follow'
        };

        fetch("wp-content/plugins/simple-membership/ajax/submitMemPayment.php", requestOptions)
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
