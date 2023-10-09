<?php
require_once(SIMPLE_WP_MEMBERSHIP_PATH.'/lib/ElavonAPI.php');
require_once(SIMPLE_WP_MEMBERSHIP_PATH.'/lib/NycosAPI.php');
$nycosAPI = new NycosAPI();

session_start();

$auth = SwpmAuth::get_instance();
$user_data = (array) $auth->userData;
extract($user_data, EXTR_SKIP);
$contact = $nycosAPI->getAPI('contacts/'.$extra_info.'?IncludeConsent=true','');

//before review build sagepay
?>

<div>

    <h4 class="section-title">Make a Donation to NYCOS</h4>
    <div id="multi-step-form-container">
        <!-- Form Steps / Progress Bar -->
        <ul class="form-stepper form-stepper-horizontal text-center mx-auto pl-0">
            <!-- Step 1 -->
            <li class="<?php ($_REQUEST['nextStep']>0)?  print "form-stepper-active" : print "form-stepper-unfinished"  ?> text-center form-stepper-list" step="1">
                <a class="mx-2">
                    <span class="form-stepper-circle">
                        <span>1</span>
                    </span>
                    <div class="label">Donation</div>
                </a>
            </li>
            <!-- Step 2 -->
            <li class="<?php ($_REQUEST['nextStep']>1)?  print "form-stepper-active" : print "form-stepper-unfinished"  ?> text-center form-stepper-list" step="2">
                <a class="mx-2">
                    <span class="form-stepper-circle text-muted">
                        <span>2</span>
                    </span>
                    <div class="label text-muted">Contact Details</div>
                </a>
            </li>
            <!-- Step 3 -->
            <li class="<?php ($_REQUEST['nextStep']>2)?  print "form-stepper-active" : print "form-stepper-unfinished"  ?> text-center form-stepper-list" step="3">
                <a class="mx-2">
                    <span class="form-stepper-circle text-muted">
                        <span>3</span>
                    </span>
                    <div class="label text-muted">Review</div>
                </a>
            </li>
            <!-- Step 4 -->
            <li class="<?php ($_REQUEST['nextStep']>3)?  print "form-stepper-active" : print "form-stepper-unfinished"  ?> text-center form-stepper-list" step="4">
                <a class="mx-2">
                    <span class="form-stepper-circle text-muted">
                        <span>4</span>
                    </span>
                    <div class="label text-muted">Confirmation</div>
                </a>
            </li>
        </ul>
        <?php if (empty($_REQUEST['nextStep'])) { ?>
        <form id="userAccountSetupForm" name="userAccountSetupForm" enctype="multipart/form-data" method="POST">
            <input type="hidden" name="nextStep" value="2" />
            <!-- Step 1 Content -->
            <section id="step-1" class="form-step">
                <?php include(SIMPLE_WP_MEMBERSHIP_PATH.'/views/partial/donation_select.php'); ?>
            </section>
        </form>
        <?php }
              if ($_REQUEST['nextStep']==2) { ?>
        <form>
            <input type="hidden" name="nextStep" value="3" />
            <input type="hidden" name="amount" value="<?= $_REQUEST["amount"] ?>" />
            <?php if (!empty($_REQUEST["giftaid"])){
                      print ' <input type="hidden" name="giftaid" value="1" />';
                  } ?>
           
            <input type="hidden" name="destinationCode" value="<?= $_REQUEST["destinationCode"] ?>" />
            <!-- Step 2 Content, default hidden on page load. -->
            <section id="step-2" class="form-step">
                <?php include(SIMPLE_WP_MEMBERSHIP_PATH.'/views/partial/profile_form.php'); ?>
            </section>
        </form>  
        <?php }
              if ($_REQUEST['nextStep']==3) {                 
                  if (empty($contact->serialNumber)) {
                      $_SESSION["contactDetails"]= (object)$_REQUEST;
                  } else {
                        $_SESSION["contactDetails"]= $contact;
                  }
        ?>
            <section id="step-3" class="form-step ">
                <?php include(SIMPLE_WP_MEMBERSHIP_PATH.'/views/partial/donation_review.php'); ?>
            </section>
        <?php } ?>
    </div>
