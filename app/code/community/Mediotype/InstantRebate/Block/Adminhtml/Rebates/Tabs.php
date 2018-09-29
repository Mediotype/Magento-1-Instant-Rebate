<?php

class Mediotype_InstantRebate_Block_Adminhtml_Rebates_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('rebateTabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('mediotype_instant_rebate')->__('Instant Rebate'));
    }

    protected function _prepareLayout()
    {
        $return = parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        return $return;
    }

    protected function _beforeToHtml()
    {
        $this->addTab(
            'general',
            array(
                'label' => Mage::helper('mediotype_instant_rebate')->__('General Information'),
                'title' => Mage::helper('mediotype_instant_rebate')->__('General Information'),
                'content' => $this->getLayout()->createBlock('mediotype_instant_rebate/adminhtml_rebates_edit_tab_form')->toHtml(),
                'active' => true
            )
        );

        $this->addTab(
            'products_tab',
            array(
                'label' => Mage::helper('mediotype_instant_rebate')->__('Associated Products'),
                'title' => Mage::helper('mediotype_instant_rebate')->__('Associated Products'),
                'content' => $this->getLayout()->createBlock('mediotype_instant_rebate/adminhtml_rebates_edit_tab_info')->toHtml(),
            )
        );

        return parent::_beforeToHtml();
    }
}
