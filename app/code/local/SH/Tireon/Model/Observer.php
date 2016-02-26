<?php

/**
 * Class SH_Tireon_Model_Observer
 */
class SH_Tireon_Model_Observer
{
    /**
     * @event controller_action_predispatch
     * @param Varien_Event_Observer $observer
     */
    public function hookToControllerActionPreDispatch(Varien_Event_Observer $observer)
    {
        $action = $observer->getEvent()->getControllerAction()->getFullActionName();
        if($action == 'customer_account_login' || $action == 'customer_account_create') {
            Mage::dispatchEvent('customer_account_redirect', array('request' => $observer->getControllerAction()->getRequest()));
        }
    }

    /**
     * @event cms_block_save_before
     * @param Varien_Event_Observer $observer
     */
    public function hookToCustomerAccount(Varien_Event_Observer $observer)
    {
        Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getBaseUrl());
    }
}