<?php
/**
 * Created by PhpStorm.
 * User: szurek
 * Date: 9/4/14
 * Time: 5:33 PM
 */

class Mediotype_InstantRebate_Block_Adminhtml_Grid_Column_Filter_Active extends Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Select
{

    protected function _getOptions()
    {
        return array(
            array(
                'value' =>  '',
                'label' =>  ''
            ),
            array(
                'value' =>  1,
                'label' =>  Mage::helper('mediotype_instant_rebate')->__('Active')
            ),
            array(
                'value' =>  0,
                'label' =>  Mage::helper('mediotype_instant_rebate')->__('Disabled')
            )
        );
    }
}