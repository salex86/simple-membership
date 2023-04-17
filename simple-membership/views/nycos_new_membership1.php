<?php
require_once(SIMPLE_WP_MEMBERSHIP_PATH.'/lib/SagePay.php');
require_once(SIMPLE_WP_MEMBERSHIP_PATH.'/lib/NycosAPI.php');
session_start();
$nycosAPI = new NycosAPI();

$auth = SwpmAuth::get_instance();
$user_data = (array) $auth->userData;
extract($user_data, EXTR_SKIP);
$contact = $nycosAPI->getAPI('contacts/'.$extra_info.'?IncludeConsent=true','');

$item = $nycosAPI->getMembershipSchemes()->data;

$txCode= rand(0,32000)*rand(0,32000);

?>

    <!-- MultiStep Form -->
<div>
    <h4 class="section-title">Become a Friend</h4>
    <div id="multi-step-form-container">
        <!-- Form Steps / Progress Bar -->
        <ul class="form-stepper form-stepper-horizontal text-center mx-auto pl-0">
            <!-- Step 1 -->
            <li class="<?php (empty($_REQUEST['nextStep']) or $_REQUEST['nextStep']>0)?  print "form-stepper-active" : print "form-stepper-unfinished"  ?> text-center form-stepper-list" step="1">
                <a class="mx-2">
                    <span class="form-stepper-circle">
                        <span>1</span>
                    </span>
                    <div class="label">Select</div>
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
            <!-- Step 2 -->
            <li class="<?php ($_REQUEST['nextStep']>2)?  print "form-stepper-active" : print "form-stepper-unfinished"  ?> text-center form-stepper-list" step="3">
                <a class="mx-2">
                    <span class="form-stepper-circle text-muted">
                        <span>3</span>
                    </span>
                    <div class="label text-muted">Review</div>
                </a>
            </li>
            <!-- Step 3 -->
            <li class="<?php ($_REQUEST['nextStep']>3)?  print "form-stepper-active" : print "form-stepper-unfinished"  ?> text-center form-stepper-list" step="4">
                <a class="mx-2">
                    <span class="form-stepper-circle text-muted">
                        <span>4</span>
                    </span>
                    <div class="label text-muted">Payment</div>
                </a>
            </li>
            <!-- Step 4 -->
            <li class="<?php ($_REQUEST['nextStep']>4)?  print "form-stepper-active" : print "form-stepper-unfinished"  ?>  text-center form-stepper-list" step="5">
                <a class="mx-2">
                    <span class="form-stepper-circle text-muted">
                        <span>5</span>
                    </span>
                    <div class="label text-muted">Confirmation</div>
                </a>
            </li>
        </ul>
        <!-- Step Wise Form Content -->
     
            <!-- Step 1 Content -->
        <?php if (empty($_REQUEST['nextStep'])) { ?>
            <section id="step-1" class="form-step">
                    <input type="hidden" name="nextStep" value="2" />
                    <?php include(SIMPLE_WP_MEMBERSHIP_PATH.'/views/partial/membership_select.php'); ?>                   
                </section>
         <?php } else if ($_REQUEST['nextStep']==2) { ?>
             <section id="step-2" class="form-step">
                 <form id="userAccountSetupForm" name="userAccountSetupForm" enctype="multipart/form-data" autocomplete="off" method="POST">

                     <input type="hidden" name="nextStep" value="3" />
                     <div class="mt-3">
                         <?php include(SIMPLE_WP_MEMBERSHIP_PATH.'/views/partial/giftaid_add.php'); ?>
                         <?php include(SIMPLE_WP_MEMBERSHIP_PATH.'/views/partial/profile_form.php'); ?>                         

                         <input type="hidden" name="schemename" value="<?php if ($_REQUEST['schemename']){ print $_REQUEST['schemename']; } ?>" />
                         <input type="hidden" name="period" value="<?php if ($_REQUEST['period']){ print $_REQUEST['period']; } ?>" />
                         <input type="hidden" name="amount" value="<?php if ($_REQUEST['amount']){ print $_REQUEST['amount']; } ?>" />
                         <input type="hidden" name="paymentMethod" value="<?php if ($_REQUEST['paymentMethod']){ print $_REQUEST['paymentMethod']; } ?>" />

                     </div>
                 </form>
             </section>             
            <!-- Step 3 Content, default hidden on page load. --> 
        <?php } else if ($_REQUEST['nextStep']==3) {
                     
                      if (empty($contact->serialNumber)) {
                          $_SESSION["contactDetails"]= (object)$_REQUEST;
                      } else {
                          $_SESSION["contactDetails"]= $contact;
                      }
        ?>
            <section id="step-3" class="form-step">
                <h2 class="font-normal">Review</h2>
                <!-- Step 2 input fields -->
                <?php include(SIMPLE_WP_MEMBERSHIP_PATH.'/views/partial/membership_review.php'); ?>
            </section>
        <?php }
              if ($_REQUEST['nextStep']==3) {
        ?>
            <!-- Step 4 Content, default hidden on page load. -->
            <section id="step-4" class="form-step d-none">
                <form method="POST" id="debitForm" enctype="multipart/form-data" >
                    <input type="hidden" name="giftaid" value="<?php if ($_REQUEST['giftaid']){ print "on"; } ?>" />
                    <input type="hidden" name="nextStep" value="5" />
        <?php include(SIMPLE_WP_MEMBERSHIP_PATH.'/views/partial/membership_debit.php'); ?>
                    </form>
            </section>
        <?php } else if ($_REQUEST['nextStep']==5) { ?>
            <!-- Step 4 Content, default hidden on page load. -->
            <section id="step-5" class="form-step ">
                <?php include(SIMPLE_WP_MEMBERSHIP_PATH.'/views/partial/membership_confirmation.php'); ?>
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
.d-none {
    display: none;
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

    /* General CSS */

#aspnetForm.form-inline {
    display: block
}

label {
    display: inline-block !important;
}

.hide,
.hidden {
    display: none !important
}

h5.section-title {
    text-transform: uppercase
}

a.nav-link {
    color: #00aac5 !important
}

    a.nav-link:hover {
        color: #006879 !important
    }

@media(min-width:768px) {
    #ctl00_cp1_contactDetails_CONTACTTITLE_radio tr,
    #ctl00_cp1_CONTACTTITLE_radio tr {
        display: table-cell
    }
}

.imglist img {
    height: 60px
}

.esitContent .SSArow table td {
    padding: 0 5px
}

.attendee #sp {
    display: flex;
    flex-wrap: wrap;
    margin-right: -5px;
    margin-left: -5px
}

#sp .input-small {
    width: 31.5%;
    margin-left: 5px;
    margin-right: 5px
}

input[type=checkbox] {
    position: absolute;
    margin-top: .3rem;
    margin-left: -1.25rem
}

    input[type=checkbox] + label {
        margin-bottom: 0
    }

input[type=submit] {
    margin-bottom: 5px
}

span.checkbox + label {
    margin-bottom: 0
}

.esitContent .esitBody fieldset {
    margin: 10px 0;
    border-top: none
}

.esitContent .esitBody .main input:focus,
.esitContent .esitBody .main select:focus,
.esitContent .esitBody .main textarea:focus {
    outline: none
}

.esitContent .orgCheckbox {
    margin-left: 10px;
    margin-bottom: 10px
}

