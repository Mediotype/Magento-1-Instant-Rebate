<?php

define('BR', "\r\n");
echo BR;

require_once('app/Mage.php');
Mage::app()->setCurrentStore(0);

$orderId = 201;
$startingProductsArray = array(231, 232);
$rebateConfig = array(
    "rebate_title" => 'test title',
    "max_instant_rebate_uses" => 5,
    "instant_rebate_amount" => 100,
    "zip_codes" => '80027, 80233',
    "instant_rebate_sponsor" => 'Mediotype',
    "marketing_message" => 'Marketing Message Place Holder',
    "product_message" => 'Product Message Place Holder',
    "amount_exceeded_message" => 'Amount Exceed Message',
    "sponsor_logo_url" => null,
    "start_date" => '2014-09-01 00:00:00',
    "end_date" => '2020-09-01 00:00:00',
    "max_program_amount" => 100 * 5 * 2,
    "shopping_cart_rule_id" => null,
    "products" => $startingProductsArray
);
/** @var Mediotype_InstantRebate_Model_InstantRebate $rebate */
echo "BEFORE Saved" . BR;
$rebate = Mage::getModel('mediotype_instant_rebate/instantRebate');
$rebate->setData($rebateConfig);
$rebate->save();
echo "Rebate Saved" . BR;
$rebate->load($rebate->getId());

$numRecords = $rebateConfig['max_program_amount'] / $rebateConfig['instant_rebate_amount'];
echo "Number of records needed to max out rebate " . $numRecords . BR;
$recordCount = 0;
$conversionModel = Mage::getModel('mediotype_instant_rebate/conversions');
$conversionModelData = array(
    "order_id" => $orderId,
    "instant_rebate_amount_applied" => $rebateConfig['instant_rebate_amount'],
    "instant_rebate_id" => $rebate->getId()
);
while ($recordCount < $numRecords - 1) {
    echo "Creating Record " . $recordCount . " of " . $numRecords . BR;
    $conversionModel->setData($conversionModelData);
    $conversionModel->save();
    $recordCount++;
}

echo "Rebate ALmost Maxed Out! " . $rebate->getUsedAmount() . " out of " . $rebate->getData('max_program_amount') . BR;

$rebate->revalidate();

echo "Rebate data: " . BR;
var_dump($rebate->getData('active'));

if ($rebate->isActive()) {
    echo "Rebate Is Still Active!" . BR;
} else {
    echo "Something Went Wrong, The rebate isn't active." . BR;
}

echo "Added another conversion model" . BR;
$conversionModel->setData($conversionModelData);
$conversionModel->save();

echo "Rebate Maxed Out! " . $rebate->getUsedAmount() . " out of " . $rebate->getData('max_program_amount') . BR;

$rebate->revalidate();
$rebate->load($rebate->getId());
echo "Rebate Active Value:." . BR;
var_dump($rebate->getData('active'));
if (!$rebate->isActive()) {
    echo "The rebate isn't active." . BR;
} else {
    echo "Something Went Wrong, Rebate Is Still Active!" . BR;
}
array_shift($startingProductsArray);
$startCount = count($rebate->getData('products'));
$rebate->setData('products', array($startingProductsArray));
$rebate->save();
$rebate->load($rebate->getId());

if(count($rebate->getData('products')) != ($startCount - 1)){
    echo "Something went wrong updating associated products!" . BR;
} else {
    echo "Updating associated products worked as expected" . BR;
}
var_dump($rebate->getData());

//$rebate

//var_dump($test);