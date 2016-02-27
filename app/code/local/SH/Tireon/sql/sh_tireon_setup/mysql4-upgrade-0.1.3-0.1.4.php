<?php

/* @var $installer Mage_Core_Model_Resource_Setup*/
$installer = $this;

$installer->startSetup();

$installer->setConfigData('wishlist/general/active', 0);
$installer->setConfigData('sendfriend/email/enabled', 0);
$installer->setConfigData('shipping/option/checkout_multiple', 0);
$installer->setConfigData('carriers/freeshipping/active', 1);
$installer->setConfigData('payment/ccsave/active', 0);

$installer->setConfigData('payment/checkmo/active', 1);
$installer->setConfigData('payment/checkmo/title', 'Наличные, пластиковые карточки');

$installer->endSetup();