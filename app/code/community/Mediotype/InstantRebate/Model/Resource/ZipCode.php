<?php
/**
 *
 * @author  Joel Hart   <joel@mediotype.com>
 */
class Mediotype_InstantRebate_Model_Resource_ZipCode extends Mage_Core_Model_Resource_Db_Abstract
{

    protected function _construct()
    {
        $this->_init('mediotype_instant_rebate/zipcode', 'id');
    }

}