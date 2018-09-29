<?php
/**
 *
 * @author  Joel Hart   <joel@mediotype.com>
 */
class Mediotype_InstantRebate_Model_Resource_ZipCode_Collection extends Mediotype_Core_Model_Resource_Db_Collection_Abstract
{

    protected function _construct()
    {
        $this->_init('mediotype_instant_rebate/zipCode');
    }

}