.esitContent .volunteerSkills .checkbox {
    clear: both;
    float: none
}

.esitContent .check label {
    display: inline;
    margin-left: 5px
}

.esitContent .check input {
    float: left
}

.esitContent .tqRow,
.esitContent .SSArow,
.esitContent .rowRadio {
    margin-bottom: 1rem;
    clear: both
}

    .esitContent .SSArow input,
    .esitContent .SSArow label,
    .esitContent .rowRadio label {
        display: inline
    }

.esitContent .gap {
    clear: both
}

.esitContent .captcha span {
    margin-left: 23px
}

.esitContent .tqRow .tqLabel,
.esitContent .gap .tqLabel,
.esitContent .rowRadio .tqLabel,
.esitContent .SSArow .tqLabel {
    margin-top: 0
}

.esitContent .checkbox .value {
    margin-left: 10px;
    float: right
}

.esitContent .autoSummary td.left {
    vertical-align: top
}

.esitContent .ownLineValidationMessage {
    padding-left: 215px
}

.esitContent .rowLeft label,
.esitContent .rowLeft .label {
    width: 130px
}

.esitContent .rowRadio td label,
.esitContent .rowRadio td .label {
    text-align: left
}

.esitContent .rowRadio td input {
    float: right;
    margin: 7px 0 0 5px
}

.esitContent .SSArow table label {
    width: auto;
    float: none;
    margin: 0;
    padding: 0 5px
}

.esitContent .SSArow table input.SSAfreeAmount {
    width: 55px
}

.esitContent .value {
    overflow: visible;
    display: inline-block;
    text-align: right;
    float: right
}

.esitContent .rowRadio .value {
    margin-top: 3px;
    float: left
}

.esitContent .gap input,
.esitContent .gap textarea {
    width: 200px
}

.esitContent .btns {
    margin-left: 160px
}

.esitContent .checkBoxes div {
    display: block
}

.tqRow .has-error label {
    color: #a94442
}

small.error {
    color: inherit
}

.has-error small.error {
    color: #a94442
}

p.error {
    color: inherit
}

.has-error p.error {
    color: #a94442
}

label span.filled {
}

.esitContent table.cbListDest td label {
    width: auto;
    text-align: left;
    padding-left: 5px
}

.esitContent .tqRow .longButton,
.esitContent .gap .longButton {
    width: 300px
}

.esitContent .esitBody input.inpWide {
    width: 250px
}

input.inpSmall {
    width: 50px
}

.esitContent .esitBody textarea.inpBig {
    height: 100px
}

.esitContent .esitBody textarea.inpHuge {
    width: 350px;
    height: 200px
}

.esitContent .valSummary {
    margin: 10px 0 10px 10px;
    font-size: 1.1em;
    border: 1px solid;
    width: 90%;
    padding: 5px
}

.esitContent table.autoSummary {
    border-collapse: collapse
}

    .esitContent table.autoSummary td {
        padding: 10px 1px
    }

    .esitContent table.autoSummary tr td.left {
        width: 220px;
        padding-right: 20px;
        text-align: right
    }

    .esitContent table.autoSummary tr td.right {
        text-align: left
    }

.esitContent div.autoSummaryDiv h4 {
    margin-top: 20px
}

.esitContent td.num {
    text-align: right
}

.esitContent td.tdWide {
    width: 90%
}

.esitContent td.tdMid {
    width: 33%
}

.esitContent input.reminderButton {
    width: 80px
}

.esitContent .esitBtn,
.esitContent .tqRow .esitBtn {
    padding-left: 10px;
    padding-right: 10px;
    width: auto
}

.esitContent div.softDiv {
    padding: 5px 0 10px
}

.esitContent table.softTable td {
    border-bottom: 1px solid #767676
}

.esitContent td.tdTopAlign {
    vertical-align: top;
    text-align: center
}

.esitContent ul.bulleted {
    margin-top: 10px;
    list-style-type: disc;
    padding-left: 30px
}

    .esitContent ul.bulleted li {
        padding-bottom: 10px
    }

.esitContent .ddPlaces {
    width: 4.5em;
    margin-bottom: 0
}

.esitContent .customHTMLWrapper h5 {
    color: #777;
    margin-bottom: 5px
}

.esitContent .customHTMLWrapper {
    position: absolute;
    display: none
}

.esitContent .customHTMLInner {
    position: relative;
    margin-top: 100px;
    left: 50px;
    padding: 20px;
    border: 1px solid red;
    width: 610px;
    background: url(../image/bgSoft.gif) repeat-x #eee
}

.esitContent .customHTMLWrapper .customHTMLTextBox {
    display: block;
    margin-bottom: 5px
}

.esitContent .customHTMLWrapper #customHTMLDefault {
    margin-top: 10px;
    margin-bottom: 10px
}

    .esitContent .customHTMLWrapper #customHTMLDefault a {
        text-decoration: none
    }

.esitContent .calendarWrapper {
    text-align: center
}

.esitContent table.eventsCalendar {
    width: 234px;
    margin-left: auto;
    margin-right: auto
}

    .esitContent table.eventsCalendar td {
        padding: 2px
    }

.esitContent td.eventDay {
    font-weight: 700;
    border: 1px solid #000
}

.esitContent .rowCheckBoxList {
    margin-top: 30px
}

    .esitContent .rowCheckBoxList table {
        margin-left: 70px
    }

.esitContent .accountCheck div {
    padding: 5px 0 0 130px;
    clear: both
}

.esitContent .accountCheck label {
    padding-left: 5px
}

.esitContent .loneButton {
    padding: 10px 0 10px 160px
}

.esitContent .accountFeedback p {
    color: Red
}

.esitContent .eventDoBook {
    font-size: 1.2em;
    font-weight: 700
}

.esitContent .tradingPriceLineLeft {
    float: left;
    clear: both;
    font-size: 1.2em;
    margin-top: 5px
}

.esitContent .tradingTotalPrice {
    font-size: 1.2em;
    padding-bottom: 10px
}

.esitContent .tradingPriceLineRight,
.esitContent .documentPermissionsLineRight {
    float: right
}

.basketPriceLineRight .input-small {
    margin-bottom: 5px;
    margin-top: 5px
}

.esitContent .tradingIncludes {
    margin: 20px 0 10px
}

.esitContent div.tradingBasketMenu {
    border: 1px solid;
    padding: 8px
}

    .esitContent div.tradingBasketMenu li {
        font-size: .9em
    }

.esitContent .basket {
    border-radius: 4px;
    padding: 10px;
    margin: 5px 0
}

    .esitContent .basket ul {
        margin-left: 0;
        margin-top: 5px
    }

.esitContent .tradingBasketLinks {
    margin-top: 5px;
    text-align: right
}

.esitContent .tradinOtherPrice,
.esitContent .documentOtherPrice {
    clear: both;
    margin-bottom: 2px
}

.esitContent .basketStockLine {
    text-align: right;
    float: left;
    margin-left: 105px
}

.esitContent .tradingStockLine {
    text-align: right;
    clear: both;
    padding-bottom: 5px
}

.esitContent .tradingAddressOption {
    margin: 10px 0 10px 155px
}

.esitContent .ADDRESSCHOICEDiv td {
    padding-bottom: 20px
}

.esitContent .tradingSummary h4 {
    margin-top: 20px
}

.esitContent table.tradingProductSummary {
    border-collapse: collapse
}

