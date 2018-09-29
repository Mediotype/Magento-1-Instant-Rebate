<?php
class Mediotype_InstantRebate_Block_Adminhtml_Rebates_Edit_Tab_Info_Products extends Mage_Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('rebate_products_info');
        $this->setTemplate('mediotype/instantrebate/tabs/info/products.phtml');
    }

    public function getRebateProducts()
    {
        return $this->getModel()->getProductCollection();
    }

    /**
     * @return Mediotype_InstantRebate_Model_InstantRebate
     */
    public function getModel()
    {
        $rebateId = $this->getRequest()->getParam('id');
        $model = Mage::getModel('mediotype_instant_rebate/instantRebate')->load($rebateId);
        return $model;
    }
}