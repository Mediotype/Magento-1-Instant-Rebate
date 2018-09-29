<?php

class Mediotype_InstantRebate_Model_Resource_Products_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract{

    public function _construct(){
        $this->_init('mediotype_instant_rebate/products');
    }

}