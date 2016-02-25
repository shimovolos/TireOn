<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->setConfigData('currency/options/base', 'BYR');
$installer->setConfigData('currency/options/default', 'BYR');
$installer->setConfigData('currency/options/allow', 'BYR');

$installer->setConfigData('general/country/default', 'RU');

$installer->setConfigData('general/locale/firstday', 1);
$installer->setConfigData('general/locale/weekend', '0,6');
$installer->setConfigData('general/locale/code', 'ru_RU');
$installer->setConfigData('general/locale/timezone', 'Europe/Minsk');

$installer->endSetup();