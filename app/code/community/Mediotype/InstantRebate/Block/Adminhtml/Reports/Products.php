<?php
class Mediotype_InstantRebate_Block_Adminhtml_Reports_Customers extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'mediotype_instant_rebate';
        $this->_controller = 'adminhtml_reports_products';
        $this->_headerText = $this->__('Product Sales By Rebate');
        parent::__construct();
        $this->_removeButton('add');
        $this->_removeButton('delete');
    }

}