<?php
class Mediotype_InstantRebate_Block_Adminhtml_Rebates extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'mediotype_instant_rebate';
        $this->_controller = 'adminhtml_rebates';
        $this->_headerText = $this->__('Instant Rebates');
        $this->_addButtonLabel = "Create New Rebate";
        parent::__construct();

    }

    public function getCreateUrl()
    {
        return $this->getUrl('mediotype_instantrebate/adminhtml_index/new');
    }
}