<?php
require_once(SIMPLE_WP_MEMBERSHIP_PATH.'/lib/NycosAPI.php');
$nycosAPI = new NycosAPI();

$auth = SwpmAuth::get_instance();
$user_data = (array) $auth->userData;
$user_data['membership_level_alias'] = $auth->get('alias');
extract($user_data, EXTR_SKIP);
$contact = $nycosAPI->getAPI('contacts/'.$extra_info,'');

$bookings = $nycosAPI->getAPI('eventBooking/?SerialNumber='.$contact->serialNumber.'&EventId='.$_REQUEST['eventId'],'');
$eventBooking = new EventBooking();

?> 

<main class="site-main" id="main" role="main">
    <h5 class="section-title">
        View current Bookings
        <hr />
    </h5>
    <h1 class="page-header">Event Bookings</h1>
    
    <fieldset>
        <table class="table table-striped table-bordered">
            <tbody>
                <tr>
                    <th style="text-align:left">Booking Id</th>
                    <th style="text-align:left">Booking Status</th>
                    <th style="text-align:left">Name</th>
                    <th style="text-align:left">Start Date</th>
                    <th style="text-align:left">Tickets</th>
                    <th> Balance </th>
                    <th> Make Payment </th>           
                </tr>
                <?php foreach ($bookings->data as $item) {
                          $data = $nycosAPI->getEvent($item->eventId);
                          $event = new Events($data);
                ?>     
                <tr>
                    <td class="tdMid"><?= $item->bookingId ?></td>
                    <td class="tdMid"><?= $item->bookingStatus ?></td>
                    <td class="tdMid">
                        <?= $event->eventName ?>
                    </td>
                    <td class="tdMid">
                        <?= ($event->startDate)? date("F j Y", strtotime($event->startDate)): ""  ?>
                    </td>
                    <td class="tdMid"><?= $item->numberOfPlaces ?>
                    </td>
                    <td><?php empty($item->outstanding)? print "Paid": print "&pound; ". $item->outstanding  ?></td>
                    <td><?php if (!empty($item->outstanding)) { ?>
                        <form action="/NYCOS-Booking-review?bookingId=<?= $item->bookingId ?>">
                            <input class="form-control" type="text" name="amount" placeholder="Payment amount" />
                            <input type="hidden" name="bookingId" value="<?= $item->bookingId ?>" />
                            <button id="payBtn" class="btn btn-primary" type="submit">Pay</button>
                        </form>                    
                    <?php } ?> </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </fieldset>
  
</main>
<a href="/nycos-home" id="backHome" class="btn btn-primary">Back</a>
