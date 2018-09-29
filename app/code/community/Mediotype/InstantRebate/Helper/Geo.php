<?php
/**
 *
 *
 * @author  Joel Hart
 */
class Mediotype_InstantRebate_Helper_Geo extends Mediotype_InstantRebate_Helper_Abstract{

    /**
     *
     * @author  Joel Hart
     *
     * @returns array | Null   Either returns a zip code array by looking up in MaxMind, or the zip code resource model, or null if not possible
     */
    public function lookupCurrentUserIp( ){
        /** @var Mage_Core_Helper_Http $httpHelper */
        $httpHelper = Mage::helper('core/http');
        $currentVisitorIP = '75.145.120.74';
//        $currentVisitorIP = Mage::app()->getRequest()->getServer('HTTP_X_FORWARDED_FOR');
        if(is_null($currentVisitorIP)){
            $currentVisitorIP = $httpHelper->getRemoteAddr();
        }
        //Mage::log($_SERVER,null,'server.log');
        $maxMindHelper = Mage::helper('geoip');
        /** @var Mediotype_MaxMindGeo_Model_Locations_AbstractModel $locationData */
        $locationData = $maxMindHelper->lookUpIp($currentVisitorIP);
        //Mage::log($currentVisitorIP, null, 'locationdata.log');
        $zipArray = array();
        if(!is_null($locationData->postal->code)){
            //zip is present
            $zipArray['zip_code'] = $locationData->postal->code;
            return $zipArray;
        }

        if($zipArray = $this->getZipCodeArray($locationData->mostSpecificSubdivision->isoCode, $locationData->city->name)){
            return $zipArray;
        }

        return null;
    }

    /**
     * Returns array('zip_code' => '#####') for a given state and city
     * @author  Joel Hart
     * @param $stateShortCode
     * @param $cityName
     * @return array|null
     */
    public function getZipCodeArray($stateShortCode, $cityName){

        //Attempt to find a zip code from the database
        /** @var Mediotype_InstantRebate_Model_Resource_ZipCode_Collection $zipCollection */
        $zipCollection = Mage::getModel('mediotype_instant_rebate/ZipCode')->getCollection()
            ->addFieldToFilter('state_prefix', array('eq' => $stateShortCode))
            ->addFieldToFilter('city', array('eq' => $cityName))
            ->addFieldToSelect('zip_code');

        if(count($zipCollection->getItems()) > 1){
            return $zipCollection->toArray(array('zip_code'));
        } else if( count($zipCollection->getItems()) == 1 ){
            return $zipCollection->toArray(array('zip_code'));
        }

        return null;
    }

    public function testZipCodeArrayLookup($stateShortCode = 'CO', $cityName = 'Boulder'){
        $result = $this->getZipCodeArray($stateShortCode, $cityName);
        var_dump($result); die();
    }

    public function testCurrentUserLookup(){
        $result = $this->lookupCurrentUserIp();
        var_dump($result);die();
    }

}