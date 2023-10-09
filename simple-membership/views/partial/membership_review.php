<?php

//create contact if not logged in

if (empty($contact->serialNumber)){

    $contact = new Contact($_SESSION["contactDetails"]);

    $contact = $nycosAPI->postNewContact($contact->title,$contact->firstName,$contact->keyname,
            $contact->address,$contact->town,$contact->county,$contact->postcode,$contact->country,$contact->emailAddress,$contact->mobileNumber,
            $contact->dayTelephone,$contact->eveningTelephone);


    if (empty($contact->serialNumber)){
        $theContact = new Contact( $_SESSION["contactDetails"]);
        if (array_key_exists("Mail",$theContact->consent)){
            $nycosAPI->postContactConsent($contact->serialNumber,"Mail","Granted");
        } else {
            $nycosAPI->postContactConsent($contact->serialNumber,"Mail","Denied");
        }
        if (array_key_exists("Email",$theContact->consent)){
            $nycosAPI->postContactConsent($contact->serialNumber,"Email","Granted");
        } else {
            $nycosAPI->postContactConsent($contact->serialNumber,"Email","Denied");
        }
    }
}

$membership = $nycosAPI->postCreditMembership($contact->serialNumber,$_REQUEST['schemename'],$_REQUEST['period']);



$elavonAPI = new ElavonAPI();

$order = $elavonAPI->CreateOrder($_REQUEST["amount"],$membership->membershipDetailId)->href;
$session = $elavonAPI->CreatePaymentSession($order);

print_r($session);
?>
<script src="https://uat.hpp.converge.eu.elavonaws.com/client/library.js"></script>

<div class="alert alert-primary" role="alert">
    <h4 class="alert-heading">Payment</h4>
    <p>Please allow the confirmation page to appear once you have completed payment.</p>
</div>
<div class="mt-3">
    <fieldset>
        <legend>Review Your Membership Request</legend>
        <br />
        <div id="ctl00_cp1_wholeSummary">
            <table class="autoSummary">
                <tbody>
                    <tr>
                        <td class="left">Membership Type: </td>
                        <td id="type-name" class="right">
                            <?php if ($_REQUEST['schemename']){ print $_REQUEST['schemename']; } ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="left">Membership Cost: </td>
                        <td id="amount" class="right">
                            &pound;<?php if ($_REQUEST['amount']){ print $_REQUEST['amount']; } ?> - <?php if ($_REQUEST['period']){ print $_REQUEST['period']; } ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="left">Payment Method: </td>
                        <td id="amount" class="right">
                            <?php if ($_REQUEST['paymentMethod']){ print $_REQUEST['paymentMethod']; } ?>
                        </td>
                    </tr>
                </tbody>

            </table>

            <input type="hidden" name="giftaid" form="debitForm" value="<?php if ($_REQUEST['giftaid']){ print "on"; } ?>" />
            <input type="hidden" form="debitForm" name="schemename" value="<?php if ($_REQUEST['schemename']){ print $_REQUEST['schemename']; } ?>" />
            <input type="hidden" form="debitForm" name="period" value="<?php if ($_REQUEST['period']){ print $_REQUEST['period']; } ?>" />
            <input type="hidden" form="debitForm" name="amount" value="<?php if ($_REQUEST['amount']){ print $_REQUEST['amount']; } ?>" />
            <input type="hidden" form="debitForm" name="paymentMethod" value="<?php if ($_REQUEST['paymentMethod']){ print $_REQUEST['paymentMethod']; } ?>" />
        </div>
    </fieldset>
</div>

<div class="mt-3">

    <?php if ($_REQUEST["paymentMethod"]=="Direct Debit") { ?>
    <button class="btn btn-primary btn-navigate-form-step" type="button" step_number="4">Next</button>
    <?php } else if ($_REQUEST["paymentMethod"]=="Credit Card") { ?>
    <div id="payBox" class="alert alert-warning" role="alert">
        <h4 class="alert-heading">Payment</h4>
        <button id="payBtn" class="btn btn-primary submit-btn" onclick="onClickHandler();" type="submit">Submit</button>

    </div>
    <form id="dataForm">
        <input type="hidden" id="serial" value="<?= $contact->serialNumber; ?>" />
        <input type="hidden" id="amount" value=" <?= $_REQUEST['amount'] ?>" />
        <input type="hidden" id="membershipId" value="<?= $membership->membershipDetailId; ?>" />
        <input type="hidden" id="orderid" value="<?= $membership->membershipDetailId; ?>" />
    </form>
    <?php } ?>
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
        formdata.append("membershipId", document.getElementById('membershipId').value);

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