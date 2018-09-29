<?php
/**
 *
 * @author  Joel Hart
 */
class Mediotype_InstantRebate_Helper_Abstract extends Mage_Core_Helper_Abstract{

    const CONFIG_PATH_REBATES_ENABLED               = "instant_rebate_config/general/rebates_enabled";
    const CONFIG_PATH_GEO_ACTIVE                    = "instant_rebate_config/general/geo_active";
    const CONFIG_PATH_ALLOW_GUEST                   = "instant_rebate_config/general/allow_guest";
    const CONFIG_PATH_DISABLE_MULTISHIPPING          = "instant_rebate_config/general/disable_multishipping_when_rebate_present";
    const CONFIG_PATH_DISABLED_PAYMENT_METHODS      = "instant_rebate_config/general/disabled_payments_when_rebate_present";

    const CONFIG_PATH_ZIP_LABEL                     = "instant_rebate_config/textedit/zip_label";
    const CONFIG_PATH_REBATE_UNAVAILABLE            = "instant_rebate_config/textedit/zip_unavailable";
    const CONFIG_PATH_LAST_CHANCE                   = "instant_rebate_config/textedit/cart_last_chance";
    const CONFIG_PATH_ZIP_NOT_MATCHING              = "instant_rebate_config/textedit/zip_not_match";

    const ASSET_LOCATION_SPRONSOR_LOGO  = "sponsors";

    /**
     * @param Mediotype_InstantRebate_Model_InstantRebate $rebate
     * @return bool|string
     * @author  Joel Hart
     */
    public function getSponsorLogoUrl(Mediotype_InstantRebate_Model_InstantRebate $rebate){
        $url = false;
        if ($rebate->getData('sponsor_logo_url')) {
            $url = (string)Mage::getBaseUrl('media'). '/' . self::ASSET_LOCATION_SPRONSOR_LOGO . $rebate->getData('sponsor_logo_url');
        }
        return $url;
    }

    /**
     * Gets path to media folder for sponsors
     *
     * @return string
     */
    public function getMediaSponsorPath(){
        return Mage::getBaseDir('media') . DS . self::ASSET_LOCATION_SPRONSOR_LOGO . DS;
    }

    /**
     * Returns controller url for testing zip codes
     *
     * @return string
     */
    public function getRebatesUrl(){
        return Mage::getBaseUrl() . "instantrebate/instantRebate/getRebates";
    }

    /**
     * Returns controller url for setting optin
     *
     * @return string
     */
    public function getOptInUrl(){
        return Mage::getBaseUrl() . "instantrebate/instantRebate/setOptInAjax";
    }

    /**
     * Like: paypaluk_express,paypal_billing_agreement,paypal_mecl
     *
     * @return mixed
     */
    public function getDisabledCheckoutPaymentMethods(){
        return explode(',', Mage::getStoreConfig(self::CONFIG_PATH_DISABLED_PAYMENT_METHODS));
    }

    /**
     * @return bool check system config for if rebates are currently enabled
     */
    public function isRebatesEnabled(){
        return (bool)Mage::getStoreConfig(self::CONFIG_PATH_REBATES_ENABLED);
    }

    /**
     * @return bool check system config for if multi shipping is allowed
     */
    public function isMultiShippingAllowed(){
        return (bool)Mage::getStoreConfig(self::CONFIG_PATH_DISABLE_MULTISHIPPING);
    }

    /**
     * @return bool check system config for if geo location is enabled
     */
    public function isGeoActive(){
        return (bool)Mage::getStoreConfig(self::CONFIG_PATH_GEO_ACTIVE);
    }

    /**
     * @return bool check system config for if guest use of rebates is allowed
     */
    public function isGuestAllowed(){
        return (bool)Mage::getStoreConfig(self::CONFIG_PATH_ALLOW_GUEST);
    }

    /**
     * Returns the label for the input zip header
     * @return String | Null
     */
    public function getZipLabelText(){
        return (string)Mage::getStoreConfig(self::CONFIG_PATH_ZIP_LABEL);
    }

    /**
     * @return String | Null
     */
    public function getRebateUnavailableText(){
        return (string)Mage::getStoreConfig(self::CONFIG_PATH_REBATE_UNAVAILABLE);
    }

    /**
     * @return String | Null
     */
    public function getLastChanceText(){
        return (string)Mage::getStoreConfig(self::CONFIG_PATH_LAST_CHANCE);
    }

    /**
     * @return String | Null
     */
    public function getZipNotMatchingText(){
        return (string)Mage::getStoreConfig(self::CONFIG_PATH_ZIP_NOT_MATCHING);
    }

    /**
     * Get customer session model instance
     *
     * @return Mage_Customer_Model_Session
     */
    public function getCustomerSession()
    {
        return Mage::getSingleton('customer/session');
    }

    /**
     * Get customer session model instance
     *
     * @return Mage_Customer_Model_Session
     */
    public function getCheckoutSession()
    {
        return Mage::getSingleton('checkout/session');
    }

}