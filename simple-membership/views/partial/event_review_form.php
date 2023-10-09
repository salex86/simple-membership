<?php
require_once(SIMPLE_WP_MEMBERSHIP_PATH.'/lib/NycosAPI.php');
//work out total costs from attendeeData in seession
$total = array();
foreach($_SESSION['attendeeData'] as $key => $attendee){
    $attendee = (object)$attendee;

    $total[$key] = $attendee->value;
    foreach($attendee->Structure as $structureId => $value){
        $total[$key] += $value;
    }
}

SwpmLog::log_simple_debug( 'event review form request '.print_r($_REQUEST,true), true );

$mainContact = new Contact($_SESSION["contactDetails"]);
SwpmLog::log_simple_debug( 'Contact for Booking '.print_r($mainContact,true), true );

$mainContact = $nycosAPI->postNewContact($mainContact->title,$mainContact->firstName,$mainContact->keyname,
        $mainContact->address,$mainContact->town,$mainContact->county,$mainContact->postcode,$mainContact->country,$mainContact->emailAddress,$mainContact->mobileNumber,
        $mainContact->dayTelephone,$mainContact->eveTelephone);

$theContact = new Contact( $_SESSION["contactDetails"]);

if (empty($contact->serialNumber)){
    if (array_key_exists("Mail",$theContact->consent)){
        $nycosAPI->postContactConsent($mainContact->serialNumber,"Mail","Granted");
    } else {
        $nycosAPI->postContactConsent($mainContact->serialNumber,"Mail","Denied");
    }
    if (array_key_exists("Email",$theContact->consent)){
        $nycosAPI->postContactConsent($mainContact->serialNumber,"Email","Granted");
    } else {
        $nycosAPI->postContactConsent($mainContact->serialNumber,"Email","Denied");
    }
}

$booking = new EventBooking();

foreach($_SESSION['attendeeData'] as $key => $attendee){

    $attendee = (object)$attendee;
    $attendee->dateOfBirth = date('Y-m-d', strtotime($attendee->DOBMonth.'-'.$attendee->DOBDay.'-'.$attendee->DOBYear));

    if ($attendee->isMain){

        $contactAttending = true;

        $booking->attendeeType = $attendee->attendeeType;
        $booking->serialNumber = $mainContact->serialNumber;
        $booking->badgeName = $attendee->firstName;
        $booking->dateOfBirth = $attendee->dateOfBirth;
        $booking->specialNeeds = $attendee->specialNeeds;
        foreach($attendee->Structure as $structureId => $value){
            $structure = new Structures();
            $structure->structureId = $structureId;
            $booking->structures[] = $structure;
        }
    } else {
        $contact = new Contact($nycosAPI->postNewContact($attendee->title,$attendee->firstName,$attendee->keyname,
        $contact->address,$contact->town,$contact->county,$contact->postcode,$contact->country,$contact->emailAddress,$contact->mobileNumber,
        $contact->dayTelephone,$contact->eveTelephone,$attendee->dateOfBirth,$attendee->gender,$attendee->region));

        $structures = array();

        foreach($attendee->Structure as $structureId => $value){
            $structure = new Structures();
            $structure->structureId = $structureId;
            $structures[] = $structure;
        }
        $additionalAttendees[]= new AdditionalAttendees($contact->serialNumber,$attendee->attendeeType,$structures,$contact->firstName,$attendee->dateOfBirth,$attendee->specialNeeds);
    }
};
if ($contactAttending){
    //contact coming so no mainserialnumber and we have booking setup already
    $booking->badgeName = $mainContact->firstName;
} else {
    //contact not coming use first attendee to create $booking
    $booking->mainAttendeeSerialNumber = $additionalAttendees[0]->serialNumber;
    $booking->serialNumber = $mainContact->serialNumber;
    $booking->attendeeType = $additionalAttendees[0]->attendeeType;
    $booking->badgeName = $additionalAttendees[0]->firstName;
    $booking->structures = $additionalAttendees[0]->structures;
    $booking->dateOfBirth = $additionalAttendees[0]->dateOfBirth;
    $booking->specialNeeds = $additionalAttendees[0]->specialNeeds;
    array_shift($additionalAttendees);
}

$booking->additionalAttendees = $additionalAttendees;
$booking->eventId = $event->eventId;
// SwpmLog::log_simple_debug( 'Payment for Booking made '.print_r($booking,true), true );
//use values to build up the bookingObject
$array = json_decode(json_encode($booking), true);
json_encode($array);
$booking = $nycosAPI->post($array,"eventBooking");

