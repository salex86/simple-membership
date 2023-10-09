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

function endsWith( $haystack, $needle ) {
    $length = strlen( $needle );
    if( !$length ) {
        return true;
    }
    return substr( $haystack, -$length ) === $needle;
}


?>

<main class="site-main" id="main" role="main">
    <h5 class="section-title">
        Join Membership<hr/>
    </h5>
  
    <h3>Available Memberships</h3>
    <fieldset>
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th scope="col">Membership details</th>
                </tr>
            </thead>
            <tbody>
                <?php
                   $giftaids = $nycosAPI->getGiftAid($contact->serialNumber)->data;
                    foreach($giftaids as $key => $giftaid){
                        if ((strtotime($giftaid->effectiveFromDate) < date('Y-m-d') and (strtotime($giftaid->effectiveToDate) > date('Y-m-d'))) or (strtotime($giftaid->effectiveFromDate) < date('Y-m-d'))  ){

                        } else {
                            unset($giftaids[$key]);
                        }
                    }

                // check if empty giftaids and show add
                if (empty($giftaids) or empty($contact->serialNumber)) {
                 $giftAid= "Yes";
                 } else {
                    $giftAid= "No";
                }
                echo $giftAid;
                foreach($idArray as $serialNumber){
                    $item = $nycosAPI->getProfiles($serialNumber)->data;
                foreach($item as $profile) {
                        $memberships = $nycosAPI->getMemberships($serialNumber)->data;
                        $membershipId = "";
                        $latestDate = "2017-01-01";
                        foreach($memberships as $membership) {

                            if (endsWith($membership->schemeName,"Choir")){
                                if (strtotime($membership->expiryDate) > strtotime($latestDate) ) {

                                    $membershipId = $membership->membershipDetailId;
                                }
                                $latestDate = $membership->expiryDate;
                            }
                        }
                    //print_r($profile);
                    if (($profile->category == "Regional Choirs") and ($profile->name == "Joining Band" or $profile->name == "Renewal Band")) {
                        $fee = "";
                        $instalment= "";
                        $childContact=new Contact($nycosAPI->getAPI('contacts/'.$serialNumber.'?IncludeAddresses=true',''));
                        //print_r($childContact);
                        $scheme= $nycosAPI->getMembershipScheme(explode('-',$profile->value)[0]);
                        //print_r($scheme);
                        foreach($scheme->bandsAvailable as $band){
                            if ($band->bandId == $profile->value){
                                $fee = $band->fixedFees[0]->joiningAmount;
                                $instalment = round($fee/4,2);
                            }
                        }
                ?>
                <tr>
                    <td class="tdWide">
                        <h4>
                            <?= $scheme->membershipType ?>
                            <?php if ($profile->name == "Renewal Band") {
                                      $formUrl = "https://www.nycos.co.uk/regional-choir-renewal-form";
                                      $buttonTitle = "Renew";
                                  } else {
                                      $formUrl = "https://www.nycos.co.uk/join-new-choir-form";
                                      $buttonTitle = "Join";
                                  } ?>

                            <small class="small"><?= explode('-',$profile->value)[2] ?></small>
                        </h4>      
                        <p><?= $childContact->firstName ?> <?= $childContact->keyname ?>  </p>
                        <form action="<?= $formUrl ?>" method="GET">
                            <input type="hidden" name="serialNumber" value="<?= $serialNumber ?>" />
                            <input type="hidden" name="firstName" value="<?= $childContact->firstName?>" />
                            <input type="hidden" name="surname" value="<?= $childContact->keyname?>" />
                            <input type="hidden" name="dob" value="<?= $newDate = date("d/m/Y", strtotime($childContact->dateOfBirth)); ?>" />
                            <input type="hidden" name="gender" value="<?= $childContact->gender?>" />
                            <input type="hidden" name="instalment" value="<?= $instalment ?>" />
                            <input type="hidden" name="fee" value="<?= $fee ?>" />
                            <input type="hidden" name="memberType" value="<?= $scheme->membershipType ?>" />
                            <input type="hidden" name="newMemberType" value="<?= explode('-',$profile->value)[2] ?>" />
                            <input type="hidden" name="parentSerialNumber" value="<?= $contact->serialNumber?>" />
                            <input type="hidden" name="parentTitle" value="<?= $contact->title?>" />
                            <input type="hidden" name="parentFirstName" value="<?= $contact->firstName?>" />
                            <input type="hidden" name="parentSurname" value="<?= $contact->keyname?>" />
                            <input type="hidden" name="parentAddress" value="<?= $contact->address?>" />
                            <input type="hidden" name="parentTown" value="<?= $contact->town?>" />
                            <input type="hidden" name="parentPostcode" value="<?= $contact->postcode?>" />
                            <input type="hidden" name="parentEmail" value="<?= $contact->emailAddress?>" />
                            <input type="hidden" name="parentMobile" value="<?= $contact->mobileNumber?>" />
                            <input type="hidden" name="parentTelephone" value="<?= $contact->dayTelephone?>" />
                            <input type="hidden" name="TAXSTATUS" value="<?= $giftAid ?>" />
                            <input type="hidden" name="membershipId" value="<?= $membershipId ?>" /> 
                            <input type="hidden" name="mobileNumber" value="<?= $childContact->addresses[0]->mobileNumber ?>" />
                            <input type="hidden" name="dayTelephone" value="<?= $childContact->addresses[0]->dayTelephone ?>" />
                            <input type="hidden" name="eveningTelephone" value="<?= $childContact->addresses[0]->eveningTelephone ?>" />
                            <input type="hidden" name="renewalBandId" value="<?= $profile->value?>" />

                            <input type="submit" class="btn btn-primary" value="<?= $buttonTitle ?>" />
                        </form>
                      
                         </td>
                </tr>
                <?php } } } ?>
            </tbody>
        </table>
    </fieldset>
    
</main>
<a href="/nycos-home" id="backHome" class="btn btn-primary">Back</a>