<?php
/**
 *
 * @author  Joel Hart
 */
class Mediotype_InstantRebate_Model_Observer{

    /**
     *
     * @param $observer
     */
    public function calculateRebates($observer){
        /** @var Mage_Sales_Model_Quote_Item $quoteItem */
        $quoteItem = $observer->getQuoteItem();
        /** @var Mage_Sales_Model_Quote $quote */
        $quote = $quoteItem->getQuote();
        $hasZip = true;
        if(is_null($quote->getShippingAddress()->getPostcode()) && !is_null($this->getRebateHelper()->getCustomerZip()))
        {
            $quote->getShippingAddress()
                ->setCountryId('US')
                ->setPostcode($this->getRebateHelper()->getCustomerZip())
                ->setRegionId(1)
                ->setRegion('Alabama')
                ->setCollectShippingRates(true);
            $quote->save();
            $hasZip = false;
        }
        $quote->collectTotals()->save();
        if(!$hasZip)
        {
            Mage::app()->getResponse()->setRedirect(Mage::getUrl("checkout/cart"))->sendResponse();
        }
    }

    public function addConversion($observer){
        //grab order and find if any discounts were applied
        /** @var  Mage_Sales_Model_Order $order */
        $order = $observer->getOrder();
        //Get the list of items for your order
        $items = $order->getItemsCollection();
        //loop through each item
        foreach($items as $item){
            /** @var  Mage_Sales_Model_Order_Item $item */
            //if the item has not had a rule applied to it skip it
            if($item->getAppliedRuleIds() != '')
            {
                foreach(explode(",",$item->getAppliedRuleIds()) as $ruleID){
                    //Load the rule object
                    $rule = Mage::getModel('salesrule/rule')->load((int)$ruleID);

                    // Throw out some information like the rule name what product it was applied to
                    if(strpos($rule->getData('name'), 'rebate-')===0){
                        //add data to conversion model
                        /** @var  Mediotype_InstantRebate_Model_Products $rebateProduct */
                        $rebateProduct = Mage::getModel('mediotype_instant_rebate/products')->load($ruleID,'shopping_cart_rule_id');
                        /** @var  Mediotype_InstantRebate_Model_InstantRebate $rebate */
                        $rebate = $rebateProduct->getParentRebate();
                        $conversion = Mage::getModel('mediotype_instant_rebate/conversions');
                        $conversion->setData('order_id',$order->getId());
                        $conversion->setData('order_item_id',$item->getId());
                        $conversion->setData('product_id', $item->getProductId());
                        $conversion->setData('customer_id',$order->getCustomerId());
                        $conversion->setData('instant_rebate_id',$rebate->getId());
                        $conversion->setData('instant_rebate_amount_applied',($item->getQtyOrdered()*$rebateProduct->getData('instant_rebate_amount')));
                        $conversion->setData('opt_in',Mage::helper('mediotype_instant_rebate')->getOptIn());
                        $conversion->save();
                        $rebate->revalidate();
                    }
                }
            }
        }
    }

    /**
     * Disable paypal express payment option & Amazon payment option if rebate is present
     *
     * Event fired from: \Mage_Payment_Model_Method_Abstract::isAvailable
     * Listens To:
        Mage::dispatchEvent('payment_method_is_active', array(
            'result'          => $checkResult,
            'method_instance' => $this,
            'quote'           => $quote,
        ));
     * @param $observer
     * @assigns $observer->result bool true , false
     */
    public function validatePaymentMethods($observer){

        $helper = $this->getRebateHelper();

        $checkoutSession = $helper->getCheckoutSession();

        /** @var Mage_Sales_Model_Quote $quote */
        $quote = $checkoutSession->getQuote();
        $appliedRuleIds = $quote->getAppliedRuleIds();

        $ruleIdArray = explode(',', $appliedRuleIds);

        $checkResult = false;
        foreach($ruleIdArray as $ruleId){
            $rebateProduct = Mage::getModel('mediotype_instant_rebate/products')->load($ruleId, 'shopping_cart_rule_id');
            if($rebateProduct->getId()){
                $checkResult = true;
            }
        }

        if(!$checkResult){
            return;
        }


        $instance = $observer->getMethodInstance();
        $disabledMethods = $helper->getDisabledCheckoutPaymentMethods();
        if(in_array($instance->getCode(), $disabledMethods)){
            $observer->getResult()->isAvailable = false;
        }

    }

    /**
     * If rebates module is disabled, disable the salesrule coupons associated with enabled rebates,
     * vice versa on enabling the module
     * Listens too: admin_system_config_changed_section_instant_rebate_config
     */
    public function systemConfigChanged($observer)
    {
        $helper = $this->getRebateHelper();
        $ruleHelper = $this->getRuleHelper();

        $rebatesCollection = Mage::getModel('mediotype_instant_rebate/InstantRebate')->getCollection()->addActiveFilter(1)->load();
        $setActiveFlag = $helper->isRebatesEnabled();
        $allowGuestFlag = $helper->isGuestAllowed();

        foreach($rebatesCollection->getItems() as $enabledRebate){
            foreach($enabledRebate->getProductCollection() as $productRebate) {
                $salesRule = Mage::getModel('salesrule/rule')->load($productRebate->getShoppingCartRuleId());
                $salesRule->setIsActive($setActiveFlag);
                $ruleHelper->setAllowGuest($salesRule,$allowGuestFlag);
                $salesRule->save();
            }
        }
    }

    /**
     * @return Mediotype_InstantRebate_Helper_Rule
     */
    protected function getRuleHelper(){
        return Mage::helper('mediotype_instant_rebate/Rule');
    }

    /**
     * @return Mediotype_InstantRebate_Helper_Data
     */
    protected function getRebateHelper(){
        return Mage::helper('mediotype_instant_rebate');
    }


}