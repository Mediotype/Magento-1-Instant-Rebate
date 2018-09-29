<?php
class Mediotype_InstantRebate_Block_Adminhtml_Form_Import_Container extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'mediotype_instant_rebate';
        $this->_controller = 'adminhtml_form_import';
        parent::__construct();

        $this->_removeButton('save');
    }


    public function getHeaderText(){
        return "Import Instant Rebate CSV";
    }

}