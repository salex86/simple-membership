<?php
require_once(SIMPLE_WP_MEMBERSHIP_PATH.'/lib/NycosAPI.php');

$nycosAPI = new NycosAPI();

$auth = SwpmAuth::get_instance();
$user_data = (array) $auth->userData;
$user_data['membership_level_alias'] = $auth->get('alias');
extract($user_data, EXTR_SKIP);
$contact = $nycosAPI->getAPI('contacts/'.$extra_info,'');
$payments = $nycosAPI->getPayments($extra_info,"Donation")->data;
$yearAgoToday = date('Y-m-d', strtotime(' - 1 years'));

function comparator($object1, $object2) {
    return $object1->date < $object2->date;
}

usort($payments, 'comparator');

?>

<main class="site-main" id="main" role="main">
    <h5 class="section-title">
        SUPPORT US
        <hr />
    </h5>
    <h1 class="page-header">Your Donations</h1>
    <p class="hidden">
        <a href="javascript:window.print()" title="Print this page">Print</a> this page.
    </p>
    <p id="ctl00_cp1_donations"> This page shows the donations we have received from you in the last 12 months. </p>
    <h4>Your donations since <?=date('Y-m-d', strtotime(' - 1 years')); ?></h4>
    <fieldset>
        <table class="table">
            <tbody>
                <tr>
                    <th scope="col">Donation Details</th>
                    <th scope="col" id="amount">Amount</th>
                </tr>
                <?php foreach($payments as $payment){
                if ($payment->incomeType=="DONATION"){
                    if ($payment->date > $yearAgoToday) {?> 

                <tr>
                    <td class="tdWide">
                        <h4> <?= date("F j Y", strtotime($payment->date)) ?> </h4> <?= $payment->paymentMethod ?> Donation
                    </td>
                    <td class="num"> &pound; <?= $payment->amount ?> </td>
                </tr>
                <?php }}} ?>

            </tbody>
        </table>
    </fieldset>
</main>
<a href="/nycos-home" id="backHome" class="btn btn-primary">Back</a>