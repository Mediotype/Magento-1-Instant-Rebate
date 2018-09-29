<?php

class Mediotype_InstantRebate_Model_Products extends Mage_Core_Model_Abstract{

    public function _construct(){
        $this->_setResourceModel('mediotype_instant_rebate/products');
    }

    public function getProduct()
    {
        return Mage::getModel('catalog/product')->loadByAttribute('sku', $this->getProductSku());
    }

    public function getParentRebate()
    {
        return Mage::getModel('mediotype_instant_rebate/instantRebate')->load($this->getInstantRebateId());
    }

    public function getProductMessage()
    {
        $parent = $this->getParentRebate();
        return $parent->getProductMessage();
    }

    protected function _beforeSave()
    {
        if (is_null($this->getData('shopping_cart_rule_id'))) {
            $this->generateRebateRule();
        } else {
            $ruleCheck = Mage::getModel('salesrule/rule')->load($this->getData('shopping_cart_rule_id'));
            if($ruleCheck->getId()){
                $this->updateRebateRule();
            } else {
                $this->generateRebateRule();
            }
        }

        // AFTER ALL THAT, THIS->setData('shopping_cart_rule_id', $shoppingCartRule->getId()
        return parent::_beforeSave();

    }

    /**
     * create Mage_SalesRule_Model_Rule
     */
    public function generateRebateRule()
    {
        /** @var Mediotype_InstantRebate_Model_InstantRebate $rebateParent */
        $rebateParent = $this->getParentRebate();
        if ($rebateParent->getData('rebate_title') != null) {
            $helper = $this->getHelper();

            $sku = $this->getData('product_sku');
            /** @var Mage_SalesRule_Model_Rule $rule */
            $rule = Mage::getModel('salesrule/rule');
            if ($helper->isGuestAllowed()) {
                $customerGroups = array(0, 1);
            } else {
                $customerGroups = array(1);
            }
            $rule->setName('rebate-' . $rebateParent->getData('rebate_title') . $sku)
                ->setDescription($rebateParent->getData('rebate_title'))
                ->setCouponType(1)
                ->setUsesPerCustomer($this->getData('max_instant_rebate_uses'))
                ->setFromDate($rebateParent->getData('start_date'))
                ->setToDate($rebateParent->getData('end_date'))
                ->setCustomerGroupIds($customerGroups) //an array of customer groupids
                ->setIsActive($rebateParent->isActive())
                ->setConditionsSerialized('')
                ->setActionsSerialized('')
                ->setStopRulesProcessing(0)
                ->setIsAdvanced(1)
                ->setProductIds('')
                ->setSortOrder(0)
                ->setSimpleAction('by_fixed')
                ->setDiscountAmount($this->getData('instant_rebate_amount'))
                ->setDiscountQty($this->getData('max_instant_rebate_uses'))
                ->setDiscountStep(0)
                ->setSimpleFreeShipping('0')
                ->setApplyToShipping('0')
                ->setIsRss(0)
                ->setWebsiteIds(array(Mage::app()->getStore()->getId()));
            $rule->setStoreLabels(array(Mage::app()->getStore()->getId() => $rebateParent->getData('rebate_title')));
            //now set the conditions upon which the rule will be applied
            $itemFound = Mage::getModel('salesrule/rule_condition_product_found')
                ->setType('salesrule/rule_condition_product_found')
                ->setValue(1) // 1 == FOUND
                ->setAggregator('any'); // match ALL conditions
            $conditionsZip = Mage::getModel('salesrule/rule_condition_address')
                ->setType('salesrule/rule_condition_address')
                ->setAttribute('postcode')
                ->setOperator('()')
                ->setValue($rebateParent->getData('zip_codes'));
            $rule->getConditions()->addCondition($itemFound);
            $rule->getConditions()->addCondition($conditionsZip);
            $conditionsSku = Mage::getModel('salesrule/rule_condition_product')
                ->setType('salesrule/rule_condition_product')
                ->setAttribute('sku')
                ->setOperator('==')
                ->setValue($sku);

            $itemFound->addCondition($conditionsSku);

            /**
             * @example Serialized Rule
             * a:7:{s:4:"type";s:40:"salesrule/rule_condition_product_combine";s:9:"attribute";N;s:8:"operator";N;s:5:"value";s:1:"1";s:18:"is_value_processed";N;s:10:"aggregator";s:3:"any";s:10:"conditions";
             * a:1:{i:0;a:5:{s:4:"type";s:32:"salesrule/rule_condition_product";s:9:"attribute";s:3:"sku";s:8:"operator";s:2:"()";s:5:"value";s:44:"4001,4002,3245,4008,4009,4010,4011,4012,3994";s:18:"is_value_processed";b:0;}}}
             */

            /** @var Mage_Salesrule_Model_Rule_Condition_Product $action */
            $action = Mage::getModel('salesrule/rule_condition_product')
                ->setType('salesrule/rule_condition_product')
                ->setAttribute('sku')
                ->setOperator('==')
                ->setValue($sku);

            /** @var Mage_SalesRule_Model_Rule_Action_Collection $actions */
            $actions = $rule->getActions()
                ->setAggregator('any')
                ->addCondition($action);

            $rule->save();
            $this->setData('shopping_cart_rule_id', $rule->getId());
        }
    }

