<?php
require_once(SIMPLE_WP_MEMBERSHIP_PATH.'/lib/NycosAPI.php');
require_once(SIMPLE_WP_MEMBERSHIP_PATH.'/lib/class/Mailer.class.php');

$nycosAPI = new NycosAPI();
if (empty($_SESSION["contactDetails"])){
        print "There has been an error please contact NYCOS, quoting this message BookErr01";
        return;

}
if($_REQUEST['crypt']){
    $sagePay = new SagePay();
    $responseArray = $sagePay->decode($_REQUEST['crypt']);

    $orderid = $responseArray['VendorTxCode'];
    $amount = $responseArray['Amount'];

    SwpmLog::log_simple_debug( 'Payment for Booking made ', true );

    if ($_SESSION["paymentId"] == $orderid){
        $responseMessage = "Payment already made";
    } else {
        if($responseArray['Status'] === "OK"){
            // create contact or post and get serial Number
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

            SwpmLog::log_simple_debug( 'Booking Return '.print_r($booking,true), true );
            //post payment

            $payment = $nycosAPI->postEventPayment($orderid,$amount,$mainContact->serialNumber,$booking->bookingId);
            $_SESSION["paymentId"] = $orderid;
            SwpmLog::log_simple_debug( 'Payment for booking return'.print_r($payment,true), true );

            $email = new Mailer();
            $email->setBookingMessage($event->eventId,$orderid,$amount,count($_SESSION['attendeeData']),$mainContact);
            $email->send($mainContact->emailAddress);

            $responseMessage = "Thank you for making your booking!";

        }elseif($responseArray['Status'] === "ABORT"){
            // Payment Cancelled
            $responseMessage = "Card Payment Aborted";
        }else{
            // Payment Failed
            $responseMessage = "Card Payment Failed";
        }
    }
} else {
    //no crypt free
    $mainContact = new Contact($_SESSION["contactDetails"]);

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
        //$attendee);
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
    SwpmLog::log_simple_debug( 'Payment for free Booking made '.print_r($booking,true), true );

    //use values to build up the bookingObject
    $array = json_decode(json_encode($booking), true);
    json_encode($array);
    $booking = $nycosAPI->post($array,"eventBooking");

    SwpmLog::log_simple_debug( 'Payment for free Booking made '.print_r($booking,true), true );


    $email = new Mailer();
    $email->setBookingMessage($event->eventId,$orderid,0,count($_SESSION['attendeeData']),$mainContact);
    $email->send($mainContact->emailAddress);

    $responseMessage = "Thank you for making your booking!";

}


?>
<div class="autoSummaryDiv">
  <fieldset>
      <h2 class="font-normal">Confirmation</h2>
      <!-- Step 3 input fields -->
      <div class="mt-3">
          <?=$responseMessage?>
      </div>



</fieldset>
</div>