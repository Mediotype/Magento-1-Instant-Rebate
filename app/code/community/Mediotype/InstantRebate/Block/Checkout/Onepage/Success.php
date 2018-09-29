<?php
class Mediotype_InstantRebate_Block_Checkout_Onepage_Success extends Mage_Checkout_Block_Onepage_Success
{
    /** return an array of rebates */
    public function getRebates()
    {
        $rebatesArray = array();
        $orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        if ($orderId)
        {
            //get the rebates that were applied to the order
            /** @var  Mage_Sales_Model_Order $order */
            $order = Mage::getModel('sales/order')->load($orderId);
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
                            $rebatesArray[] = $rebateProduct->getParentRebate();
                        }
                    }
                }
            }
        }
        return $rebatesArray;
    }
}