.esitContent .tradingProductSummary td,
.esitContent .tradingProductSummary th {
    text-align: right;
    border: 1px solid;
    padding: 4px
}

.esitContent .tradingProductSummary .tradingItem {
    text-align: left
}

.esitContent .tqRow textarea.tradingSpecialInstructions {
    height: 100px
}

label[for=ctl00_cp1_contactDetails_CONTACTTITLE_radio_5] {
    letter-spacing: -.04em
}

.esitContent div.dProductListing,
.esitContent div.dDocumentDetails {
    overflow: auto;
    padding: 10px 0 10px 10px
}

.esitContent div.xTradingProduct {
    overflow: hidden;
    margin: 0 auto;
    padding-left: 10px
}

.esitContent hr.tradingGroupDivider,
.esitContent hr.documentGroupDivider {
    clear: both
}

.esitContent .DDGbox {
    border: 2px solid #000;
    padding: 20px;
    margin-bottom: 20px
}

.esitContent table.results {
    border-collapse: collapse
}

    .esitContent table.results th {
        text-align: left
    }

    .esitContent table.results th,
    .esitContent table.results td {
        padding: 3px;
        border-width: 1px;
        border-style: solid
    }

.esitContent .alumniResultsCell div {
}

.esitContent .alumniNewMessage {
    height: 200px;
    width: 500px
}

.esitContent .wholeWidthMultiLineBox {
    width: 450px;
    height: 250px
}

.esitContent .employmentRecord {
    margin-bottom: 20px
}

    .esitContent .employmentRecord div {
        margin-bottom: 10px
    }

.esitContent .messageWaiting {
    border-width: 1px;
    border-style: solid;
    padding-right: 10px;
    padding-left: 10px;
    margin: 10px
}

.esitContent .courseName {
}

.esitContent .registerCourses label {
    width: 140px
}

.esitContent .searchProfileAlert {
    border: 1px solid;
    padding: 5px;
    margin: 10px 0
}

.esitContent .editModeCbListTable {
    padding-top: 5px
}

.esitContent .alumniSearchForm .tqRow label,
.esitContent .alumniSearchForm .tqRow .label {
    width: 100px
}

.tqRow input.courseAutocompleterTextBox {
    width: 350px
}

.tradingPriceLineRight .btn {
    margin-bottom: 9px
}

.tradingPriceLineDetail {
    margin-top: 10px
}

.tradingLabel {
    display: inline-block;
    width: 100px;
    text-align: right;
    margin-right: 5px
}

#ProductThumbs {
    max-height: 60px;
    white-space: nowrap
}

.imglist a img {
    padding: 5px 4px
}

.courseAutocompleter {
    border: 1px solid gray;
    position: relative;
    left: 120px !important;
    background: #fff
}

.courseAutocompleterTrading {
    border: 1px solid gray;
    position: relative;
    padding-left: 0;
    z-index: 99;
    left: 1px !important;
    background: #fff
}

.courseAutocompleterItem {
    padding: 3px 3px 3px 10px;
    background: #fff;
    list-style: none;
    font-size: smaller
}

.courseAutocompleterSearch {
    border: 1px solid gray;
    position: relative;
    left: 80px !important;
    background: #fff
}

.courseAutocompleterItemHighlighted {
    padding: 3px 3px 3px 10px;
    background: #ccc;
    list-style: none;
    cursor: pointer;
    font-size: smaller
}

.cookieTable,
.cookieTable td,
.cookieTable th {
    border: 1px solid #aaa;
    border-collapse: collapse;
    padding: 5px
}

.emailAddressSpan {
    font-weight: 700
}

.tradingFirstHalf,
.documentFirstHalf {
    width: 50%;
    display: inline;
    float: left
}

.tradingSecondHalf,
.documentSecondHalf {
    width: 50%;
    display: inline;
    float: right;
    margin-top: 0
}

.volunteers {
    width: 500px
}

.volunteersEntry {
    margin: 0 0 10px
}

.mapInfo {
    width: 300px
}

    .mapInfo div {
        -moz-border-radius: 15px;
        border-radius: 5px;
        min-height: 70px;
        margin: 6px;
        padding: 4px 10px
    }

.esitContent div.volunteerSkills table {
    margin-left: 200px
}

.esitContent .esitBtnRow {
    margin: 10px 0 10px 205px
}

.searchSort {
    float: right
}

.esitContent h2.groupHeader {
    float: left;
    display: inline;
    margin-top: 2px;
    margin-bottom: 10px
}

.esitContent .documentPriceLine {
    padding-bottom: 5px
}

.has-error label {
    color: #a94442
}

html {
    min-height: 100%;
    position: relative;
    margin: 0;
    padding: 0
}

body {
    margin-bottom: 140px
}

a:hover,
a:focus {
    text-decoration: none
}

@media(min-width:768px) {
    ul {
        list-style-position: outside
    }

    .alignedBtn {
        margin: 0 0 0 225px
    }

    .esitContent .basketPriceLineRight {
        float: right
    }
}

.footer {
    font-size: .8em;
    color: #555;
    position: absolute;
    bottom: 0;
    width: 100%;
    height: 140px;
    padding: 10px 0
}

    .footer .container {
        padding: 0 25px
    }

@media(min-width:768px) {
    body {
        margin-bottom: 85px
    }

    .footer {
        height: 85px
    }

    .footer-left {
        float: left;
        text-align: left
    }

    .footer-right {
        float: right;
        text-align: right
    }
}

.esitContent {
    margin: 20px auto
}

.esitBody {
    padding: 5px 10px 10px;
    clear: both
}

.esitBanner {
    padding: 5px;
    margin-bottom: 5px
}

    .esitBanner .logo {
        padding: 0 20px
    }

    .esitBanner h2 {
        margin: 20px 0 0;
        padding: 0;
        float: right
    }

.esitContent .breadcrumb li {
    color: #999
}

    .esitContent .breadcrumb li.active {
        color: #444
    }

.esitContent .breadcrumb .divider {
    padding: 0 5px;
    color: #ccc
}

div.tqbreadCrumb {
    margin-bottom: 12px;
    margin-top: 7px
}

.breadcrumb > li + li:before {
    content: none
}

.main .page-header {
    margin-top: 0
}

.access {
    display: none
}

.clear {
    clear: both
}

.esitContent .main .page-header {
    margin-top: 0
}

h3.popover-title {
    margin: 0 !important
}

h4.panel-title {
    margin: 0 !important
}

#ctl00_basketUpdatePanel ul,
ul.unstyled,
ol.unstyled {
    margin-left: 0;
    padding-left: 0;
    list-style: none
}

td.tdWide {
    width: 85%
}

label {
    font-weight: 400
}

.product-add {
    margin-bottom: 9px
}

#sp select {
    display: inline-block
}

.xtradingProduct div.dProductImageleft,
.xtradingProduct div.dProductImageright,
.xtradingProduct div.dDocumentImageleft {
    float: left;
    margin: 5px 5px 10px 0;
    padding-top: 0
}

.esitContent .esitBody .main,
.esitContent .breadCrumb,
.esitContent .esitBody .esitLeft,
.esitContent .esitBody table.softTable td,
.esitContent .esitBody div.softDiv,
.esitContent .esitBanner,
.esitContent h1,
.esitContent table.results th,
.esitContent table.results td {
    border-color: #767676
}

