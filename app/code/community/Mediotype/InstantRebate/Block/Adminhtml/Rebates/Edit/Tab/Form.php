<?php
class Mediotype_InstantRebate_Block_Adminhtml_Rebates_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{


    public function __construct()
    {
        parent::__construct();
        $this->setId("mediotype_instant_rebate");

    }

    public function getModel()
    {
        $rebateId = $this->getRequest()->getParam('id');
        $model = Mage::getModel('mediotype_instant_rebate/instantRebate')->load($rebateId);
        return $model;
    }

    protected function _prepareForm()
    {
        $customWysiwygSettings = array(
            "add_images"    => false,
            "hidden"        => true,
            "add_variables" => false,
            "add_widgets"   => false,
        );
        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig($customWysiwygSettings);

        $model = $this->getModel();

        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save'),
            'method' => 'post'
        ));


        $fieldset = $form->addFieldset(
            'base_fieldset',
            array(
                'legend' => Mage::helper('mediotype_instant_rebate')->__('Rebate Information'),
                'class' => 'fieldset-wide',
            )
        );
        $sponsorFieldset = $form->addFieldset(
            'sponsor_fieldset',
            array(
                'legend' => Mage::helper('mediotype_instant_rebate')->__('Sponsor Information'),
                'class' => 'fieldset-wide',
            )
        );

        $sponsorFieldset->addType('sponsor', 'Mediotype_InstantRebate_Block_Adminhtml_Form_Element_Sponsor');

        $messageFieldset = $form->addFieldset(
            'messaging_fieldset',
            array(
                'legend' => Mage::helper('mediotype_instant_rebate')->__('Customer Messages'),
                'class' => 'fieldset-wide',
            )
        );

        if ($model->getId()) {
            $fieldset->addField(
                'id',
                'hidden',
                array(
                    'name' => 'id',
                    'index' => 'id'
                )
            );
            $this->setTitle($model->getData('rebate_title'));
        } else {
            $this->setTitle('New Rebate');
        }

        $fieldset->addType('rebate_products', 'Mediotype_InstantRebate_Block_Adminhtml_Form_Element_Products');
        $fieldset->addField(
            'products',
            'rebate_products',
            array(
                'name'  => 'products',
                'id'    => 'products',
                'index' => 'products'
            )
        );

        $fieldset->addField(
            'rebate_title',
            'text',
            array(
                'name' => 'rebate_title',
                'index' => 'rebate_title',
                'label' => Mage::helper('mediotype_instant_rebate')->__('Rebate Title'),
                'title' => Mage::helper('mediotype_instant_rebate')->__('Rebate Title')
            )
        );
        $fieldset->addField(
            'start_date',
            'date',
            array(
                'name' => 'start_date',
                'index' => 'start_date',
                'label' => Mage::helper('mediotype_instant_rebate')->__('Start Date'),
                'title' => Mage::helper('mediotype_instant_rebate')->__('Start Date'),
                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
            )
        );

        $fieldset->addField(
            'end_date',
            'date',
            array(
                'name' => 'end_date',
                'index' => 'end_date',
                'label' => Mage::helper('mediotype_instant_rebate')->__('End Date'),
                'title' => Mage::helper('mediotype_instant_rebate')->__('End Date'),
                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
            )
        );

        $fieldset->addField(
            'zip_codes',
            'text',
            array(
                'name' => 'zip_codes',
                'index' => 'zip_codes',
                'label' => Mage::helper('mediotype_instant_rebate')->__('Zip Codes'),
                'title' => Mage::helper('mediotype_instant_rebate')->__('Zip Codes'),
            )
        );
        $fieldset->addField(
            'max_program_amount',
            'text',
            array(
                'name' => 'max_program_amount',
                'index' => 'max_program_amount',
                'label' => Mage::helper('mediotype_instant_rebate')->__('Max Program Amount'),
                'title' => Mage::helper('mediotype_instant_rebate')->__('Max Program Amount'),
            )
        );

        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'multiselect', array(
                'name' => 'stores[]',
                'label' => Mage::helper('mediotype_instant_rebate')->__('Store View'),
                'title' => Mage::helper('mediotype_instant_rebate')->__('Store View'),
                'required' => true,
                'values' => Mage::getSingleton('adminhtml/system_store')
                        ->getStoreValuesForForm(false, true),
            ));
        }
        else {
            $fieldset->addField('store_id', 'hidden', array(
                'name' => 'stores[]',
                'value' => Mage::app()->getStore(true)->getId()
            ));
        }

        $sponsorFieldset->addField(
            'instant_rebate_sponsor',
            'text',
            array(
                'name' => 'instant_rebate_sponsor',
                'index' => 'instant_rebate_sponsor',
                'label' => Mage::helper('mediotype_instant_rebate')->__('Sponsor'),
                'title' => Mage::helper('mediotype_instant_rebate')->__('Sponsor'),
            )
        );
        $sponsorFieldset->addField(
            'sponsor_logo_url',
            'sponsor',
            array(
                'name' => 'sponsor_logo_url',
                'index' => 'sponsor_logo_url',
                'label' => Mage::helper('mediotype_instant_rebate')->__('Sponsor Logo'),
                'title' => Mage::helper('mediotype_instant_rebate')->__('Sponsor Logo'),
            )
        );

        $messageFieldset->addField(
            'marketing_message',
            'editor',
            array(
                'name' => 'marketing_message',
                'index' => 'marketing_message',
                'label' => Mage::helper('mediotype_instant_rebate')->__('Marketing Message'),
                'title' => Mage::helper('mediotype_instant_rebate')->__('Marketing Message'),
                'config' => $wysiwygConfig
            )
        );
        $messageFieldset->addField(
            'product_message',
            'editor',
            array(
                'name' => 'product_message',
                'index' => 'product_message',
                'label' => Mage::helper('mediotype_instant_rebate')->__('Product Message'),
                'title' => Mage::helper('mediotype_instant_rebate')->__('Product Message'),
                'config' => $wysiwygConfig
            )
        );
        $messageFieldset->addField(
            'amount_exceeded_message',
            'editor',
            array(
                'name' => 'amount_exceeded_message',
                'index' => 'amount_exceeded_message',
                'label' => Mage::helper('mediotype_instant_rebate')->__('Amount Exceeded Message'),
                'title' => Mage::helper('mediotype_instant_rebate')->__('Amount Exceeded Message'),
                'config' => $wysiwygConfig
            )
        );

        $form->setValues($model->getData());

        $this->setForm($form);

        return parent::_prepareForm();

    }
}