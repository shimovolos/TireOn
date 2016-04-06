<?php

/* @var $installer Mage_Core_Model_Resource_Setup*/
$installer = $this;

$installer->startSetup();

$installer->setConfigData('sh_tireon_settings/general/custom_product_sku', 'custom_product_sku_for_tyres');

$installer->endSetup();