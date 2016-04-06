<?php
require_once '../app/Mage.php';
Mage::app();

$cron = new SH_Tireon_Model_Cron_Task();
$cron->callCreate();