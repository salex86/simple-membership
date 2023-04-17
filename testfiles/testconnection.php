<?php
require_once('NycosAPI.php');
$nycosAPI = new NycosAPI();

$contactConsents = $nycosAPI->getAPI('contactconsent/?SerialNumber='.$extra_info,'');

$mailChecked ="";
$emailChecked = "";
foreach($contactConsents as $consent) {
    print "here";
}





?>