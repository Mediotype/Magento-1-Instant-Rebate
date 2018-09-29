<?php
/**
 *
 * @author  Joel Hart
 */
class Mediotype_InstantRebate_Helper_Rule extends Mediotype_InstantRebate_Helper_Abstract{

    /**
     * @param Mage_Salesrule_Model_Rule $rule
     * @param int $allowGuest - 0 , 1 (false, true)
     */
    public function setAllowGuest(Mage_Salesrule_Model_Rule $rule, $allowGuest = null){

        if(is_null($allowGuest)){
            $allowGuest = $this->isGuestAllowed(); // set by config if no param passed
        }

        /** @var Mage_Customer_Model_Group $customerGroup */
        $customerGroup = Mage::getModel('customer/group')->load('NOT LOGGED IN', 'customer_group_code');
        if(!$customerGroup->getId()){
            Mediotype_Core_Helper_Debugger::log('FAILED TO LOAD GUEST CUSTOMER GROUP');
            return;
        }

        $currentCustomerGroupIds = $rule->getCustomerGroupIds();
        if($allowGuest){
            if(!in_array($customerGroup->getId(),$currentCustomerGroupIds)){
                $currentCustomerGroupIds[] = $customerGroup->getId();
            }
        } else { //remove guest customer group from allowed customer group array on rule
            if( $key = array_search($customerGroup->getId(), $currentCustomerGroupIds) !== false) {
                unset($currentCustomerGroupIds[$key]);
            }
        }

        $rule->setCustomerGroupIds($currentCustomerGroupIds);
    }

}