SwpmLog::log_simple_debug( 'Booking Return before payment '.print_r($booking,true), true );
//post payment
if (empty($booking->bookingId)){
    echo "error Building this booking";
} else {
    echo $booking->bookingId;
}
print_r($booking);

$elavonAPI = new ElavonAPI();

$order = $elavonAPI->CreateOrder(array_sum($total),$booking->bookingId)->href;
$session = $elavonAPI->CreatePaymentSession($order);

print_r($session);
?>
<script src="https://uat.hpp.converge.eu.elavonaws.com/client/library.js"></script>

<div class="autoSummaryDiv">
    <fieldset>
        <legend>Review Your Booking</legend>
        <h4> Total Booking Cost </h4>
        <table class="autoSummary">
            <tbody>
                <tr>
                    <td class="left">total cost</td>
                    <td class="right">
                        &pound; <?php print array_sum($total); ?>
                    </td>
                </tr>
                <tr>
                    <td class="left">number of tickets</td>
                    <td class="right">
                        <?php print count($_SESSION['attendeeData']); ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php $count =0;
      foreach($_SESSION['attendeeData'] as $key => $attendee){
          $count++;
                $attendee = (object)$attendee;
                $data = $nycosAPI->getAttendeeType($attendee->typeId);
                $attendeeType = new AttendeeTypes($data);?>
        <h4 id="attendeeTitle">
            Attendee <?= $count ?>: <?= $attendeeType->attendeeType ?> &pound; <?= $attendeeType->costs[0]->value ?>
        </h4>
        <table class="autoSummary">
            <tbody>

                <tr>
                    <td class="left">first name</td>
                    <td class="right">
                        <?= $attendee->firstName ?>
                    </td>
                </tr>
                <tr>
                    <td class="left">surname</td>
                    <td class="right">
                        <?= $attendee->keyname ?>
                    </td>
                </tr>
                <tr>
                    <td class="left">date of birth</td>
                    <td class="right">
                        <?= $attendee->DOBDay ?>/<?= $attendee->DOBMonth ?>/<?= $attendee->DOBYear ?>
                    </td>
                </tr>
                <tr>
                    <td class="left">gender</td>
                    <td class="right">
                        <?= $attendee->gender ?>
                    </td>
                </tr>
                <tr>
                    <td class="left">local authority</td>
                    <td class="right">
                        <?= $attendee->region ?>
                    </td>
                </tr>
                <tr>
                    <td class="left">special requirements</td>
                    <td class="right">
                        <?= $attendee->specialNeeds ?>
                    </td>
                </tr>
                <?php foreach($attendee->Structure as $structureId => $value){
              $structure=$nycosAPI->getStructure($structureId);
              $item = new Seminars($structure); ?>
                <tr>
                    <td class="left">
                        <?php (strpos($item->eventName, "Mini") !== false or strpos($item->eventName, "Summer School") !== false)? print $item->structureType: print $item->notes  ?>
                    </td>
                    <td class="right">
                        <?= $item->description ?><small>
                            - <strong>
                                <?php ($item->startDate)? print date("F j Y", strtotime($item->startDate)): "" ?>
                            </strong><?= $item->startTime ?> - <?= $item->endTime ?> - <?php ($item->costs[0]->value >0)? print $item->costs[0]->value:"" ?>
                        </small>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php
      }

       $contact = new Contact( $_SESSION["contactDetails"]);

        ?>

        <h4> Contact Details </h4>
        <table class="autoSummary">
            <tbody>
                <tr>
                    <td class="left">title</td>
                    <td class="right">
                        <?php echo $contact->title; ?>
                    </td>
                </tr>
                <tr>
                    <td class="left">first name</td>
                    <td class="right">
                        <?php echo $contact->firstName; ?>
                    </td>
                </tr>
                <tr>
                    <td class="left">surname</td>
                    <td class="right">
                        <?php echo $contact->keyname; ?>
                    </td>
                </tr>
                <tr>
                    <td class="left">country</td>
                    <td class="right">
                        <?php echo $contact->country; ?>
                    </td>
                </tr>
                <tr>
                    <td class="left">postcode</td>
                    <td class="right">
                        <?php echo $contact->postcode; ?>
                    </td>
                </tr>
                <tr>
                    <td class="left">address</td>
                    <td class="right">
                        <?php echo $contact->address; ?>
                    </td>
                </tr>
                <tr>
                    <td class="left">city</td>
                    <td class="right">
                        <?php echo $contact->town; ?>
                    </td>
                </tr>
                <tr>
                    <td class="left">county</td>
                    <td class="right">
                        <?php echo $contact->county; ?>
                    </td>
                </tr>
                <tr>
                    <td class="left">email address</td>
                    <td class="right">
                        <?php echo $contact->emailAddress; ?>
                    </td>
                </tr>
                <tr>
                    <td class="left">telephone (day)</td>
                    <td class="right">
                        <?php echo $contact->dayTelephone; ?>
                    </td>
                </tr>
                <tr>
                    <td class="left">telephone (evening)</td>
                    <td class="right">
                        <?php echo $contact->eveningTelephone; ?>
                    </td>
                </tr>
                <tr>
                    <td class="left">mobile number</td>
                    <td class="right">
                        <?php echo $contact->mobileNumber; ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <h4> Future Correspondence </h4>
        <?php foreach ($contact->consent as $consent) {
                  if ($consent->status == "Granted") {
                    array_push($contact->consent,$consent->channel);
                }
            }
        ?>
        <table class="autoSummary">
            <tbody>
                <tr>
                    <td class="left">I am happy for you to contact me by email for marketing purposes</td>
                    <td class="right">
                        <?= (array_key_exists("Email",$contact->consent)) ?  "Yes" :  "No"  ?>
                    </td>
                </tr>
                <tr>
                    <td class="left">I am happy for you to contact me by post for marketing purposes</td>
                    <td class="right">
                        <?= array_key_exists("Mail",$contact->consent)?  "Yes" :  "No"  ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </fieldset>
</div>

<?php
      if (array_sum($total) > 0){
          // paid event?>
<div class="alert alert-warning" role="alert">
    <div id="payBox" class="alert alert-warning" role="alert">
        <h4 class="alert-heading">WARNING</h4>
        <button id="payBtn" class="btn btn-primary submit-btn" onclick="onClickHandler();" type="submit">Submit</button>
        <p>Do not leave the processing page until your payment has been confirmed.</p>
    </div>
    <form id="dataForm">
        <input type="hidden" id="serial" value="<?= $contact->serialNumber; ?>" />
        <input type="hidden" id="amount" value="<?= array_sum($total); ?>" />
        <input type="hidden" id="bookingId" value="<?= $booking->bookingId; ?>" />
        <input type="hidden" id="orderid" value="<?= $session->id ?>" />
    </form>
</div>
<?php } ?>

<script type="text/javascript">
    const MessageTypes = window.ConvergeLightbox.MessageTypes;

    const submitData = (data) => {
      // send data to your server
      console.log(data);
    };

    function confirmPayment() {
        var formdata = new FormData();
        formdata.append("serial", document.getElementById('serial').value);
        formdata.append("orderid", document.getElementById('orderid').value);
        formdata.append("amount", document.getElementById('amount').value);
        formdata.append("dest", document.getElementById('dest').value);

        var requestOptions = {
          method: 'POST',
          body: formdata,
          redirect: 'follow'
        };

        fetch("wp-content/plugins/simple-membership/ajax/submitEventPayment.php", requestOptions)
          .then(response => response.text())
          .then(result => console.log(result))
          .catch(error => console.log('error', error));


        let btn = document.getElementById('payBox');
        btn.innerHTML = "Thank you for making your payment <a href='nycos-home' class='btn btn-primary'>Back</a>";
    }

    let lightbox;

    function onClickHandler() {
      // do work to create a sessionId
      const sessionId = '<?= $session->id ?>';
      if (!lightbox) {
        lightbox = new window.ConvergeLightbox({
          sessionId: sessionId,
          onReady: (error) =>
            error
              ? console.error('Lightbox failed to load')
              : lightbox.show(),
          messageHandler: (message, defaultAction) => {
            switch (message.type) {
              case MessageTypes.transactionCreated:
                submitData({
                  sessionId: message.sessionId,
                });
                    confirmPayment();
                break;
              case MessageTypes.hostedCardCreated:
                submitData({
                  convergePaymentToken: message.hostedCard,
                  hostedCard: message.hostedCard,
                  sessionId: message.sessionId,
                });
                break;
            }
            defaultAction();
          },
        });
      } else {
        lightbox.show();
      }
    }
</script>