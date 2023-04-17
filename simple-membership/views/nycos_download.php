<?php
require_once(SIMPLE_WP_MEMBERSHIP_PATH.'/lib/NycosAPI.php');
$nycosAPI = new NycosAPI();

$auth = SwpmAuth::get_instance();
$user_data = (array) $auth->userData;
$user_data['membership_level_alias'] = $auth->get('alias');
extract($user_data, EXTR_SKIP);
$contact = $nycosAPI->getAPI('contacts/'.$extra_info,'');

$attachment = $nycosAPI->getAttachments($_REQUEST["commId"])[0];

//we have the filebytes now force the download

$decoded = base64_decode($attachment->attachmentBytes);

$fileName = $attachment->description;

if (headers_sent()) throw new Exception('Headers sent.');
while (ob_get_level() && ob_end_clean());
if (ob_get_level()) throw new Exception('Buffering is still active.');
flush();
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.$fileName.'"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . strlen($decoded));

echo $decoded;
exit;


?>

