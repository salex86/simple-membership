<?php

 $contact = new Contact( $_SESSION["contactDetails"]);

   $sagePay = new SagePay();
    $sagePay->setCurrency('GBP');
    $sagePay->setAmount($_REQUEST['amount']);
    $sagePay->setVendorTxCode(rand(0,32000)*rand(0,32000));
    $sagePay->setDescription('New Donation');
    $sagePay->setBillingSurname($contact->keyname);
    $sagePay->setBillingFirstnames($contact->firstName);
    if ($contact->town){$sagePay->setBillingCity($contact->town);}
    if ($contact->postcode){$sagePay->setBillingPostCode($contact->postcode); }
    if ($contact->address){$sagePay->setBillingAddress1($contact->address);}
    $sagePay->setBillingCountry('GB');
    $sagePay->setDeliverySameAsBilling();
    $sagePay->setSuccessURL('https://nycos.co.uk/nycos-donation?dest='.$_REQUEST["destinationCode"].'&giftaid='.$_REQUEST['giftaid'].'&nextStep=4');
    $sagePay->setFailureURL('https://nycos.co.uk/nycos-donation/');
?>

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

    <form method="POST" id="SagePayForm" action="https://live.sagepay.com/gateway/service/vspform-register.vsp">
        <input type="hidden" name="VPSProtocol" value="3.00" />
        <input type="hidden" name="TxType" value="PAYMENT" />
        <input type="hidden" name="Vendor" value="NYCOS" />
        <input type="hidden" name="Crypt" value="<?php if ($_REQUEST["amount"]) { echo $sagePay->getCrypt();} ?>" />
        <a href="nycos-donation" id="prevButton" class="btn btn-primary">Back</a>

        <button class="btn btn-primary submit-btn" type="submit">Next</button>
    </form>

</div>