.dProductListing p,
.dProductListing ul,
.dProductListing table {
    display: none
}

    .dProductListing p:first-child {
        display: block
    }

.shopItem img {
    max-width: 150px;
    max-height: 205px
}

.basketPriceLineRight select,
.basketPriceLineRight input,
.tradingPriceLineRight select,
.tradingPriceLineRight input {
    display: inline-block
}

.shop-buttons a {
    margin-bottom: 5px
}

.sale .tradingPrice {
    text-decoration: line-through;
    color: #900
}

@media(min-width:768px) {
    .shopRow img {
        max-width: 200px;
        max-height: 162px
    }

    .esitContent .orgCheckbox {
        margin-left: 226px;
        margin-bottom: 10px
    }
}

@media(min-width:992px) {
    .shopRow img {
        max-width: 150px;
        max-height: 162px
    }
}

@media(min-width:1200px) {
    .shopRow img {
        max-width: 200px;
        max-height: 162px
    }

    .affix {
        width: 257px;
        top: 5px
    }
}

.softTable {
    width: 100%
}

.navbar-nav {
    margin: 0
}

.esitMenuNormal .well {
    min-height: 20px;
    padding: 0;
    margin-bottom: 0;
    border: none;
    border-radius: 4px;
    -webkit-box-shadow: none;
    box-shadow: none
}

.active > .well {
    padding-left: 0
}

    .active > .well > ul > li > a {
        padding-left: 40px
    }

        .active > .well > ul > li > a:before {
            content: "\e080 \00a0";
            text-decoration: inherit;
            width: 20px;
            position: absolute;
            left: 20px;
            top: 15px;
            font: 400 10px glyphicons halflings
        }

.esitMenuNormal .well > ul > li {
    float: left
}

.navbar-default .navbar-nav > li > a {
    color: #337ab7
}

.nav > span > li > a {
    position: relative;
    display: block;
    padding: 10px 15px
}

.navbar-default .navbar-brand {
    color: #555
}

.esitMenuNormal .well > ul > li + li {
    margin-left: 2px
}

.esitMenuNormal .well > ul > li.active > a,
.esitMenuNormal .well > ul > li.active > a:hover,
.esitMenuNormal .well > ul > li.active > a:focus,
.navbar-nav li.active > a,
.navbar-nav li.active > a:hover,
.navbar-nav li.active > a:focus {
    color: #555;
    background: #e7e7e7
}

.esitMenuNormal .well > ul > li {
    float: none
}

    .esitMenuNormal .well > ul > li + li {
        margin-left: 0
    }

.navbar-collapse {
    padding-right: 0;
    padding-left: 0
}

@media(min-width:768px) {
    .sidebar-nav .navbar .navbar-collapse {
        padding: 0;
        max-height: none
    }

    .sidebar-nav .navbar ul {
        float: none
    }

        .sidebar-nav .navbar ul:not {
            display: block
        }

    .sidebar-nav .navbar li {
        float: none;
        display: block
    }

        .sidebar-nav .navbar li a {
            padding-top: 12px;
            padding-bottom: 12px
        }
}

@media(max-width:768px) {
    .esitContent .tqRow label,
    .esitContent .tqRow .tqLabel,
    .esitContent .gap label,
    .esitContent .gap .tqLabel,
    .esitContent .SSArow label,
    .esitContent .SSArow .tqLabel,
    .esitContent .rowRadio .tqLabel,
    .esitContent .rowRadio label {
        width: auto;
        text-align: left
    }

    .affix {
        position: static;
        top: 5px
    }
}

@media(min-width:768px) {
    .captcha input,
    .form-inline .form-control,
    .form-inline .SSAfreeAmount {
        width: 100%
    }

    .form-inline .input-group > .postcode-control {
        width: 169px;
        height: 38px
    }

    .rowRadio > label {
        width: auto !important
    }
}

.esitContent .input-small {
    width: 70px;
    width: 70px;
    padding: 6px
}

.esitContent .input-medium {
    width: 125px
}

.form-inline .input-group > .postcode-control {
    border-top-left-radius: 4px !important;
    border-bottom-left-radius: 4px !important
}

.postcode-group > span {
    vertical-align: bottom
}

@media(min-width:768px) and (max-width:1200px) {
    .affix {
        width: 207px;
        top: 5px
    }
}

@media(min-width:768px) {
    .postcode-group > span {
        vertical-align: top
    }

    .esitContent .value {
        float: none;
        text-align: left
    }
}

html.no-js table.cbList {
    display: block !important
}

button,
html [type=button],
[type=reset],
[type=submit] {
    -webkit-appearance: none !important;
    color: #fff;
    background-color: none;
    border-color: none
}

#wrapper-footer .zigzag {
    border-bottom: 27px solid #1c1c1c;
    background: 0 0;
    height: unset
}

.zigzag {
    background: 0 0;
    height: unset
}

.mobile-head .zigzag2 {
    background: 0 0
}

@media screen and (-ms-high-contrast:active),(-ms-high-contrast:none) {
    .zigzag svg {
        -ms-transform: translateY(48%) scale(1.6) !important
    }
}




.swpm-margin-10 {
    margin: 10px;
}
.swpm-margin-top-10{
    margin-top: 10px;
}
.swpm-margin-bottom-10{
    margin-bottom: 10px;
}
.swpm-hidden{
    display: none;
}

.swpm-yellow-box{
    margin: 10px 0px;
    padding: 10px;
    background-color: #FFFFE0;
    border-color: #E6DB55;
    border-radius: 3px 3px 3px 3px;
    border-style: solid;
    border-width: 1px;
}

.swpm-red-box {
    margin: 10px 0px;
    padding: 10px;
    background-color: #FFEBE8;
    border-color: #CC0000;
    color: #333333;
    border-radius: 3px 3px 3px 3px;
    border-style: solid;
    border-width: 1px;
}

/* Wrap directly with this class (not to be used with a paragraph tag) */
.swpm-orange-box{
    margin: 10px 0px;
    padding: 15px 10px;
    color: #3F2502;
    text-shadow: 1px 1px #FFFFFF;
    background-color: #FFF6D5;
    border-color: #D1B655;
    border-radius: 3px 3px 3px 3px;
    border-style: solid;
    border-width: 1px;
}

/* Wrap directly with this class (not to be used with a paragraph tag) */
.swpm-grey-box{
    margin: 10px 0px;
    padding: 15px 10px;
    background-color: #DDDDDD;
    border-color: #CCCCCC;
    border-radius: 3px 3px 3px 3px;
    border-style: solid;
    border-width: 1px;
}

/* Wrap directly with this class (not to be used with a paragraph tag) */
.swpm-green-box {
    margin: 10px 0px;
    padding: 15px 10px;
    background-color: #CCF4D6;
    border-color: #059B53;
    color: #043B14;
    border-radius: 3px 3px 3px 3px;
    border-style: solid;
    border-width: 1px;
}

/* Membership buy buttons */
.swpm-button-wrapper input[type="submit"]{
    width: auto !important;
    height: auto !important;
}
.swpm-button-wrapper input[type="image"]{
    width: auto !important;
    height: auto !important;
}

/* Login form CSS */
.swpm-login-widget-form input,.swpm-login-widget-form checkbox{
    width: auto;
}
.swpm-username-input, .swpm-password-input{
    margin-bottom: 10px;
}
.swpm-login-submit{
    margin-bottom: 10px;
}
.swpm-login-widget-action-msg{
    font-weight: bold;
}
.swpm-logged-label{
    font-weight: bold;
}

