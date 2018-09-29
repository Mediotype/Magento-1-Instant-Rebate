<?php
/**
 *
 *
 * @author  Mediotype
 */
class Mediotype_InstantRebate_Block_Checkout_Cart_Item_Renderer extends Mage_Core_Block_Template
{

    /**
     * Get Mage_Sales_Model_Quote_Item
     *
     * @return mixed
     */
    public function getItem()
    {
        $parent = $this->getParentBlock();
        if ($parent) {
            return $parent->getItem();
        }
    }

    /**
     *
     *
     * @return Mediotype_InstantRebate_Model_Resource_InstantRebate_Collection
     */
    public function filterProductRebatesByZip()
    {
        /** @var Mage_Sales_Model_Quote_Item $item */
        $item = $this->getItem();
        return Mage::helper('mediotype_instant_rebate')->getProductRebates($item->getSku());
    }

    public function getCustomerZip()
    {
        return Mage::helper('mediotype_instant_rebate')->getCustomerZip();
    }
}