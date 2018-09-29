<?php
class Mediotype_InstantRebate_Block_Adminhtml_Form_Import_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{


    public function __construct()
    {
        parent::__construct();
        $this->setId("mediotype_instant_rebate_import");

    }

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/importCsv'),
            'method' => 'post',
            'enctype' => 'multipart/form-data'
        ));

        $fieldset = $form->addFieldset(
            'base_fieldset',
            array(
                'legend' => Mage::helper('mediotype_instant_rebate')->__('File Upload'),
                'class' => 'fieldset-wide',
            )
        );
        $fieldset->addType('configurable', 'Mediotype_InstantRebate_Block_Adminhtml_Form_Element_Configurable');
        $fieldset->addField(
            'csv_file',
            'file',
            array(
                'name' => 'csv_file',
                'label' => Mage::helper('mediotype_instant_rebate')->__('CSV File'),
                'title' => Mage::helper('mediotype_instant_rebate')->__('CSV File'),
            )
        );


        $fieldset->addField(
            'submit',
            'configurable',
            array(
                'name' => 'submit',
                'value' => '<span><span><span>' . Mage::helper('mediotype_instant_rebate')->__(
                        'Run Import'
                    ) . '</span></span></span>',
                'class' => 'save success',
                'htmlTag' => 'button',
                'onclick'   => 'editForm.submit();',
            )
        );

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();

    }
}