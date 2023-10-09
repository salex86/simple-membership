<?php
require_once(SIMPLE_WP_MEMBERSHIP_PATH.'/lib/NycosAPI.php');
$nycosAPI = new NycosAPI();

$auth = SwpmAuth::get_instance();
$user_data = (array) $auth->userData;
$user_data['membership_level_alias'] = $auth->get('alias');
extract($user_data, EXTR_SKIP);
$contact = $nycosAPI->getAPI('contacts/'.$extra_info,'');

$children = $nycosAPI->getChildren($extra_info)->data ;
$idArray= array($extra_info);
foreach($children as $child) {
    $idArray[]= $child->childSerialNumber;
}


?>

<main class="site-main" id="main" role="main">
    <h5 class="section-title">
        Memberships<hr/>
    </h5>
    <div class="row hide">
        <div class="col-sm-6">
            <h3>Sign-Up for a new Membership</h3>
            <p>
                You can use the Membership sign-up option to <a href="nycos-new-membership" title="Membership sign-up">create a new membership</a>.
            </p>
        </div>
        <div class="col-sm-6">
            <h3>Membership Payment History</h3>
            <p>
                View the <a href="/nycos-mem-payments" title="Your Membership Payments">Membership Payments</a> you have made in the last 12 months.
            </p>

        </div>
    </div>
    <h3>Your Active Memberships</h3>
    <fieldset>
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th scope="col">Membership details</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach($idArray as $serialNumber){
                     
                    $item = $nycosAPI->getMemberships($serialNumber)->data;
                foreach($item as $membership) {
                    if (strtotime($membership->expiryDate) > time() ) {
                        $paymentAmount = $membership->nextPaymentAmount;
                        $paymentDate = $membership->nextPayment;
                        if (empty($membership->nextPaymentAmount)){
                            $paymentDate=$membership->expiryDate;
                        }
                        if (empty($membership->nextPayment)){
                            $paymentAmount=0;
                        }



                ?>
                <tr>
                    <td class="tdWide">
                        <h4>
                            <?= $membership->schemeName ?>
                            <small class="small"><?= $membership->bandName ?></small>
                        </h4><small>
                            Our reference: <?= $membership->membershipId ?><br /><br />
                        </small> Next payment due: &pound; <?= $paymentAmount ?> within one month of <?= date("F j Y", strtotime($paymentDate)) ?>
                        <br />
                        <p>
                            Need help with fees? You can ask for a fee reduction <a href="https://www.nycos.co.uk/sing/financial-assistance-application/">here</a>
                            
                        </p>
                        <?php if (!empty($membership->nextPayment) and $membership->paymentMethod=="Credit Card") {
                        ?>
                       
                        <a href="/NYCOS-Membership-payment?membershipDetailId=<?= $membership->membershipDetailId ?>" name="pay" value="pay by card" id="pay" class="btn btn-primary">Pay By Card</a>
                        <?php } ?>
                        <br />
                      
                         </td>
                </tr>
                <?php } } } ?>
            </tbody>
        </table>
    </fieldset>
    <p> A membership can be paid if it has: </p>
    <ul>
        <li>one or more due or overdue instalments</li>
        <li>and/or one or more instalments due in the next 12 months.</li>
    </ul>
    <br />
    <h3>Membership Payment History</h3>
    <p>
        View the <a href="nycos-mem-payments" title="Your Membership Payments">Membership Payments</a> you have made in the last 12 months.
    </p>
    <br />
    <h3>Regional Choir Memberships</h3>
    <p>
        Renew or start a <a href="nycos-rcmembership" title="Regional Choir">NYCOS Regional Choir Membership</a>.
    </p>
    <p class="hidden">
        <a href="javascript:window.print()" title="Print this page">Print</a> this page.
    </p>
</main>
<a href="/nycos-home" id="backHome" class="btn btn-primary">Back</a>
<a href="nycos_memberships.php">nycos_memberships.php</a>