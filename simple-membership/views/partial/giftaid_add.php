<?php
$giftaids = $nycosAPI->getGiftAid($contact->serialNumber)->data;
foreach($giftaids as $key => $giftaid){
    if (($giftaid->effectiveFromDate < date('Y-m-d') and ($giftaid->effectiveToDate > date('Y-m-d'))) or ($giftaid->effectiveFromDate < date('Y-m-d'))  ){

    } else {
        unset($giftaids[$key]);
    }
}

                // check if empty giftaids and show add
                if (empty($giftaids) or empty($contact->serialNumber)) { ?>
<fieldset id="ctl00_cp1_askGAD">
    <legend>Gift Aid</legend>
    <div style="padding-right: 115px">
        <big class="pull-left">Boost your donation by 25p for every &pound;1 you donate at no extra cost to you with Gift Aid.</big>
    </div>
    <img src="<?= SIMPLE_WP_MEMBERSHIP_URL ?>/images/giftaid-logo.png" alt="Gift Aid logo" class="giftAidLogo pull-right" />
    <p class="hidden">
        To Gift Aid your donation, please read the statement below and tick the tickbox:
    </p>
    <p class="clear">
        <small id="ctl00_cp1_GiftAidStatement1_GADTEXT">
            I am a UK taxpayer and understand that if I pay less Income Tax and/or Capital Gains Tax than the amount of Gift Aid claimed on all my donations in that tax year it is my responsibility to pay any difference.<br /><br />Please notify us if you want to cancel this declaration, change your name or home address or no longer pay sufficient tax on your income and/or capital gains tax.
        </small>
    </p><p>
        <strong>
            Your home address is needed, to identify you as a current UK taxpayer.
        </strong>
    </p>
    <p></p>
    <div id="GAD_d" class="form-check">
        <input id="ctl00_cp1_GAD" type="checkbox" name="giftaid" class="form-check-input" data-parsley-multiple="ctl00cp1GAD" /><label for="ctl00_cp1_GAD" class="form-check-label">I want to Gift Aid my donation today and any donations I make in the future or have made in the past 4 years</label>
    </div>
    <small>
        <br />If you pay Income Tax at the higher or additional rate and want to receive the additional tax relief due to you, you must include all your Gift Aid donations on your Self-Assessment tax return or ask HM Revenue and Customs to adjust your tax code.
    </small>
</fieldset>
<?php } else { ?>
<fieldset id="ctl00_cp1_haveGAD">
    <legend>Gift Aid</legend>
    <p> We currently have an active Gift Aid Declaration for you: this donation will be automatically gift-aided. </p>
</fieldset>
<?php } ?>