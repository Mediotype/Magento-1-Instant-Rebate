<?php
/**
 *
 * @author  Joel Hart
 */
class Mediotype_InstantRebate_Block_Adminhtml_Form_Element_Sponsor extends Varien_Data_Form_Element_Image
{

    /**
     * @return bool|string
     */
    protected function _getUrl()
    {
        $url = false;
        if ($this->getValue()) {
            $url = Mage::getBaseUrl('media'). DS . Mediotype_InstantRebate_Helper_DATA::ASSET_LOCATION_SPRONSOR_LOGO . $this->getValue();
        }
        return $url;
    }

}