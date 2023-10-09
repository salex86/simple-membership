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

function GenerateKey($length = 16) {
	$str = "";
	$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
	$max = count($characters) - 1;
	for ($i = 0; $i < $length; $i++) {
		$rand = mt_rand(0, $max);
		$str .= $characters[$rand];
	}
	return $str;
}

$elavonAPI = new ElavonAPI();
$orderId = GenerateKey();
$order = $elavonAPI->CreateOrder($_REQUEST["amount"],$orderId)->href;
$session = $elavonAPI->CreatePaymentSession($order);

print_r($session);
?>
<script src="https://uat.hpp.converge.eu.elavonaws.com/client/library.js"></script>

<h2 class="font-normal">Review</h2>
<!-- Step 3 input fields -->
<div class="mt-3">
    <p>
        Please review all the information your have entered.
Use the <strong>previous page</strong> button if you need to alter any of
these details.
    </p>
    <p>
        If you are happy that all the details are correct, use
the <strong>continue</strong> button.
    </p>
    <p>
        Once you have completed your payment through our secure payment facility, we will repeat all these details on a final confirmation page.
    </p>
    <fieldset>
        <legend>Review Your Donation</legend>

        <hr />
        <h4>
            About Your Donation
        </h4><table class="autoSummary">
            <tbody>
                <tr>
                    <td class="left">destination</td><td class="right">
                        <?php if ($_REQUEST["destinationCode"]) { print $_REQUEST["destinationCode"]; } ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <hr />
        <h4>
            Payment Details
        </h4><table class="autoSummary">
            <tbody>
                <tr>
                    <td class="left">How much would you like to donate?&nbsp;</td><td class="right">
                        <?php if ($_REQUEST["amount"]) { print $_REQUEST["amount"]; } ?>
                    </td>
                </tr>
            </tbody>
        </table><hr /><h4>
            Gift Aid
        </h4><table class="autoSummary">
            <tbody>
                <tr>
                    <td class="left">I want to Gift Aid my donation today and any donations I make in the future or have made in the past 4 years</td><td class="right">
                        <?php ($_REQUEST['giftaid'])? print 'Yes' : print 'No'; ?>
                    </td>
                </tr>
            </tbody>
        </table>
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
        <input type="hidden" id="dest" value="<?= $_REQUEST['destinationCode']; ?>" />
        <input type="hidden" id="orderid" value="<?= $orderId ?>" />
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
        formdata.append("dest", document.getElementById('dest').value);

        var requestOptions = {
          method: 'POST',
          body: formdata,
          redirect: 'follow'
        };

        fetch("wp-content/plugins/simple-membership/ajax/submitDonation.php", requestOptions)
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