<?php
class Mediotype_InstantRebate_Block_Adminhtml_Rebates_Edit_Tab_Info extends Mage_Core_Block_Template {

    public function __construct()
    {
        parent::__construct();
        $this->setId('rebate_products_info');
        $this->setTemplate('mediotype/instantrebate/tabs/info.phtml');
    }

    public function getModel()
    {
        $rebateId = $this->getRequest()->getParam('id');
        $model = Mage::getModel('mediotype_instant_rebate/instantRebate')->load($rebateId);
        return $model;
    }

    public function getRebateProductGrid()
    {
        return $this->getLayout()->createBlock('mediotype_instant_rebate/adminhtml_rebates_edit_tab_info_grid')->toHtml();
    }

    public function getUpdateProductInfoUrl()
    {
        return $this->getUrl('*/*/updateInfoBlock');
    }

    public function getProductInfo()
    {
        return $this->getLayout()->createBlock('mediotype_instant_rebate/adminhtml_rebates_edit_tab_info_products')->toHtml();
    }
}
/**
 * $fieldset->addField(
'max_instant_rebate_uses',
'text',
array(
'name' => 'max_instant_rebate_uses',
'index' => 'max_instant_rebate_uses',
'label' => Mage::helper('mediotype_instant_rebate')->__('Max Uses Per Customer'),
'title' => Mage::helper('mediotype_instant_rebate')->__('Max Uses Per Customer'),
)
);
$fieldset->addField(
'instant_rebate_amount',
'text',
array(
'name' => 'instant_rebate_amount',
'index' => 'instant_rebate_amount',
'label' => Mage::helper('mediotype_instant_rebate')->__('Instant Rebate Amount'),
'title' => Mage::helper('mediotype_instant_rebate')->__('Instant Rebate Amount'),
)
);
 */