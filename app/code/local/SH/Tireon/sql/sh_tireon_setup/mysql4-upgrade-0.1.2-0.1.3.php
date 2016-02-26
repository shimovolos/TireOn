<?php

/* @var $installer Mage_Core_Model_Resource_Setup*/
$installer = $this;

$installer->startSetup();

$installer->setConfigData('design/theme/template', 'tireon');
$installer->setConfigData('design/theme/skin', 'tireon');
$installer->setConfigData('design/theme/layout', 'tireon');
$installer->setConfigData('design/theme/default', 'tireon');

$installer->endSetup();