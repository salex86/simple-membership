<?php

/**
 * Mailer
 *
 * Class for setting the templates and then
 * sending email notifications
 *
 * @version 1.0
 * @author Stephen Alexander
 */
class Mailer
{
    public $to;
    public $subject;
    public $message;
    private $headers;

    public function __construct() {
        $this->headers = array('Content-Type: text/html; charset=UTF-8');
    }

    public function send($to){
        wp_mail($to,$this->subject,$this->message,$this->headers);
    }

    public function setDonationMessage($firstName,$keyName,$amount,$paymentRef,$giftaid) {
        $giftText = "";
        if ($giftaid){
            $giftText = " We can claim &pound;".($amount*0.25)." from your donation. ";
        }
        $this->subject = "Thank you for your donation";
        $this->message = "<html><body><img title='Join Now' src='" . SIMPLE_WP_MEMBERSHIP_URL . "/images/nycos-email-logo.png' alt='NYCOS Header' width='100%' />
<p>Thank you ".$firstName." ".$keyName." for your donation of &pound;".$amount.". Our reference
                    for this donation is: ".$paymentRef.". ".$giftText." Your support is hugely appreciated and makes a real difference to our
                    children and young people, particularly during what is a challenging time for charities and arts organisations.</p><p>
                    We are delighted to be meeting, singing and training together in person again after a long period of delivering our
                    work online. The return of NYCOS to weekly activities, rehearsals, concert halls, schools and courses has been a joyous
                    one, and it is thanks to the support of so many that NYCOS continues to renew and regrow.
                    Our many thanks again for your support of NYCOS.</p></body></html>";

    }

    public function setBookingMessage($eventId,$bookingRef,$amount,$attendees,$contact) {
        $nycosAPI = new NycosAPI();
        $data = $nycosAPI->getEvent($eventId);
        $event = new Events($data);
        $contact = new Contact($contact);

        $this->subject = "Your Event Booking";
        $this->message = "<html><body><img title='Join Now' src='" . SIMPLE_WP_MEMBERSHIP_URL . "/images/nycos-email-logo.png' alt='NYCOS Header' width='100%' />
Event Name: ".$event->eventName." </br>
                    Event Dates: ".$event->startDate." ".$event->endDate." </br>
                    Event Location: ".$event->locationAddressLine." </br>
                    Booking Reference: ".$bookingRef." </br>
                    Total Booking Cost: ".$amount."  </br>
                    Attendees: ".$attendees." </br>
                    Contact Details:".$contact->title." ".$contact->firstName." ".$contact->keyname." ".$contact->address." </br>
                    Email: ".$contact->emailAddress." </br>
                    Telephone: ".$contact->dayTelephone." </br>
                    Please Note: Tickets (where used) will be available for collection at the venue half an hour before
                    the start time</body></html>";

    }

    public function setNewMembershipMessage($contact,$membershipTitle,$membershipId,$paymentType,$amount){
        $this->subject = "Your New Membership";
        $this->message = "<html><body><img title='Join Now' src='" . SIMPLE_WP_MEMBERSHIP_URL . "/images/nycos-email-logo.png' alt='NYCOS Header' width='100%' />
Thank you ".$contact->title." ".$contact->firstName." ".$contact->keyname." for setting up the following new membership. </br>
                    Membership: ".$membershipTitle." </br>
                    Membership Reference: ".$membershipId." </br>
                    Payment Type: ".$paymentType." </br>
                    Payment Amount: ".$amount."</body></html>";

    }

    public function setGiftAidMessage($contact){
        $this->subject = "Your Gift Aid Declaration";
        $this->message = "<html><body><img title='Join Now' src='" . SIMPLE_WP_MEMBERSHIP_URL . "/images/nycos-email-logo.png' alt='NYCOS Header' width='100%' />
Dear ".$contact->title." ".$contact->firstName." ".$contact->keyname.", </br>
        <p>Thank you for completing a new Gift Aid Declaration against your NYCOS account. Claiming Gift Aid on any donations and a portion of membership fees represents a signifciant support to
        NYCOS activities and we are grateful to you for taking the time to allow us to do so. It is your responsibility to let us know of any changes to your Gift Aid status or
        declaration, please do so via info@nycos.co.uk or create a new Declaration online if approrpriate.</p>
        Your declaration states: I am a UK taxpayer and understand that if I pay less Income Tax and/or Capital
        Gains Tax than the amount of Gift Aid claimed on all my donations in that tax year it is my responsibility to pay any difference.
        <p><i> Please notify us if you want to cancel this declaration, change your name or home address or no longer pay sufficient tax on
        your income and/or capital gains tax.</i></p><body><html>";

    }

    public function setPayMembershipMessage($contact,$amount){
        $this->subject = "Member Payment";
        $this->message = "<html><body><img title='Join Now' src='" . SIMPLE_WP_MEMBERSHIP_URL . "/images/nycos-email-logo.png' alt='NYCOS Header' width='100%' />
Dear ".$contact->title." ".$contact->firstName." ".$contact->keyname.", Thank you for your payment of ".$amount." towards <MEMBER (First Name)>'s
        <SCHEMENAME (Choir)> membership fees. You can view any remaining instalments and their due dates by logging into your web account and clciking on 'Memberships'.
        Your payments acre currently not eligible for Gift Aid. Should you wish to allow NYCOS to claim an extra 25p in the &pound; on a portion of your membership payments,
        you can complete a Declaration after logging in and selecting 'Support Us'.</body></html>";
    }


}