    /**
     * update Mage_SalesRule_Model_Rule
     */
    public function updateRebateRule()
    {
        $helper = $this->getHelper();
        /** @var Mage_SalesRule_Model_Rule $rule */
        $rule = Mage::getModel('salesrule/rule')->load($this->getData('shopping_cart_rule_id'));

        /** @var Mediotype_InstantRebate_Model_InstantRebate $rebateParent */
        $rebateParent = $this->getParentRebate();
        $sku = $this->getData('product_sku');

        if ($helper->isGuestAllowed()) {
            $customerGroups = array(0, 1);
        } else {
            $customerGroups = array(1);
        }
        $rule->setName('rebate-' . $rebateParent->getData('rebate_title') . $sku)
            ->setDescription($rebateParent->getData('rebate_title'))
            ->setCustomerGroupIds($customerGroups) //an array of customer groupids
            ->setUsesPerCustomer($this->getData('max_instant_rebate_uses'))
            ->setConditionsSerialized('')
            ->setActionsSerialized('')
            ->setFromDate($rebateParent->getData('start_date'))
            ->setToDate($rebateParent->getData('end_date'))
            ->setIsActive($rebateParent->getData('active'))
            ->setDiscountAmount($this->getData('instant_rebate_amount'))
            ->setDiscountQty($this->getData('max_instant_rebate_uses'))
            ->setWebsiteIds(array(Mage::app()->getStore()->getId()));
        $rule->setStoreLabels(array(Mage::app()->getStore()->getId() => $rebateParent->getData('rebate_title')));
        //now set the conditions upon which the rule will be applied

        $itemFound = Mage::getModel('salesrule/rule_condition_product_found')
            ->setType('salesrule/rule_condition_product_found')
            ->setValue(1) // 1 == FOUND
            ->setAggregator('any'); // match ALL conditions
        $conditionsZip = Mage::getModel('salesrule/rule_condition_address')
            ->setType('salesrule/rule_condition_address')
            ->setAttribute('postcode')
            ->setOperator('()')
            ->setValue($rebateParent->getData('zip_codes'));
        $rule->getConditions()->addCondition($itemFound);
        $rule->getConditions()->addCondition($conditionsZip);
        $conditionsSku = Mage::getModel('salesrule/rule_condition_product')
            ->setType('salesrule/rule_condition_product')
            ->setAttribute('sku')
            ->setOperator('==')
            ->setValue($sku);

        $itemFound->addCondition($conditionsSku);

        /**
         * @example Serialized Rule
         * a:7:{s:4:"type";s:40:"salesrule/rule_condition_product_combine";s:9:"attribute";N;s:8:
         * "operator";N;s:5:"value";s:1:"1";s:18:"is_value_processed";N;s:10:"aggregator";s:3:"any";s:10:"conditions";
         * a:1:{i:0;a:5:{s:4:"type";s:32:"salesrule/rule_condition_product";s:9:"attribute";s:3:"sku";s:8:"operator";s:2:"()";s:5:"value";s:44:"4001,4002,3245,4008,4009,4010,4011,4012,3994";s:18:"is_value_processed";b:0;}}}
         */

        /** @var Mage_Salesrule_Model_Rule_Condition_Product $action */
        $action = Mage::getModel('salesrule/rule_condition_product')
            ->setType('salesrule/rule_condition_product')
            ->setAttribute('sku')
            ->setOperator('==')
            ->setValue($sku);

        /** @var Mage_SalesRule_Model_Rule_Action_Collection $actions */
        $actions = $rule->getActions()
            ->setAggregator('any')
            ->addCondition($action);

        $rule->save();

    }

    protected function _beforeDelete()
    {
        //delete the associated coupon
        $coupon = Mage::getModel('salesrule/rule')->load($this->getData('shopping_cart_rule_id'));
        $coupon->delete();
        return parent::_beforeDelete();
    }

    /**
     * @return Mediotype_InstantRebate_Helper_Data
     */
    protected function getHelper()
    {
        return Mage::helper('mediotype_instant_rebate');
    }
}