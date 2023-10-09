<?php
require_once(SIMPLE_WP_MEMBERSHIP_PATH.'/lib/ElavonAPI.php');
require_once(SIMPLE_WP_MEMBERSHIP_PATH.'/lib/NycosAPI.php');
ini_set('session.gc_maxlifetime', 3600);
session_start();

$nycosAPI = new NycosAPI();

$auth = SwpmAuth::get_instance();
$user_data = (array) $auth->userData;
extract($user_data, EXTR_SKIP);
$contact = $nycosAPI->getAPI('contacts/'.$extra_info.'?IncludeConsent=true','');

$txCode= rand(0,32000)*rand(0,32000);

$data = $nycosAPI->getEvent($_REQUEST['id']);

$event = new Events($data);

if (!$event->publishToWeb){
    print "This event is not available yet";
} else {
    //if logged in check if booking exists

?>

<!-- MultiStep Form -->
<div>
    <h4 class="section-title">Book an event</h4>
    <div id="multi-step-form-container">
        <!-- Form Steps / Progress Bar -->
        <ul class="form-stepper form-stepper-horizontal text-center mx-auto pl-0">
            <!-- Step 1 -->
            <li class="<?php ($_REQUEST['nextStep']>0)?  print "form-stepper-active" : print "form-stepper-unfinished"  ?> text-center form-stepper-list" step="1">
                <a class="mx-2">
                    <span class="form-stepper-circle">
                        <span>1</span>
                    </span>
                    <div class="label">Tickets</div>
                </a>
            </li>
            <!-- Step 2 -->
            <li class="<?php ($_REQUEST['nextStep']>1)? print "form-stepper-active" : print "form-stepper-unfinished"  ?> text-center form-stepper-list" step="2">
                <a class="mx-2">
                    <span class="form-stepper-circle text-muted">
                        <span>2</span>
                    </span>
                    <div class="label text-muted">Attendees</div>
                </a>
            </li>
            <!-- Step 3 -->
            <li class="<?php ($_REQUEST['nextStep']>2)? print "form-stepper-active" : print "form-stepper-unfinished"  ?> text-center form-stepper-list" step="3">
                <a class="mx-2">
                    <span class="form-stepper-circle text-muted">
                        <span>3</span>
                    </span>
                    <div class="label text-muted">Contact Details</div>
                </a>
            </li>
            <!-- Step 4 -->
            <li class="<?php ($_REQUEST['nextStep']>3)? print "form-stepper-active" : print "form-stepper-unfinished"  ?> text-center form-stepper-list" step="4">
                <a class="mx-2">
                    <span class="form-stepper-circle text-muted">
                        <span>4</span>
                    </span>
                    <div class="label text-muted">Review</div>
                </a>
            </li>
            <!-- step 5 -->
            <li class="<?php ($_REQUEST['crypt'] or $_REQUEST['nextStep']==5)? print "form-stepper-active" : print "form-stepper-unfinished"  ?> text-center form-stepper-list" step="5">
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

        <section id="step-1" class="form-step <?php ($_REQUEST['nextStep'])? print "d-none": ""; ?>">
            <form id="selectTicketsForm" name="selectTicketsForm" enctype="multipart/form-data" method="POST">
                <input type="hidden" name="nextStep" value="2" />
                <main class="site-main" id="main" role="main">
                    <input type="hidden" name="nextStep" value="2" />

                    <span id="ctl00_cp1_totalPlaceCheck" style="color:Red;display:none;"></span>
                    <span id="ctl00_cp1_maxAttendeesValidator" style="color:Red;display:none;"></span>
                    <div id="ctl00_cp1_pageTop" class="pageTop">
                        <h4 id="ctl00_cp1_eventName">
                            <?= $event->eventName ?>
                        </h4>
                        <p id="ctl00_cp1_eventDates">
                            <?= date("F j Y", strtotime($event->startDate))  ?> - <?= date("F j Y", strtotime($event->endDate)) ?>, <?= $event->startTime ?> - <?= $event->endTime ?>
                        </p>
                        <p id="ctl00_cp1_NYCoS_eventDates"></p>
                        <p id="ctl00_cp1_eventDescription">
                            <?= $event->description ?>
                        </p>
                        <p id="ctl00_cp1_eventCosts" class="">
                            <strong>Prices:</strong> Variable
                        </p>
                        <p id="ctl00_cp1_eventLocation">
                            <strong>Location:</strong> <?= $event->locationAddressLine ?>
                        </p>
                    </div>
                    <p class="hide">
                        Choose how many places to book and click <strong>continue</strong>:
                    </p>
                    <input id="ticketsLeft" type="hidden" value="<?= $event->ticketsLeft ?>" />
                    <input id="maxAttendeesPerBooking" type="hidden" value="<?= $event->maxAttendeesPerBooking ?>" />

                    <fieldset id="evF1">
                        <legend></legend>
                        <table class="table table-striped table-bordered">
                            <tbody>
                                <tr>
                                    <th style="text-align:left;">Type</th>
                                    <th style="text-align:left;">Price</th>
                                    <th style="text-align:left;">Select no. required</th>
                                </tr>
                                <?php foreach ($event->attendeeTypes as $attendeeType){
                    $attendeeType = new AttendeeTypes($attendeeType);?>

                                <tr class="attendeeSelectRow">
                                    <td class="tdMid">
                                        <?= $attendeeType->attendeeType ?>
                                    </td>
                                    <td class="tdMid ">
                                        &pound; <?= $attendeeType->costs[0]->value ?>
                                    </td>
                                    <td data-attendeetype="<?= $attendeeType->attendeeType ?>">
                                        <input type="number" min="0" value="0" max="<?= $attendeeType->remaining ?>" name="attendeeType[<?= $attendeeType->attendeeTypeId ?>]" id="ctl00_cp1_selectPlaces_ctl01_Attplaces" class="ddPlaces form-control" />

                                        <span style="display:none" class="True"></span>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <div id="errorContainer"></div>
                    </fieldset>

                    <br />
                 
                </main>
                <div class="mt-3">
                    <button class="btn btn-primary" id="eventChooseButton" type="submit">Next</button>
                </div>
            </form>
        </section>

       <!-- Step 2 Content, default hidden on page load. -->
        <?php if ($_REQUEST['nextStep']==2) { ?>
         <section id="step-2" class="form-step">
            <form id="pageForm" name="userAccountSetupForm" enctype="multipart/form-data" method="POST">
                <input type="hidden" name="nextStep" value="3" />
              <?php  include(SIMPLE_WP_MEMBERSHIP_PATH.'/views/partial/attendee_form.php'); ?>
            </form>
         </section>
        <?php } else if ($_REQUEST['nextStep']==3) {
                  $_SESSION['attendeeData'] = $_REQUEST['attendeeType']; ?>
        <section id="step-3" class="form-step">
            <form id="pageForm" name="userAccountSetupForm" enctype="multipart/form-data" method="POST">
                <input type="hidden" name="nextStep" value="4" />
                <?php include(SIMPLE_WP_MEMBERSHIP_PATH.'/views/partial/profile_form.php'); ?>
            </form>
        </section>
        <?php } else if ($_REQUEST['nextStep']==4) {
                
                  if (empty($contact->serialNumber)) {
                      $_SESSION["contactDetails"]= (object)$_REQUEST;
                  } else {
                        $_SESSION["contactDetails"]= $contact;
                  }
        ?>
            <!-- Step 4 Content, default hidden on page load. -->
            <section id="step-4" class="form-step">
                    <?php include(SIMPLE_WP_MEMBERSHIP_PATH.'/views/partial/event_review_form.php'); ?>
            </section>
        <?php } else if ($_REQUEST['crypt'] or $_REQUEST['nextStep']==5) { ?>
            <!-- Step 5 Content, default hidden on page load. -->
            <section id="step-5" class="form-step">

                <?php  include(SIMPLE_WP_MEMBERSHIP_PATH.'/views/partial/event_confirmation.php'); ?>
            </section>
     <?php } ?>
    </div>
</div>
<a href="/nycos-events" id="backHome" class="btn btn-primary">Back</a>
<?php
} ?>
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
</style>
<script>

   document.addEventListener("DOMContentLoaded", function(event)
{

        document.getElementById("selectTicketsForm").onsubmit = function (form) {
            let totalAllowed = document.getElementById("ticketsLeft").value;
            let maxAttendeesPerBooking = document.getElementById("maxAttendeesPerBooking").value;
            let totalRequired = +0;
            document.querySelectorAll('.ddPlaces').forEach(function(ticket) {
                // Now do something with my button
                console.log(ticket);
                totalRequired += +ticket.value;
            });
            if (totalAllowed < totalRequired) {
                form.preventDefault();
                document.getElementById("errorContainer").innerHTML = "There are only " + totalAllowed + " spaces Left, please reduce your required spaces";
            } else if (totalRequired < 1) {
                form.preventDefault();
                document.getElementById("errorContainer").innerHTML = "Please select at least one attendee";
            } else if (maxAttendeesPerBooking < totalRequired) {
                form.preventDefault();
                document.getElementById("errorContainer").innerHTML = "There are only " + maxAttendeesPerBooking + " tickets available per booking, please reduce your required spaces";
     
            }
       };

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