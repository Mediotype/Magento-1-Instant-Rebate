<?php
/**
 * Helper for Instant Rebates Module
 *
 * @author Mediotype
 */
class Mediotype_InstantRebate_Helper_Data extends Mediotype_InstantRebate_Helper_Abstract {

    /**
     * @return string
     */
    public function showMultiShippingTemplate(){

        $checkoutSession = $this->getCheckoutSession();

        /** @var Mage_Sales_Model_Quote $quote */
        $quote = $checkoutSession->getQuote();
        $appliedRuleIds = $quote->getAppliedRuleIds();

        $ruleIdArray = explode(',', $appliedRuleIds);

        $checkResult = false;
        foreach($ruleIdArray as $ruleId){
            $rebate = Mage::getModel('mediotype_instant_rebate/products')->load($ruleId, 'shopping_cart_rule_id');
            if($rebate->getId()){
                if( $this->isMultiShippingAllowed() ){
                    //allow multi shipping link (do nothing)
                } else {
                    $checkResult = true;
                }
            }
        }

        if($checkResult){
            return '';
        } else{
            return 'checkout/multishipping/link.phtml';
        }

    }

    /**
     * Get active rebates collection by zip code
     *
     * @param $zipCode Number like: 80302
     * @return Mediotype_InstantRebate_Model_Resource_InstantRebate_Collection
     */
    public function getRebates($zipCode){

        /** @var Mediotype_InstantRebate_Model_Resource_InstantRebate_Collection $collection */
        $collection = Mage::getModel('mediotype_instant_rebate/instantRebate')->getCollection();
        $collection->addFieldToFilter('zip_codes', array("like" => "%$zipCode%"));
        $collection->addFieldToFilter('active', array("eq"=>1));
        $collection->load();
        return $collection;

    }

    /**
     * Determines if the current zip code has a rebate available
     *
     * @param $zipCode
     * @return bool
     */
    public function hasRebates($zipCode){
        return (count($this->getRebates($zipCode)->getItems()) > 0) ? true : false;
    }

    /**
     * Return collection of rebates associated with an input product
     *
     * @param  $product
     * @return Mediotype_InstantRebate_Model_Resource_InstantRebate_Collection
     */
    public function getProductRebates($productSku){
        //get the data of all rebates for this product
        if(is_null($zipCode = $this->getCustomerZip())){
            return null; //Don't waste resources on a collection if you don't have to
        }
        /** @var Mediotype_InstantRebate_Model_Resource_InstantRebate_Collection $collection */
        $collection = Mage::getModel('mediotype_instant_rebate/instantRebate')->getCollection();
        $collection->addFieldToFilter('zip_codes', array("like" => "%$zipCode%"));
        $collection->addFieldToFilter('active', array("eq"=>1));
        $collection->getSelect()
            ->join(array("t1" => "mediotype_instant_rebate_products"), 't1.instant_rebate_id = main_table.id AND t1.product_sku = "' . $productSku . '"');
        $collection->load();
        return $collection;
    }

    /**
     * Check if product id has
     *
     * @author  Joel Hart
     *
     * @param $entityID
     * @return  Bool
     */
    public function hasProductEntityIdRebateById($entityId, $rebateId){
        $collection = Mage::getModel('mediotype_instant_rebate/Products')->getCollection()
            ->addFieldToFilter('product_id', array('eq' => $entityId))
            ->addFieldToFilter('instant_rebate_id', array('eq' => $rebateId))
            ->load();

        if(count($collection->getItems()) > 0){
            return true;
        }

        return false;
    }

    /**
     * Has customer rabate zip been set on session
     *
     * @return mixed
     */
    public function getCustomerZip()
    {
        //if it's null, customer hasn't entered info yet
        if( $zip = $this->getCustomerSession()->getData('rebate_zip') ){
            return $zip;
        }
        return null;
    }

    /**
     * @return bool
     */
    public function isGeoZipViable()
    {

        if($this->isGeoActive() == false){
            //Noneed to lookup IP if form is always present
            return false;
        }

        /** @var $zips Null | Array like array('zip_code' => 'xxxxx', 'zip_code' => 'xxxxx') */
        $zips = $this->getGeoZips();

        if(is_null($zips) || $zips == "NULL"){
            return false;
        }

        foreach($zips as $zip){
            if($this->hasRebates($zip)){
                //once a working zip is found just return true
                return true;
            }
        }

        return false;
    }

    /**
     * Ping Geo helper module to see if zip(s) code can be found
     *
     * @return Array|null like array('zip_code' => 'xxxxx', 'zip_code' => 'xxxxx')
     */
    protected function getGeoZips()
    {
        /** @var Mediotype_InstantRebate_Helper_Geo $geoHelper */
        $geoHelper = Mage::helper('mediotype_instant_rebate/Geo');

        /** @var $lookupResult Null | Array like array('zip_code' => 'xxxxx', 'zip_code' => 'xxxxx') */
        $lookupResult = $geoHelper->lookupCurrentUserIp();
        if(is_null($lookupResult)){
            return null;
        }

        return $lookupResult;
    }

    /**
     * See if customer session has rebates available set
     *
     * @return bool|mixed
     */
    public function getCustomerRebateAvailable()
    {
        //if it's null, customer hasn't entered info yet so rebates may be available
        $available = $this->getCustomerSession()->getData('rebate_available');
        return (is_null($available))? true : $available;
    }

    /**
     * See if customer optin happened on checkout session
     *
     * @return bool|mixed
     */
    public function getOptIn()
    {
        $optin = $this->getCheckoutSession()->getData('optin');
        return (is_null($optin))? true: $optin;
    }

    /**
     * Check if geo lookup ran already
     *
     * @return bool|mixed
     */
    public function getGeoRun()
    {
        $geoRun = $this->getCustomerSession()->getData('geo_run');
        return (is_null($geoRun))? false: $geoRun;
    }

}
