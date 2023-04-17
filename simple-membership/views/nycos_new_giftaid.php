<?php
require_once(SIMPLE_WP_MEMBERSHIP_PATH.'/lib/NycosAPI.php');
$nycosAPI = new NycosAPI();

$auth = SwpmAuth::get_instance();
$user_data = (array) $auth->userData;
$user_data['membership_level_alias'] = $auth->get('alias');
extract($user_data, EXTR_SKIP);
$contact = $nycosAPI->getAPI('contacts/'.$extra_info,'');

if ($_REQUEST["action"] == "add"){
    $nycosAPI->postMemberGiftAid($extra_info);
}
?>

<main class="site-main" id="main" role="main">
    <h5 class="section-title">
        Support Us

    </h5>
    <h1>Gift Aid Declaration</h1>
    <fieldset>
        <legend>Your Contact Details</legend>
        <div class="contactDetailConfirmation" style="padding:10px;">
            <div class="contactDetailConfirmationStatement">
                We currently hold the following contact information for you:
            </div>
            <div class="contactDetailConfirmationDetail" style="margin:10px;font-weight:bold;font-size:1.1em;">
                <?= $contact->title ?><?= $contact->firstName ?><?= $contact->keyname ?><br /><?= $contact->address ?>
            </div>
            <div class="contactDetailConfirmationCorrect" style="margin-top:10px;">
                If these are incorrect,
please <a href="/nycos-profile" title="Visit the contact details page">click here</a> to correct them.
            </div>
        </div>
    </fieldset>
    <p id="ctl00_cp1_GADBtns"></p>
    <fieldset>
        <legend>Your Gift Aid Declaration</legend>
        <p>
            <small id="text">
                I am a UK taxpayer and understand that if I pay less Income Tax and/or Capital Gains Tax than the amount of Gift Aid claimed on all my donations in that tax year it is my responsibility to pay any difference.<br /><br />Please notify us if you want to cancel this declaration, change your name or home address or no longer pay sufficient tax on your income and/or capital gains tax.
            </small>
        </p>
        <p>
            <strong>
                Your home address is needed, to identify you as a current UK taxpayer.
            </strong>
        </p>
        <p></p>
    </fieldset>
    <p> I want to Gift Aid any donations I make in the future or have made in the past 4 years. </p>
    <div class="softDiv"></div>
    <p></p>
    <form name="userAccountSetupForm" action="/nycos-giftaid" enctype="multipart/form-data" method="POST">
        <input type="hidden" name="action" value="add" />

        <button type="submit" name="confirm" value="confirm" id="confirm" class="btn btn-primary ">confirm</button>

    </form>
    <p></p>
</main>
<a href="/nycos-giftaid" id="backHome" class="btn btn-primary">Back</a>