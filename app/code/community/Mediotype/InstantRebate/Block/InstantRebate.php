<?php
class Mediotype_InstantRebate_Block_InstantRebate extends Mage_Core_Block_Template
{
    /**
     * @return string
     */
    public function getRebatesUrl()
    {
        return Mage::helper('mediotype_instant_rebate')->getRebatesUrl();
    }

    public function getRebatesByZip($zip = null)
    {
        //grab customer session zip
        if(is_null($zip))
        {
            $zip = $this->getCustomerZip();
            //check to see if still null
            if(is_null($zip))
            {
                return null;
            }
        }
        //if we've got a zip, get the rebates
        $rebates = Mage::helper('mediotype_instant_rebate')->getRebates($zip);
        return $rebates;
    }

    public function getCustomerZip()
    {
        return Mage::helper('mediotype_instant_rebate')->getCustomerZip();
    }

    public function getCustomerRebateAvailable()
    {
        return Mage::helper('mediotype_instant_rebate')->getCustomerRebateAvailable();
    }
}