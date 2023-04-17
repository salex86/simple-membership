<?php
require_once(SIMPLE_WP_MEMBERSHIP_PATH.'/lib/NycosAPI.php');
$nycosAPI = new NycosAPI();

$auth = SwpmAuth::get_instance();
$user_data = (array) $auth->userData;
$user_data['membership_level_alias'] = $auth->get('alias');
extract($user_data, EXTR_SKIP);
$contact = $nycosAPI->getAPI('contacts/'.$extra_info,'');

$communications = $nycosAPI->getCommunications($extra_info);

?>

<main class="site-main" id="main" role="main">

    <div class="row hide">
       
        <div class="col-sm-6">
           
            <p>
                View the Communications you have recieved from NYCOS.
            </p>

        </div>
    </div>
    <h3>Your Communications</h3>
     <fieldset>
        <table class="table">
            <tbody>
                <tr>
                    <th scope="col">Type</th>
                    <th scope="col" id="amount">Subject</th>
                    <th scope="col" id="amount">Date</th>
                    <th scope="col" id="link">Download</th>
                </tr>
                <?php foreach($communications as $com){  
                          $com = new Communication($com); ?> 
                
                <tr>
                    <td>
                        <?= $com->notes ?>
                       
                    </td>
                    <td>
                        <?= $com->subject ?>
                    </td>
                    <td class="tdWide">
                         <?= date("F j Y", strtotime($com->dateOfCommunication)) ?> 
                       
                    </td>
                    <td > <a href="/nycos-download?commId=<?= $com->communicationId ?>"> Download </a> </td>
                </tr>
                <?php } ?>

            </tbody>
        </table>
    </fieldset>
   


</main>
<a href="/nycos-home" id="backHome" class="btn btn-primary">Back</a>