<?php
require_once('lib/SagePay.php');
require_once('lib/NycosAPI.php');

$nycosAPI = new NycosAPI();
print "Bearer : " .$nycosAPI->getBearer();

$sagePay = new SagePay();
$sagePay->setCurrency('GBP');
$sagePay->setAmount('10');
$sagePay->setDescription("[description of the order");
$sagePay->setVendorTxCode(rand(0,32000)*rand(0,32000));
$sagePay->setAmount('100');
$sagePay->setDescription('Lorem ipsum');
$sagePay->setBillingSurname('Mustermann');
$sagePay->setBillingFirstnames('Max');
$sagePay->setBillingCity('Cologne');
$sagePay->setBillingPostCode('50650');
$sagePay->setBillingAddress1('Bahnhofstr. 1');
$sagePay->setBillingCountry('de');
$sagePay->setDeliverySameAsBilling();
$sagePay->setSuccessURL('https://www.yoururl.com/success.php');
$sagePay->setFailureURL('https://www.yoururl.org/fail.php');

?>

<form method="POST" id="SagePayForm" action="https://test.sagepay.com/gateway/service/vspform-register.vsp">
    <input type="hidden" name="VPSProtocol" value="3.00" />
    <input type="hidden" name="TxType" value="PAYMENT" />
    <input type="hidden" name="Vendor" value="NYCOS" />
    <input type="hidden" name="Crypt" value="<?php echo $sagePay->getCrypt(); ?>" />
    <input type="submit" value="continue to SagePay" />
</form>