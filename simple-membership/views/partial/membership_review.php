<?php

if ($_REQUEST['paymentMethod']=="Credit Card"){
    $contact = new Contact( $_SESSION["contactDetails"]);

    $sagePay = new SagePay();
    $sagePay->setCurrency('GBP');
    $sagePay->setAmount($_REQUEST['amount']);
    $sagePay->setVendorTxCode($txCode);
    $sagePay->setDescription('New Membership');
    $sagePay->setBillingSurname($contact->keyname);
    $sagePay->setBillingFirstnames($contact->firstName);
    $sagePay->setBillingCity($contact->town);
    $sagePay->setBillingPostCode($contact->postcode);
    $sagePay->setBillingAddress1($contact->address);
    $sagePay->setBillingCountry('GB');
    $sagePay->setDeliverySameAsBilling();
    $sagePay->setSuccessURL('https://nycos.co.uk/nycos-new-membership/?dest='.$_REQUEST["destinationCode"].'&schemename='.urlencode($_REQUEST["schemename"]).'&giftaid='.$_REQUEST['giftaid'].'&nextStep=5');
    $sagePay->setFailureURL('https://nycos.co.uk/nycos-new-membership//');
}
?>

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
    <form method="POST" id="SagePayForm" action="https://live.sagepay.com/gateway/service/vspform-register.vsp">
        <button type="submit" onclick="window.location.reload(true);" value="previous" id="prevButton" class="btn btn-primary">Back</button>

        <input type="hidden" name="VPSProtocol" value="3.00" />
        <input type="hidden" name="TxType" value="PAYMENT" />
        <input type="hidden" name="Vendor" value="NYCOS" />
        <input type="hidden" name="Crypt" value="<?php if ($_REQUEST["paymentMethod"]) { echo $sagePay->getCrypt();} ?>" />
        <button class="btn btn-primary submit-btn" type="submit">Next</button>
    </form>
    <?php } ?>
</div>