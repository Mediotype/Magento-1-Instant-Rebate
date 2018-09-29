<?php
/**
 * Controller for Rebate Program
 *
 * @author  R. Dale Owen    <dale@mediotype.com>
 */
class Mediotype_InstantRebate_InstantRebateController extends Mage_Core_Controller_Front_Action
{
    /**
     * Get customer session model instance
     *
     * @return Mage_Checkout_Model_Session
     */
    protected function getCustomerSession()
    {
        return Mage::getSingleton('customer/session');
    }

    /**
     * Get checkout session model instance
     *
     * @return Mage_Checkout_Model_Session
     */
    protected function getCheckoutSession()
    {
        return Mage::getSingleton('checkout/session');
    }

    /**
     * @param $zip string of 5 numbers
     *
     */
    public function getRebatesAction()
    {
        $response = new stdClass();
        $rebatezip = $this->getRequest()->getParam('rebatezip', '');
        //make sure we've got numbers in our string
        //ideally done before sent to this, but we have to check
        if ($rebatezip == '') {
            $this->getResponse()->setRawHeader('HTTP/1.0 500 Invalid Zip Code');
        } else {
            // Get response html
            if($this->hasRebates($rebatezip))
            {
                $this->getCustomerSession()->setData("rebate_available", true);
                $this->getCustomerSession()->setData("rebate_zip", $rebatezip);
                $response->result = 'success';
                $block = $this->getLayout()->createBlock('mediotype_instant_rebate/instantRebate')->setTemplate('mediotype/instantrebate/rebates.phtml');
                $this->getResponse()->setBody($block->toHtml());
            } else {
                $this->getCustomerSession()->setData("rebate_available", false);
                $this->getResponse()->setRawHeader('HTTP/1.0 500 Invalid Zip Code');
            }
        }
        return;
    }

    /**
     *
     */
    public function setOptInAjaxAction()
    {
        $optin = $this->getRequest()->getParam('optin', '');
        $this->getCheckoutSession()->setData('optin',$optin);
    }

    /**
     * @param $zip
     * @return bool
     */
    public function hasRebates($zip){
        return Mage::helper('mediotype_instant_rebate')->hasRebates($this->getRequestZip($zip));
    }

    /**
     * @param $zip
     * @return string
     */
    protected function getRequestZip($zip){
        $rebatezip = trim($zip);
        if (strlen($rebatezip) > 5) {
            $rebatezip = substr($rebatezip, 0, 5);
        }

        return $rebatezip;
    }

}