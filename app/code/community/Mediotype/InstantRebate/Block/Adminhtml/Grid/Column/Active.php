<?php
/**
 * Created by PhpStorm.
 * User: szurek
 * Date: 9/4/14
 * Time: 4:58 PM
 */

class Mediotype_InstantRebate_Block_Adminhtml_Grid_Column_Active extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    public function render(Varien_Object $row)
    {
        $value = $row->getData($this->getColumn()->getIndex());
        if ($value == '1') {
            return 'Active';
        } else {
            return 'Disabled';
        }
    }

}