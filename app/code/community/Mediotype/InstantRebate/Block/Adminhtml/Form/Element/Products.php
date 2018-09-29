<?php
/**
 * Created by PhpStorm.
 * User: szurek
 * Date: 9/4/14
 * Time: 12:05 PM
 */
class Mediotype_InstantRebate_Block_Adminhtml_Form_Element_Products extends Varien_Data_Form_Element_Hidden
{
    public function getValue($index)
    {
        return implode(",", parent::getValue($index));
    }
}