/* Password reset form CSS */
.swpm-pw-reset-widget-form table{
    border: none;
}
.swpm-pw-reset-widget-form tr{
    border: none;
}
.swpm-pw-reset-widget-form td{
    border: none;
}
.swpm-reset-pw-error{
    font-weight: bold;
    color: red;
}
.swpm-reset-pw-success-box{
    margin: 10px 0px;
    padding: 15px 10px;
    background-color: #CCF4D6;
    border-color: #059B53;
    color: #043B14;
    border-radius: 3px 3px 3px 3px;
    border-style: solid;
    border-width: 1px;
}

/* Registration form CSS */
.swpm-registration-widget-form td{
    min-width: 100px;
}

.swpm-registration-widget-form input[type="text"], .swpm-registration-widget-form input[type="password"]{
    width: 95%;
    position: relative;
}

/* Edit profile form CSS */
.swpm-edit-profile-form input[type="text"], .swpm-edit-profile-form input[type="password"] {
    width: 95%;
}
.swpm-edit-profile-form select {
    width: 95%;
}
.swpm-edit-profile-submit-section{
    text-align: center;
}

.swpm-profile-account-delete-section{
    text-align: center;
}
.swpm-profile-account-delete-section a{
    color: red !important;
}
.swpm-profile-update-success{
    font-weight: bold;
    color: green;
}
.swpm-profile-update-error{
    font-weight: bold;
    color: red;
}
/* Misc CSS */
.swpm-restricted{
    font-weight: bold;
    color:red;
}
.swpm-select-box-left{
    margin: 0;
    padding-bottom: 5px;
}

.switch {
    font-size: 1rem;
    position: relative
}

.switch input {
    position: absolute;
    height: 1px;
    width: 1px;
    background: 0 0;
    border: 0;
    clip: rect(0 0 0 0);
    clip-path: inset(50%);
    overflow: hidden;
    padding: 0
}

    .switch input + label {
        position: relative;
        min-width: calc(calc(2.375rem * .8) * 2);
        border-radius: calc(2.375rem * .8);
        height: calc(2.375rem * .8);
        line-height: calc(2.375rem * .8);
        display: inline-block;
        cursor: pointer;
        outline: none;
        user-select: none;
        vertical-align: middle;
        text-indent: calc(calc(calc(2.375rem * .8) * 2) + .5rem)
    }

        .switch input + label::before,
        .switch input + label::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: calc(calc(2.375rem * .8) * 2);
            bottom: 0;
            display: block
        }

        .switch input + label::before {
            right: 0;
            background-color: #dee2e6;
            border-radius: calc(2.375rem * .8);
            transition: .2s all
        }

        .switch input + label::after {
            top: 2px;
            left: 2px;
            width: calc(calc(2.375rem * .8) - calc(2px * 2));
            height: calc(calc(2.375rem * .8) - calc(2px * 2));
            border-radius: 50%;
            background-color: #fff;
            transition: .2s all
        }

    .switch input:checked + label::before {
        background-color: #00aac5
    }

    .switch input:checked + label::after {
        margin-left: calc(2.375rem * .8)
    }

    .switch input:focus + label::before {
        outline: none;
        box-shadow: 0 0 0 .2rem rgba(0,136,221,.25)
    }

    .switch input:disabled + label {
        color: #868e96;
        cursor: not-allowed
    }

        .switch input:disabled + label::before {
            background-color: #e9ecef
        }

.switch.switch-sm {
    font-size: .875rem
}

    .switch.switch-sm input + label {
        min-width: calc(calc(1.9375rem * .8) * 2);
        height: calc(1.9375rem * .8);
        line-height: calc(1.9375rem * .8);
        text-indent: calc(calc(calc(1.9375rem * .8) * 2) + .5rem)
    }

        .switch.switch-sm input + label::before {
            width: calc(calc(1.9375rem * .8) * 2)
        }

        .switch.switch-sm input + label::after {
            width: calc(calc(1.9375rem * .8) - calc(2px * 2));
            height: calc(calc(1.9375rem * .8) - calc(2px * 2))
        }

    .switch.switch-sm input:checked + label::after {
        margin-left: calc(1.9375rem * .8)
    }

.switch.switch-lg {
    font-size: 1.25rem
}

    .switch.switch-lg input + label {
        min-width: calc(calc(3rem * .8) * 2);
        height: calc(3rem * .8);
        line-height: calc(3rem * .8);
        text-indent: calc(calc(calc(3rem * .8) * 2) + .5rem)
    }

        .switch.switch-lg input + label::before {
            width: calc(calc(3rem * .8) * 2)
        }

        .switch.switch-lg input + label::after {
            width: calc(calc(3rem * .8) - calc(2px * 2));
            height: calc(calc(3rem * .8) - calc(2px * 2))
        }

    .switch.switch-lg input:checked + label::after {
        margin-left: calc(3rem * .8)
    }

.switch + .switch {
    margin-left: 1rem
}

#aspnetForm.form-inline {
    display: block
}

label {
    display: inline-block !important;
}

.hide,
.hidden {
    display: none !important
}

h5.section-title {
    text-transform: uppercase
}

a.nav-link {
    color: #00aac5 !important
}

    a.nav-link:hover {
        color: #006879 !important
    }

@media(min-width:768px) {
    #ctl00_cp1_contactDetails_CONTACTTITLE_radio tr,
    #ctl00_cp1_CONTACTTITLE_radio tr {
        display: table-cell
    }
}

.imglist img {
    height: 60px
}

.esitContent .SSArow table td {
    padding: 0 5px
}

.attendee #sp {
    display: flex;
    flex-wrap: wrap;
    margin-right: -5px;
    margin-left: -5px
}

#sp .input-small {
    width: 31.5%;
    margin-left: 5px;
    margin-right: 5px
}

input[type=checkbox] {
    position: absolute;
    margin-top: .3rem;
    margin-left: -1.25rem
}

    input[type=checkbox] + label {
        margin-bottom: 0
    }

input[type=submit] {
    margin-bottom: 5px
}

span.checkbox + label {
    margin-bottom: 0
}

.esitContent .esitBody fieldset {
    margin: 10px 0;
    border-top: none
}

.esitContent .esitBody .main input:focus,
.esitContent .esitBody .main select:focus,
.esitContent .esitBody .main textarea:focus {
    outline: none
}

.esitContent .orgCheckbox {
    margin-left: 10px;
    margin-bottom: 10px
}

.esitContent .volunteerSkills .checkbox {
    clear: both;
    float: none
}

.esitContent .check label {
    display: inline;
    margin-left: 5px
}

.esitContent .check input {
    float: left
}

.esitContent .tqRow,
.esitContent .SSArow,
.esitContent .rowRadio {
    margin-bottom: 1rem;
    clear: both
}

    .esitContent .SSArow input,
    .esitContent .SSArow label,
    .esitContent .rowRadio label {
        display: inline
    }

.esitContent .gap {
    clear: both
}

.esitContent .captcha span {
    margin-left: 23px
}

.esitContent .tqRow .tqLabel,
.esitContent .gap .tqLabel,
.esitContent .rowRadio .tqLabel,
.esitContent .SSArow .tqLabel {
    margin-top: 0
}

