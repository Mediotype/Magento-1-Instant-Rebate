<?php
/**
 *
 * @author  Joel Hart
 */
class Mediotype_InstantRebate_Model_System_Config_Source_DisableMethods{

    protected function getActivePaymentMethods(){
        $paymentMethods = Mage::getSingleton('payment/config')->getActiveMethods();
        return $paymentMethods;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $activeMethods = $this->getActivePaymentMethods();

        $methodOptions = array(array('value'=>'', 'label'=>Mage::helper('adminhtml')->__('--Select Multiple Methods To Disable--')));

        foreach ($activeMethods as $paymentCode => $paymentModel) {
            $paymentTitle = Mage::getStoreConfig('payment/'.$paymentCode.'/title');
            $methodOptions[$paymentCode] = array(
                'label'     => $paymentTitle,
                'value'     => $paymentCode,
            );
        }

        return $methodOptions;

    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $activeMethods = $this->getActivePaymentMethods();

        $methodOptions = array();

        foreach ($activeMethods as $paymentCode => $paymentModel) {
            $paymentTitle = Mage::getStoreConfig('payment/'.$paymentCode.'/title');
            $methodOptions[$paymentCode] = $paymentTitle;
        }

        return $methodOptions;
    }

}