</div>
<a href="/nycos-home" id="backHome" class="btn btn-primary">Back</a>
<style>
    h1 {
    text-align: center;
}
h2 {
    margin: 0;
}
#multi-step-form-container {
    margin-top: 5rem;
}
.text-center {
    text-align: center;
}
.mx-auto {
    margin-left: auto;
    margin-right: auto;
}
.pl-0 {
    padding-left: 0;
}
.button {
    padding: 0.7rem 1.5rem;
    border: 1px solid #4361ee;
    background-color: #4361ee;
    color: #fff;
    border-radius: 5px;
    cursor: pointer;
}
.submit-btn {
    border: 1px solid #0e9594;
    background-color: #0e9594;
}
.mt-3 {
    margin-top: 2rem;
}

.form-step {
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 20px;
    padding: 3rem;
}
.font-normal {
    font-weight: normal;
}
ul.form-stepper {
    counter-reset: section;
    margin-bottom: 3rem;
}
ul.form-stepper .form-stepper-circle {
    position: relative;
}
ul.form-stepper .form-stepper-circle span {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translateY(-50%) translateX(-50%);
}
.form-stepper-horizontal {
    position: relative;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    justify-content: space-between;
}
ul.form-stepper > li:not(:last-of-type) {
    margin-bottom: 0.625rem;
    -webkit-transition: margin-bottom 0.4s;
    -o-transition: margin-bottom 0.4s;
    transition: margin-bottom 0.4s;
}
.form-stepper-horizontal > li:not(:last-of-type) {
    margin-bottom: 0 !important;
}
.form-stepper-horizontal li {
    position: relative;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-flex: 1;
    -ms-flex: 1;
    flex: 1;
    -webkit-box-align: start;
    -ms-flex-align: start;
    align-items: start;
    -webkit-transition: 0.5s;
    transition: 0.5s;
}
.form-stepper-horizontal li:not(:last-child):after {
    position: relative;
    -webkit-box-flex: 1;
    -ms-flex: 1;
    flex: 1;
    height: 1px;
    content: "";
    top: 32%;
}
.form-stepper-horizontal li:after {
    background-color: #dee2e6;
}
.form-stepper-horizontal li.form-stepper-completed:after {
    background-color: #4da3ff;
}
.form-stepper-horizontal li:last-child {
    flex: unset;
}
ul.form-stepper li a .form-stepper-circle {
    display: inline-block;
    width: 40px;
    height: 40px;
    margin-right: 0;
    line-height: 1.7rem;
    text-align: center;
    background: rgba(0, 0, 0, 0.38);
    border-radius: 50%;
}
.form-stepper .form-stepper-active .form-stepper-circle {
    background-color: #4361ee !important;
    color: #fff;
}
.form-stepper .form-stepper-active .label {
    color: #4361ee !important;
}
.form-stepper .form-stepper-active .form-stepper-circle:hover {
    background-color: #4361ee !important;
    color: #fff !important;
}
.form-stepper .form-stepper-unfinished .form-stepper-circle {
    background-color: #f8f7ff;
}
.form-stepper .form-stepper-completed .form-stepper-circle {
    background-color: #0e9594 !important;
    color: #fff;
}
.form-stepper .form-stepper-completed .label {
    color: #0e9594 !important;
}
.form-stepper .form-stepper-completed .form-stepper-circle:hover {
    background-color: #0e9594 !important;
    color: #fff !important;
}
.form-stepper .form-stepper-active span.text-muted {
    color: #fff !important;
}
.form-stepper .form-stepper-completed span.text-muted {
    color: #fff !important;
}
.form-stepper .label {
    font-size: 1rem;
    margin-top: 0.5rem;
}
.form-stepper a {
    cursor: default;
}
</style>