.esitContent .checkbox .value {
    margin-left: 10px;
    float: right
}

.esitContent .autoSummary td.left {
    vertical-align: top
}

.esitContent .ownLineValidationMessage {
    padding-left: 215px
}

.esitContent .rowLeft label,
.esitContent .rowLeft .label {
    width: 130px
}

.esitContent .rowRadio td label,
.esitContent .rowRadio td .label {
    text-align: left
}

.esitContent .rowRadio td input {
    float: right;
    margin: 7px 0 0 5px
}

.esitContent .SSArow table label {
    width: auto;
    float: none;
    margin: 0;
    padding: 0 5px
}

.esitContent .SSArow table input.SSAfreeAmount {
    width: 55px
}

.esitContent .value {
    overflow: visible;
    display: inline-block;
    text-align: right;
    float: right
}

.esitContent .rowRadio .value {
    margin-top: 3px;
    float: left
}

.esitContent .gap input,
.esitContent .gap textarea {
    width: 200px
}

.esitContent .btns {
    margin-left: 160px
}

.esitContent .checkBoxes div {
    display: block
}

.tqRow .has-error label {
    color: #a94442
}

small.error {
    color: inherit
}

.has-error small.error {
    color: #a94442
}

p.error {
    color: inherit
}

.has-error p.error {
    color: #a94442
}

label span.filled {
}

.esitContent table.cbListDest td label {
    width: auto;
    text-align: left;
    padding-left: 5px
}

.esitContent .tqRow .longButton,
.esitContent .gap .longButton {
    width: 300px
}

.esitContent .esitBody input.inpWide {
    width: 250px
}

input.inpSmall {
    width: 50px
}

.esitContent .esitBody textarea.inpBig {
    height: 100px
}

.esitContent .esitBody textarea.inpHuge {
    width: 350px;
    height: 200px
}

.esitContent .valSummary {
    margin: 10px 0 10px 10px;
    font-size: 1.1em;
    border: 1px solid;
    width: 90%;
    padding: 5px
}

.esitContent table.autoSummary {
    border-collapse: collapse
}

    .esitContent table.autoSummary td {
        padding: 10px 1px
    }

    .esitContent table.autoSummary tr td.left {
        width: 220px;
        padding-right: 20px;
        text-align: right
    }

    .esitContent table.autoSummary tr td.right {
        text-align: left
    }

.esitContent div.autoSummaryDiv h4 {
    margin-top: 20px
}

.esitContent td.num {
    text-align: right
}

.esitContent td.tdWide {
    width: 90%
}

.esitContent td.tdMid {
    width: 33%
}

.esitContent input.reminderButton {
    width: 80px
}

.esitContent .esitBtn,
.esitContent .tqRow .esitBtn {
    padding-left: 10px;
    padding-right: 10px;
    width: auto
}

.esitContent div.softDiv {
    padding: 5px 0 10px
}

.esitContent table.softTable td {
    border-bottom: 1px solid #767676
}

.esitContent td.tdTopAlign {
    vertical-align: top;
    text-align: center
}

.esitContent ul.bulleted {
    margin-top: 10px;
    list-style-type: disc;
    padding-left: 30px
}

    .esitContent ul.bulleted li {
        padding-bottom: 10px
    }

.esitContent .ddPlaces {
    width: 4.5em;
    margin-bottom: 0
}

.esitContent .customHTMLWrapper h5 {
    color: #777;
    margin-bottom: 5px
}

.esitContent .customHTMLWrapper {
    position: absolute;
    display: none
}

.esitContent .customHTMLInner {
    position: relative;
    margin-top: 100px;
    left: 50px;
    padding: 20px;
    border: 1px solid red;
    width: 610px;
    background: url(../image/bgSoft.gif) repeat-x #eee
}

.esitContent .customHTMLWrapper .customHTMLTextBox {
    display: block;
    margin-bottom: 5px
}

.esitContent .customHTMLWrapper #customHTMLDefault {
    margin-top: 10px;
    margin-bottom: 10px
}

    .esitContent .customHTMLWrapper #customHTMLDefault a {
        text-decoration: none
    }

.esitContent .calendarWrapper {
    text-align: center
}

.esitContent table.eventsCalendar {
    width: 234px;
    margin-left: auto;
    margin-right: auto
}

    .esitContent table.eventsCalendar td {
        padding: 2px
    }

.esitContent td.eventDay {
    font-weight: 700;
    border: 1px solid #000
}

.esitContent .rowCheckBoxList {
    margin-top: 30px
}

    .esitContent .rowCheckBoxList table {
        margin-left: 70px
    }

.esitContent .accountCheck div {
    padding: 5px 0 0 130px;
    clear: both
}

.esitContent .accountCheck label {
    padding-left: 5px
}

.esitContent .loneButton {
    padding: 10px 0 10px 160px
}

.esitContent .accountFeedback p {
    color: Red
}

.esitContent .eventDoBook {
    font-size: 1.2em;
    font-weight: 700
}

.esitContent .tradingPriceLineLeft {
    float: left;
    clear: both;
    font-size: 1.2em;
    margin-top: 5px
}

.esitContent .tradingTotalPrice {
    font-size: 1.2em;
    padding-bottom: 10px
}

.esitContent .tradingPriceLineRight,
.esitContent .documentPermissionsLineRight {
    float: right
}

.basketPriceLineRight .input-small {
    margin-bottom: 5px;
    margin-top: 5px
}

.esitContent .tradingIncludes {
    margin: 20px 0 10px
}

.esitContent div.tradingBasketMenu {
    border: 1px solid;
    padding: 8px
}

    .esitContent div.tradingBasketMenu li {
        font-size: .9em
    }

.esitContent .basket {
    border-radius: 4px;
    padding: 10px;
    margin: 5px 0
}

    .esitContent .basket ul {
        margin-left: 0;
        margin-top: 5px
    }

.esitContent .tradingBasketLinks {
    margin-top: 5px;
    text-align: right
}

.esitContent .tradinOtherPrice,
.esitContent .documentOtherPrice {
    clear: both;
    margin-bottom: 2px
}

.esitContent .basketStockLine {
    text-align: right;
    float: left;
    margin-left: 105px
}

.esitContent .tradingStockLine {
    text-align: right;
    clear: both;
    padding-bottom: 5px
}

.esitContent .tradingAddressOption {
    margin: 10px 0 10px 155px
}

.esitContent .ADDRESSCHOICEDiv td {
    padding-bottom: 20px
}

.esitContent .tradingSummary h4 {
    margin-top: 20px
}

.esitContent table.tradingProductSummary {
    border-collapse: collapse
}

.esitContent .tradingProductSummary td,
.esitContent .tradingProductSummary th {
    text-align: right;
    border: 1px solid;
    padding: 4px
}

.esitContent .tradingProductSummary .tradingItem {
    text-align: left
}

.esitContent .tqRow textarea.tradingSpecialInstructions {
    height: 100px
}

label[for=ctl00_cp1_contactDetails_CONTACTTITLE_radio_5] {
    letter-spacing: -.04em
}

.esitContent div.dProductListing,
.esitContent div.dDocumentDetails {
    overflow: auto;
    padding: 10px 0 10px 10px
}

.esitContent div.xTradingProduct {
    overflow: hidden;
    margin: 0 auto;
    padding-left: 10px
}

.esitContent hr.tradingGroupDivider,
.esitContent hr.documentGroupDivider {
    clear: both
}

