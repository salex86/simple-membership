<?php

//print_r($session);

     SwpmLog::log_simple_debug( 'Payment for existing membership review '.print_r($contact,true), true );


     $membershipFees = $nycosAPI->putMembershipGetFees($membership->membershipDetailId);
     //print_r($membershipFees);
     $payments = $nycosAPI->getLookup("NYCOS Payment Schedule");
    
    
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

        <table>
            <thead><tr><th>Date</th><th>Amount</th></tr></thead>
            <tbody>
                <?php

                       //$data2 = $payments;

                       //print_r($payments[0]->lookupValues);
                       $dates = [];
                       print_r($membershipFees->customFields->PutGetFees);
                       foreach($payments[0]->lookupValues as $installment) {
                           //  print_r($installment);
                           if ($installment->lookupValue1 == "Regional") {
                               $dates[] = strtotime($installment->lookupValue2."/".$installment->lookupValue3."/".$installment->lookupValue4);

                           }
                       }
                       $prePayments = $nycosAPI->getPayments($contact->serialNumber,"");
                       $madePayments= [];
                       //print_r($prePayments);
                       foreach($prePayments as $memPayment){
                           if ($memPayment->externalRef == $membership->membershipDetailId){
                               $madePayments[] = $memPayment;
                           }
                       }
                       $remainder=$membershipFees->customFields->PutGetFees % count($dates);
                       $third=floor($membershipFees->customFields->PutGetFees / count($dates));
                       $lastBit=$third+$remainder;

                        $x=0;
                        foreach($dates as $key => $date){
                            $x++;
                            if ($key === array_key_last($dates)) {
                                $value = $lastBit;
                            } else {
                                $value = $third;
                            }
                            if ($x < count($madePayments)){
                                echo '<tr><td><input class="datesCheck" onclick="setAmount()" type="checkbox" checked="checked" readonly data-amount="'.$value.'" />'. date('d/m/Y', $date).'</td><td>'.$value.' - PAID</td></tr>';
                            } else {
                                echo '<tr><td><input class="datesCheck" onclick="setAmount()" type="checkbox" data-amount="'.$value.'" />'. date('d/m/Y', $date).'</td><td>'.$value.'</td></tr>';

                            }
                        }
                ?>

                </tbody>
        </table>
        <br />

    </fieldset>
</div>

<div class="mt-3">
    <div id="payBox" class="alert alert-warning" role="alert">
        <h4 class="alert-heading">Payment</h4>



        <form id="dataForm" action="/NYCOS-Membership-payment">
            <input type="hidden" name="serial" id="serial" value="<?= $contact->serialNumber; ?>" />
            <input type="hidden" name="amount" id="amount" value=" <?= $membership->nextPaymentAmount ?>" />
            <input type="hidden" name="membershipDetailId" id="membershipId" value="<?= $membership->membershipDetailId; ?>" />
            <input type="hidden" name="orderId" id="orderid" value="<?= $membership->membershipDetailId; ?>" />
            <button id="payBtn" class="btn btn-primary submit-btn" type="submit">Make Payment</button>

        </form>
    </div>
</div>

<script type="text/javascript">
    function setAmount() {
        var dates = document.getElementsByClassName("datesCheck");
        var total = 0;
        for (var i = 0; i < dates.length; i++) {
            if (dates.item(i).checked) {
                total = total + Number(dates.item(i).dataset.amount);
            }
        }
        document.getElementById("amount").value = total;
    }
</script>
