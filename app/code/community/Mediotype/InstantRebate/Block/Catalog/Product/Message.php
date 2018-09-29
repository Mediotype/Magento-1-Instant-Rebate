<?php
/**
 * Displays message on product page
 *
 * @author  Mediotpe
 */
class Mediotype_InstantRebate_Block_Catalog_Product_Message extends Mage_Catalog_Block_Product_View
{

    /**
     * Check to see if product should show rebate message, references customer zip code
     *
     * @return Mediotype_InstantRebate_Model_Resource_InstantRebate_Collection
     */
    public function filterProductRebatesByZip()
    {
        return Mage::helper('mediotype_instant_rebate')->getProductRebates($this->getProduct()->getSku());
    }

}