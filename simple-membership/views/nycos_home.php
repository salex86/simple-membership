<?php
require_once(SIMPLE_WP_MEMBERSHIP_PATH.'/lib/NycosAPI.php');
$nycosAPI = new NycosAPI();

$auth = SwpmAuth::get_instance();
$user_data = (array) $auth->userData;
$user_data['membership_level_alias'] = $auth->get('alias');
extract($user_data, EXTR_SKIP);
$settings=SwpmSettings::get_instance();
$force_strong_pass=$settings->get_value('force-strong-passwords');
if (!empty($force_strong_pass)) {
    $pass_class="validate[custom[strongPass],minSize[8]]";
} else {
    $pass_class="";
}
SimpleWpMembership::enqueue_validation_scripts();

 $contact = $nycosAPI->getAPI('contacts/'.$extra_info.'?IncludeConsent=true','');
if (empty($contact->serialNumber)){
    print "There is no serial attached to this account please contact NYCOS, quoting this message";
    return;
}
?>

<main class="site-main" id="main" role="main">
    <h5 class="section-title">
        Your Account
        <hr />
    </h5>
    <div class="alert alert-success" role="alert">
        <h4 class="alert-heading">Welcome!</h4>
        <p>We have recently migrated our web portal to a new system .</p>
        <hr />
        <p class="mb-0">If you havent already done so please change your password using the <a href="nycos-password">Reset password page </a> </p>
    </div>
    <p class="lead"> The NYCOS site provides you with a number of different ways to interact with us. You can access and manage the following information: </p>
    <div class="row">
        <div class="col-md-6">
            <h3>Memberships</h3>
            <p>
                Create a <a href="nycos-new-membership">new membership</a>, view your <a href="nycos-memberships">existing memberships</a> to make a membership payment or view your <a href="nycos-mem-payments">membership payment history</a>.
            </p>
        </div>
        <div class="col-md-6">
            <h3>Donations</h3>
            <p>
                <a href="nycos-donation">Make a donation</a>, pay a previously pledged donation, check that we have a valid <a href="nycos-giftaid">Gift Aid Declaration</a> for you or view your <a href="nycos-donations">donation history</a>.
            </p>
        </div>
        <div class="col-md-6">
            <h3>Events</h3>
            <p>
                <a href="nycos-events" title="Events">Make a booking</a> for our upcoming concerts, courses and events. View your <a href="nycos-bookings" title="Booking">Existing Bookings</a>
            </p>
        </div>
        <div class="col-md-6">
            <h3>Shop</h3>
            <p>
                <a href="shop" title="Shop">Browse our shop</a> to see our publications, CDs and products.
            </p>
        </div>
       
        <div class="col-md-6">
            <h3>Your Account</h3>
            <p>
                Make sure that your <a href="nycos-profile">contact details</a> are correct and up to date or <a href="nycos-password">change your password</a>.
                <a class="btn btn-primary" href="?swpm-logout=true">
                    Logout
                </a>
            </p>
        </div>
        <div class="col-md-6">
            <h3>Communications</h3>
            <p>
                View your <a href="nycos-communication">Communications</a> from NYCOS.
            </p>
        </div>
    </div>
</main>