.esitContent .DDGbox {
    border: 2px solid #000;
    padding: 20px;
    margin-bottom: 20px
}

.esitContent table.results {
    border-collapse: collapse
}

    .esitContent table.results th {
        text-align: left
    }

    .esitContent table.results th,
    .esitContent table.results td {
        padding: 3px;
        border-width: 1px;
        border-style: solid
    }

.esitContent .alumniResultsCell div {
}

.esitContent .alumniNewMessage {
    height: 200px;
    width: 500px
}

.esitContent .wholeWidthMultiLineBox {
    width: 450px;
    height: 250px
}

.esitContent .employmentRecord {
    margin-bottom: 20px
}

    .esitContent .employmentRecord div {
        margin-bottom: 10px
    }

.esitContent .messageWaiting {
    border-width: 1px;
    border-style: solid;
    padding-right: 10px;
    padding-left: 10px;
    margin: 10px
}

.esitContent .courseName {
}

.esitContent .registerCourses label {
    width: 140px
}

.esitContent .searchProfileAlert {
    border: 1px solid;
    padding: 5px;
    margin: 10px 0
}

.esitContent .editModeCbListTable {
    padding-top: 5px
}

.esitContent .alumniSearchForm .tqRow label,
.esitContent .alumniSearchForm .tqRow .label {
    width: 100px
}

.tqRow input.courseAutocompleterTextBox {
    width: 350px
}

.tradingPriceLineRight .btn {
    margin-bottom: 9px
}

.tradingPriceLineDetail {
    margin-top: 10px
}

.tradingLabel {
    display: inline-block;
    width: 100px;
    text-align: right;
    margin-right: 5px
}

#ProductThumbs {
    max-height: 60px;
    white-space: nowrap
}

.imglist a img {
    padding: 5px 4px
}

.courseAutocompleter {
    border: 1px solid gray;
    position: relative;
    left: 120px !important;
    background: #fff
}

.courseAutocompleterTrading {
    border: 1px solid gray;
    position: relative;
    padding-left: 0;
    z-index: 99;
    left: 1px !important;
    background: #fff
}

.courseAutocompleterItem {
    padding: 3px 3px 3px 10px;
    background: #fff;
    list-style: none;
    font-size: smaller
}

.courseAutocompleterSearch {
    border: 1px solid gray;
    position: relative;
    left: 80px !important;
    background: #fff
}

.courseAutocompleterItemHighlighted {
    padding: 3px 3px 3px 10px;
    background: #ccc;
    list-style: none;
    cursor: pointer;
    font-size: smaller
}

.cookieTable,
.cookieTable td,
.cookieTable th {
    border: 1px solid #aaa;
    border-collapse: collapse;
    padding: 5px
}

.emailAddressSpan {
    font-weight: 700
}

.tradingFirstHalf,
.documentFirstHalf {
    width: 50%;
    display: inline;
    float: left
}

.tradingSecondHalf,
.documentSecondHalf {
    width: 50%;
    display: inline;
    float: right;
    margin-top: 0
}

.volunteers {
    width: 500px
}

.volunteersEntry {
    margin: 0 0 10px
}

.mapInfo {
    width: 300px
}

    .mapInfo div {
        -moz-border-radius: 15px;
        border-radius: 5px;
        min-height: 70px;
        margin: 6px;
        padding: 4px 10px
    }

.esitContent div.volunteerSkills table {
    margin-left: 200px
}

.esitContent .esitBtnRow {
    margin: 10px 0 10px 205px
}

.searchSort {
    float: right
}

.esitContent h2.groupHeader {
    float: left;
    display: inline;
    margin-top: 2px;
    margin-bottom: 10px
}

.esitContent .documentPriceLine {
    padding-bottom: 5px
}

.has-error label {
    color: #a94442
}

html {
    min-height: 100%;
    position: relative;
    margin: 0;
    padding: 0
}

body {
    margin-bottom: 140px
}

a:hover,
a:focus {
    text-decoration: none
}

@media(min-width:768px) {
    ul {
        list-style-position: outside
    }

    .alignedBtn {
        margin: 0 0 0 225px
    }

    .esitContent .basketPriceLineRight {
        float: right
    }
}

.footer {
    font-size: .8em;
    color: #555;
    position: absolute;
    bottom: 0;
    width: 100%;
    height: 140px;
    padding: 10px 0
}

    .footer .container {
        padding: 0 25px
    }

@media(min-width:768px) {
    body {
        margin-bottom: 85px
    }

    .footer {
        height: 85px
    }

    .footer-left {
        float: left;
        text-align: left
    }

    .footer-right {
        float: right;
        text-align: right
    }
}

.esitContent {
    margin: 20px auto
}

.esitBody {
    padding: 5px 10px 10px;
    clear: both
}

.esitBanner {
    padding: 5px;
    margin-bottom: 5px
}

    .esitBanner .logo {
        padding: 0 20px
    }

    .esitBanner h2 {
        margin: 20px 0 0;
        padding: 0;
        float: right
    }

.esitContent .breadcrumb li {
    color: #999
}

    .esitContent .breadcrumb li.active {
        color: #444
    }

.esitContent .breadcrumb .divider {
    padding: 0 5px;
    color: #ccc
}

div.tqbreadCrumb {
    margin-bottom: 12px;
    margin-top: 7px
}

.breadcrumb > li + li:before {
    content: none
}

.main .page-header {
    margin-top: 0
}

.access {
    display: none
}

.clear {
    clear: both
}

.esitContent .main .page-header {
    margin-top: 0
}

h3.popover-title {
    margin: 0 !important
}

h4.panel-title {
    margin: 0 !important
}

#ctl00_basketUpdatePanel ul,
ul.unstyled,
ol.unstyled {
    margin-left: 0;
    padding-left: 0;
    list-style: none
}

td.tdWide {
    width: 85%
}

label {
    font-weight: 400
}

.product-add {
    margin-bottom: 9px
}

#sp select {
    display: inline-block
}

.xtradingProduct div.dProductImageleft,
.xtradingProduct div.dProductImageright,
.xtradingProduct div.dDocumentImageleft {
    float: left;
    margin: 5px 5px 10px 0;
    padding-top: 0
}

.esitContent .esitBody .main,
.esitContent .breadCrumb,
.esitContent .esitBody .esitLeft,
.esitContent .esitBody table.softTable td,
.esitContent .esitBody div.softDiv,
.esitContent .esitBanner,
.esitContent h1,
.esitContent table.results th,
.esitContent table.results td {
    border-color: #767676
}

.dProductListing p,
.dProductListing ul,
.dProductListing table {
    display: none
}

    .dProductListing p:first-child {
        display: block
    }

.shopItem img {
    max-width: 150px;
    max-height: 205px
}

.basketPriceLineRight select,
.basketPriceLineRight input,
.tradingPriceLineRight select,
.tradingPriceLineRight input {
    display: inline-block
}

.shop-buttons a {
    margin-bottom: 5px
}

.sale .tradingPrice {
    text-decoration: line-through;
    color: #900
}

@media(min-width:768px) {
    .shopRow img {
        max-width: 200px;
        max-height: 162px
    }

    .esitContent .orgCheckbox {
        margin-left: 226px;
        margin-bottom: 10px
    }
}

@media(min-width:992px) {
    .shopRow img {
        max-width: 150px;
        max-height: 162px
    }
}

