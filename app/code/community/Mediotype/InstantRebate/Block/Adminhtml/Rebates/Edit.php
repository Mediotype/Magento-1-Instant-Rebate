<?php
class Mediotype_InstantRebate_Block_Adminhtml_Rebates_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'mediotype_instant_rebate';
        $this->_controller = 'adminhtml_rebates';

        $this->_updateButton('save', 'label', Mage::helper('mediotype_instant_rebate')->__('Save Rebate'));

        $model = $this->getModel();
        // CHECK TO SEE IF MODEL IS LOADED
        if ($model->getId()) {

            // ADD ON CLICK EVENT
            $this->_updateButton('delete', 'onclick', 'setLocation(\'' . $this->getUrl('*/*/toggleRebate', array('rebate_id' => $model->getId())) . '\')');

            // IF THE REBATE IS ACTIVE
            if ($model->getData('active') == 1) {
                $this->_updateButton('delete', 'label', Mage::helper('mediotype_instant_rebate')->__('Disable Rebate'));
            } else {
                // THE REBATE IS DISABLED
                $this->_updateButton('delete', 'label', Mage::helper('mediotype_instant_rebate')->__('Enable Rebate'));
                $this->_updateButton('delete', 'class', 'success');
            }
        }

    }

    public function getHeaderText()
    {
        $model = $this->getModel();
        if ($model->getId()) {
            return $model->getData('instant_rebate_sponsor') . " - " . $model->getData('rebate_title');
        } else {
            return "New Instate Rebate";
        }
    }

    public function getModel()
    {
        $rebateId = $this->getRequest()->getParam('id');
        $model = Mage::getModel('mediotype_instant_rebate/instantRebate')->load($rebateId);
        return $model;
    }

}