<?php
/**
 *
 *
 * Class Mediotype_InstantRebate_Model_Resource_Products
 */
class Mediotype_InstantRebate_Model_Resource_Products extends Mage_Core_Model_Resource_Db_Abstract{

    public function _construct(){
        $this->_init('mediotype_instant_rebate/products', 'id');
    }

}