@media(min-width:1200px) {
    .shopRow img {
        max-width: 200px;
        max-height: 162px
    }

    .affix {
        width: 257px;
        top: 5px
    }
}

.softTable {
    width: 100%
}

.navbar-nav {
    margin: 0
}

.esitMenuNormal .well {
    min-height: 20px;
    padding: 0;
    margin-bottom: 0;
    border: none;
    border-radius: 4px;
    -webkit-box-shadow: none;
    box-shadow: none
}

.active > .well {
    padding-left: 0
}

    .active > .well > ul > li > a {
        padding-left: 40px
    }

        .active > .well > ul > li > a:before {
            content: "\e080 \00a0";
            text-decoration: inherit;
            width: 20px;
            position: absolute;
            left: 20px;
            top: 15px;
            font: 400 10px glyphicons halflings
        }

.esitMenuNormal .well > ul > li {
    float: left
}

.navbar-default .navbar-nav > li > a {
    color: #337ab7
}

.nav > span > li > a {
    position: relative;
    display: block;
    padding: 10px 15px
}

.navbar-default .navbar-brand {
    color: #555
}

.esitMenuNormal .well > ul > li + li {
    margin-left: 2px
}

.esitMenuNormal .well > ul > li.active > a,
.esitMenuNormal .well > ul > li.active > a:hover,
.esitMenuNormal .well > ul > li.active > a:focus,
.navbar-nav li.active > a,
.navbar-nav li.active > a:hover,
.navbar-nav li.active > a:focus {
    color: #555;
    background: #e7e7e7
}

.esitMenuNormal .well > ul > li {
    float: none
}

    .esitMenuNormal .well > ul > li + li {
        margin-left: 0
    }

.navbar-collapse {
    padding-right: 0;
    padding-left: 0
}

@media(min-width:768px) {
    .sidebar-nav .navbar .navbar-collapse {
        padding: 0;
        max-height: none
    }

    .sidebar-nav .navbar ul {
        float: none
    }

        .sidebar-nav .navbar ul:not {
            display: block
        }

    .sidebar-nav .navbar li {
        float: none;
        display: block
    }

        .sidebar-nav .navbar li a {
            padding-top: 12px;
            padding-bottom: 12px
        }
}

@media(max-width:768px) {
    .esitContent .tqRow label,
    .esitContent .tqRow .tqLabel,
    .esitContent .gap label,
    .esitContent .gap .tqLabel,
    .esitContent .SSArow label,
    .esitContent .SSArow .tqLabel,
    .esitContent .rowRadio .tqLabel,
    .esitContent .rowRadio label {
        width: auto;
        text-align: left
    }

    .affix {
        position: static;
        top: 5px
    }
}

@media(min-width:768px) {
    .captcha input,
    .form-inline .form-control,
    .form-inline .SSAfreeAmount {
        width: 100%
    }

    .form-inline .input-group > .postcode-control {
        width: 169px;
        height: 38px
    }

    .rowRadio > label {
        width: auto !important
    }
}

.esitContent .input-small {
    width: 70px;
    width: 70px;
    padding: 6px
}

.esitContent .input-medium {
    width: 125px
}

.form-inline .input-group > .postcode-control {
    border-top-left-radius: 4px !important;
    border-bottom-left-radius: 4px !important
}

.postcode-group > span {
    vertical-align: bottom
}

@media(min-width:768px) and (max-width:1200px) {
    .affix {
        width: 207px;
        top: 5px
    }
}

@media(min-width:768px) {
    .postcode-group > span {
        vertical-align: top
    }

    .esitContent .value {
        float: none;
        text-align: left
    }
}

html.no-js table.cbList {
    display: block !important
}

button,
html [type=button],
[type=reset],
[type=submit] {
    -webkit-appearance: none !important;
    color: #fff;
    background-color: none;
    border-color: none
}

#wrapper-footer .zigzag {
    border-bottom: 27px solid #1c1c1c;
    background: 0 0;
    height: unset
}

.zigzag {
    background: 0 0;
    height: unset
}

.mobile-head .zigzag2 {
    background: 0 0
}

@media screen and (-ms-high-contrast:active),(-ms-high-contrast:none) {
    .zigzag svg {
        -ms-transform: translateY(48%) scale(1.6) !important
    }
}



</style>
<script>
    function getBankDetails(sort, acc) {
      
    }

 
    

   document.addEventListener("DOMContentLoaded", function(event) 
{
         let element = document.querySelector('#switch-id');  
        element.addEventListener('change', function() {  
            
            if (this.checked) {               
                var an = document.querySelectorAll('tr.Annually');
                var mon = document.querySelectorAll('tr.Monthly'); 

                an.forEach(a => {
                    a.classList.add("hidden");
                })
                mon.forEach(m => {
                    m.classList.remove("hidden");
                })
            }
            else {
                var an = document.querySelectorAll('tr.Annually');
                var mon = document.querySelectorAll('tr.Monthly'); 
                an.forEach(a => {
                    a.classList.remove("hidden");
                })
                mon.forEach(m => {
                    m.classList.add("hidden");
                })
            }
        });


    });
/**
 * Define a function to navigate betweens form steps.
 * It accepts one parameter. That is - step number.
 */
const navigateToFormStep = (stepNumber) => {
    /**
     * Hide all form steps.
     */
    document.querySelectorAll(".form-step").forEach((formStepElement) => {
        formStepElement.classList.add("d-none");
    });
    /**
     * Mark all form steps as unfinished.
     */
    document.querySelectorAll(".form-stepper-list").forEach((formStepHeader) => {
        formStepHeader.classList.add("form-stepper-unfinished");
        formStepHeader.classList.remove("form-stepper-active", "form-stepper-completed");
    });
    /**
     * Show the current form step (as passed to the function).
     */
    document.querySelector("#step-" + stepNumber).classList.remove("d-none");
    /**
     * Select the form step circle (progress bar).
     */
    const formStepCircle = document.querySelector('li[step="' + stepNumber + '"]');
    /**
     * Mark the current form step as active.
     */
    formStepCircle.classList.remove("form-stepper-unfinished", "form-stepper-completed");
    formStepCircle.classList.add("form-stepper-active");
    /**
     * Loop through each form step circles.
     * This loop will continue up to the current step number.
     * Example: If the current step is 3,
     * then the loop will perform operations for step 1 and 2.
     */
    for (let index = 0; index < stepNumber; index++) {
        /**
         * Select the form step circle (progress bar).
         */
        const formStepCircle = document.querySelector('li[step="' + index + '"]');
        /**
         * Check if the element exist. If yes, then proceed.
         */
        if (formStepCircle) {
            /**
             * Mark the form step as completed.
             */
            formStepCircle.classList.remove("form-stepper-unfinished", "form-stepper-active");
            formStepCircle.classList.add("form-stepper-completed");
        }
    }
};
/**
 * Select all form navigation buttons, and loop through them.
 */
document.querySelectorAll(".btn-navigate-form-step").forEach((formNavigationBtn) => {
    /**
     * Add a click event listener to the button.
     */
    formNavigationBtn.addEventListener("click", () => {
        /**
         * Get the value of the step.
         */
        const stepNumber = parseInt(formNavigationBtn.getAttribute("step_number"));
        /**
         * Call the function to navigate to the target form step.
         */
        navigateToFormStep(stepNumber);
    });
});
</script>