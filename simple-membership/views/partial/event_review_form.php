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


?>
<div class="autoSummaryDiv">
  <fieldset>
<legend>Review Your Booking</legend>
<h4> Total Booking Cost </h4>
<table class="autoSummary">
    <tbody>
        <tr>
            <td class="left">total cost</td>
            <td class="right">&pound; <?php print array_sum($total); ?></td>
        </tr>
        <tr>
            <td class="left">number of tickets</td>
            <td class="right"><?php print count($_SESSION['attendeeData']); ?></td>
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
                  <td class="right"><?= $attendee->firstName ?></td>
              </tr>
              <tr>
                  <td class="left">surname</td>
                  <td class="right"><?= $attendee->keyname ?></td>
              </tr>
              <tr>
                  <td class="left">date of birth</td>
                  <td class="right"><?= $attendee->DOBDay ?>/<?= $attendee->DOBMonth ?>/<?= $attendee->DOBYear ?></td>
              </tr>
              <tr>
                  <td class="left">gender</td>
                  <td class="right"><?= $attendee->gender ?></td>
              </tr>
              <tr>
                  <td class="left">local authority</td>
                  <td class="right"><?= $attendee->region ?></td>
              </tr>
              <tr>
                  <td class="left">special requirements</td>
                  <td class="right"><?= $attendee->specialNeeds ?></td>
              </tr>
              <?php foreach($attendee->Structure as $structureId => $value){
              $structure=$nycosAPI->getStructure($structureId);
              $item = new Seminars($structure); ?>
              <tr>
                  <td class="left"><?php (strpos($item->eventName, "Mini") !== false or strpos($item->eventName, "Summer School") !== false)? print $item->structureType: print $item->notes  ?></td>
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

       SwpmLog::log_simple_debug( 'Before payment for Booking: Contact '.print_r($contact,true), true );

       //check if any costs have been created if not go straight to confirmtation
       if (array_sum($total) >0){
           //print_r($contact);
           $sagePay = new SagePay();
           $sagePay->setCurrency('GBP');
           $sagePay->setAmount(array_sum($total)); // where to get amount of the membership type from?????
           $sagePay->setVendorTxCode($txCode);
           $sagePay->setDescription($event->eventName);
           $sagePay->setBillingSurname($contact->keyname);
           $sagePay->setBillingFirstnames($contact->firstName);
           if ($contact->town){$sagePay->setBillingCity($contact->town);}
           if ($contact->postcode){$sagePay->setBillingPostCode($contact->postcode); }
           if ($contact->address){$sagePay->setBillingAddress1($contact->address);}
           $sagePay->setBillingCountry('GB');
           $sagePay->setDeliverySameAsBilling();
           $sagePay->setSuccessURL('https://nycos.co.uk/nycos-book-event/?id='.$event->eventId.'&nextStep=5');
           $sagePay->setFailureURL('https://nycos.co.uk/nycos-book-event/');
       }

      ?>

    <h4> Contact Details </h4>
    <table class="autoSummary">
      <tbody>
        <tr>
          <td class="left">title</td>
          <td class="right"><?php echo $contact->title; ?></td>
        </tr>
        <tr>
          <td class="left">first name</td>
          <td class="right"><?php echo $contact->firstName; ?></td>
        </tr>
        <tr>
          <td class="left">surname</td>
          <td class="right"><?php echo $contact->keyname; ?></td>
        </tr>
        <tr>
          <td class="left">country</td>
          <td class="right"><?php echo $contact->country; ?></td>
        </tr>
        <tr>
          <td class="left">postcode</td>
          <td class="right"><?php echo $contact->postcode; ?></td>
        </tr>
        <tr>
          <td class="left">address</td>
          <td class="right"><?php echo $contact->address; ?></td>
        </tr>
        <tr>
          <td class="left">city</td>
          <td class="right"><?php echo $contact->town; ?></td>
        </tr>
        <tr>
          <td class="left">county</td>
          <td class="right"><?php echo $contact->county; ?></td>
        </tr>
        <tr>
          <td class="left">email address</td>
          <td class="right"><?php echo $contact->emailAddress; ?></td>
        </tr>
        <tr>
          <td class="left">telephone (day)</td>
          <td class="right"><?php echo $contact->dayTelephone; ?></td>
        </tr>
        <tr>
          <td class="left">telephone (evening)</td>
          <td class="right"><?php echo $contact->eveningTelephone; ?></td>
        </tr>
        <tr>
          <td class="left">mobile number</td>
          <td class="right"><?php echo $contact->mobileNumber; ?></td>
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

<form method="POST" id="SagePayForm" action="https://live.sagepay.com/gateway/service/vspform-register.vsp">
    <input type="hidden" name="VPSProtocol" value="3.00" />
    <input type="hidden" name="TxType" value="PAYMENT" />
    <input type="hidden" name="Vendor" value="NYCOS" />
    <input type="hidden" name="Crypt" value="<?php echo $sagePay->getCrypt(); ?>" />
    <button type="button" onclick="window.location.href = window.location.href += '?nextStep=0';" id="prevButton" class="btn btn-primary">Back</button>
    <button class="btn btn-primary submit-btn" type="submit">Next</button>
</form>
<?php } else {
    //free event ?>
<form id="userAccountSetupForm" name="userAccountSetupForm" enctype="multipart/form-data" method="POST">
                <input type="hidden" name="nextStep" value="5" />
    <button type="button" onclick="window.location.href = window.location.href += '?nextStep=0';" id="prevButton" class="btn btn-primary">Back</button>
    <button class="btn btn-primary submit-btn" type="submit">Next</button>
    </form>
<?php
      } ?>