<?php
require_once(SIMPLE_WP_MEMBERSHIP_PATH.'/lib/NycosAPI.php');
$nycosAPI = new NycosAPI();

$auth = SwpmAuth::get_instance();
$user_data = (array) $auth->userData;
$user_data['membership_level_alias'] = $auth->get('alias');
extract($user_data, EXTR_SKIP);
$contact = $nycosAPI->getAPI('contacts/'.$extra_info,'');

if ($_REQUEST["action"] == "add"){
    print_r($extra_info);
    print_r( $nycosAPI->postMemberGiftAid($extra_info));
     
}

$giftaids = $nycosAPI->getGiftAid($extra_info)->data;
//check postback values for add or delete
?>

<main class="site-main" id="main" role="main">
    <h5 class="section-title">
        Support Us
        <hr />
    </h5>
    <h1 class="page-header">Gift Aid Declaration</h1>
    <p> You can use this page to: </p>
    <ul>
        <li>
            check that you have an existing Gift Aid Declaration (<strong>GAD</strong>) that is in date.
        </li>
        <li>
            complete a new <strong>GAD</strong>.
        </li>
        <li>
            close your existing <strong>GAD</strong>(s). - Contact us at <a href:"mailto@info@nycos.co.uk"> Close your GAD</a>
        </li>
    </ul>
    <p>
        For more information on this scheme visit the <a target="_blank" href="http://www.hmrc.gov.uk/charities/gift-aid.htm">HRMC website</a> <small>(opens a new window)</small>.
    </p>
    <hr />
    <h4>Your Gift Aid Declarations:</h4>
    <br />
    <p>
        Please note that if the <strong>Effective to</strong> date is blank the <strong>GAD</strong> is good and will be valid indefinitely.
    </p>
    <fieldset>
        <table class="table table-striped table-bordered">
            <tbody>
                <tr>
                    <th style="text-align:left">Our&nbsp;unique&nbsp;ID</th>
                    <th style="text-align:left">Effective&nbsp;from</th>
                    <th style="text-align:left">Effective&nbsp;to</th>

                </tr>
            <?php foreach ($giftaids as $item) { ?>     
                <tr>
                    <td class="tdMid"><?= $item->declarationId ?></td>
                    <td class="tdMid"><?= ($item->effectiveFromDate)? date("F j Y", strtotime($item->effectiveFromDate)) : "" ?></td>
                    <td class="tdMid"><?= ($item->effectiveToDate)? date("F j Y", strtotime($item->effectiveToDate)) : "" ?>
                    </td>

                </tr>
                <?php } ?>
            </tbody>
        </table>
    </fieldset>
    <p id="ctl00_cp1_addGAD">
        <a type="submit" name="ctl00$cp1$add" href="/nycos-new-giftaid" id="ctl00_cp1_add" class="btn btn-lg btn-primary" >Add A GAD</a>
    </p>
</main>
<a href="/nycos-home" id="backHome" class="btn btn-primary">Back</a>