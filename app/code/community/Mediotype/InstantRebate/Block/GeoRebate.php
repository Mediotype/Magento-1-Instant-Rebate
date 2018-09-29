<?php
/**
 * Class Mediotype_InstantRebate_Block_GeoRebate
 *
 * @author  Mediotype
 */
class Mediotype_InstantRebate_Block_GeoRebate extends Mage_Core_Block_Template
{

    /** @var $helper Mediotype_InstantRebate_Helper_Data */
    protected $helper;

    protected function _construct(){
        parent::_construct();
        $this->helper = Mage::helper('mediotype_instant_rebate');
    }

    /**
     * @return bool True if we should show prompt, false if we shouldn't show prompt
     */
    public function showRebatePrompt(){

        $geoPromptOnly = $this->useGeo();
        if($geoPromptOnly == false){
            //Prompt for zip code if geo location prompt only is set to no
            return true;
        }

        $zip = $this->getCustomerZip();
        if(!is_null($zip)){
            //Geo location preffered, no zip, don't prompt
            return true;
        }

        $geoRun = $this->helper->getGeoRun();
        $available = $this->getCustomerRebateAvailable();

        if($available && $geoRun){
            return true;
        }

        if($this->isGeoZipViable() && !$geoRun){
            return true;
        }

        return false;
    }

    public function getCustomerZip()
    {
        return $this->helper->getCustomerZip();
    }

    /**
     * @return bool|mixed
     */
    public function getCustomerRebateAvailable()
    {
        return $this->helper->getCustomerRebateAvailable();
    }

    /**
     * @return bool
     */
    public function isGeoZipViable()
    {
        if ($this->helper->isGeoZipViable()) {
            $this->getCustomerSession()->setData('rebate_available', true);
            $this->getCustomerSession()->setData('geo_run', true);
            return true;
        } else {
            $this->getCustomerSession()->setData('rebate_available', false);
            return false;
        }
    }

    /**
     * @return bool
     */
    public function useGeo()
    {
        return $this->helper->isGeoActive();
    }

    /**
     * Get customer session model instance
     *
     * @return Mage_Customer_Model_Session
     */
    protected function getCustomerSession()
    {
        return Mage::getSingleton('customer/session');
    }
}