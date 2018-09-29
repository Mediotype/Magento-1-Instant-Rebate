<?php
class Mediotype_InstantRebate_Model_Convert_Adapter_Rebates// extends Mage_Dataflow_Model_Convert_Adapter_Abstract
{
    public function load()
    {
        return null;
    }

    public function save()
    {
        return null;
    }

    public function __construct()
    {
    }

    public function saveRow($rowData)
    {

        $this->_currentRecord = json_decode(json_encode($rowData));
        /** @var $validator Mediotype_Core_Helper_Mschema */
        $validator = Mage::helper('mediotype_core/mschema');
        $validator->LoadSchema(
            Mage::getModuleDir('', 'Mediotype_InstantRebate') . DS . "data" . DS . "importSchema.json"
        );

        $validator->Validate($this->_currentRecord);

        $productIds = array();
        foreach ($this->_currentRecord->product_skus as $sku) {
            $product = Mage::getModel('catalog/product')->loadByAttribute('sku', trim($sku));
            if ($product->getId()) {
                $productIds[] = $product->getId();
            }
        }

        $rebate = Mage::getModel('mediotype_instant_rebate/instantRebate');
        $data = array(
            'rebate_title' => $this->_currentRecord->rebate_title,
            'max_instant_rebate_uses' => $this->_currentRecord->max_instant_rebate_uses,
            'instant_rebate_amount' => $this->_currentRecord->instant_rebate_amount,
            'zip_codes' => $this->_currentRecord->zip_codes,
            'instant_rebate_sponsor' => $this->_currentRecord->instant_rebate_sponsor,
            'marketing_message' => $this->_currentRecord->marketing_message,
            'product_message' => $this->_currentRecord->product_message,
            'amount_exceeded_message' => $this->_currentRecord->amount_exceeded_message,
            'sponsor_logo_url' => $this->_currentRecord->sponsor_logo_url,
            'start_date' => $this->_currentRecord->start_date,
            'end_date' => $this->_currentRecord->end_date,
            'max_program_amount' => $this->_currentRecord->max_program_amount,
            'products' => $productIds
        );

        $rebate->setData($data)->save();

    }

}