<?php
$txCode= rand(0,32000)*rand(0,32000);

//check if membershipDetail is set

    $sagePay = new SagePay();
    $sagePay->setCurrency('GBP');
    $sagePay->setAmount($membership->nextPaymentAmount); // where to get amount of the membership type from?????
    $sagePay->setVendorTxCode($txCode);
    $sagePay->setDescription($membership->schemeName);
    $sagePay->setBillingSurname($contact->keyname);
    $sagePay->setBillingFirstnames($contact->firstName);
    $sagePay->setBillingCity($contact->town);
    $sagePay->setBillingPostCode($contact->postcode);
    $sagePay->setBillingAddress1($contact->address);
    $sagePay->setBillingCountry('GB');
    $sagePay->setDeliverySameAsBilling();
    $sagePay->setSuccessURL('https://nycos.co.uk/nycos-membership-payment?membershipDetailId='.$_REQUEST['membershipDetailId']);
    $sagePay->setFailureURL('https://nycos.co.uk/nycos-membership-payment?membershipDetailId='.$_REQUEST['membershipDetailId']);
    ?>

<div class="mt-3">
    <fieldset>
        <legend>Review Your Membership Payment</legend>
        <table class="autoSummary">
            <tbody>
                <tr>
                    <td class="left">Our reference:</td>
                    <td id="ctl00_cp1_PLEDGEID" class="right">
                        <?= $membership->membershipDetailId ?>
                    </td>
                </tr>
                <tr>
                    <td class="left">Membership description:</td>
                    <td id="ctl00_cp1_DESCRIPTION" class="right">
                        <?= $membership->schemeName ?>
                    </td>
                </tr>
                <tr>
                    <td class="left">Total to pay:</td>
                    <td id="ctl00_cp1_TOTALAMOUNT" class="right">
                      &pound; <?= $membership->nextPaymentAmount ?>
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