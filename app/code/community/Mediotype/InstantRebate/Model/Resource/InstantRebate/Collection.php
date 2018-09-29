<?php

class Mediotype_InstantRebate_Model_Resource_InstantRebate_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract{

    public function _construct(){
        $this->_init('mediotype_instant_rebate/instantRebate');
    }

    /**
     * Restrict results to start_date BEFORE NOW and end_date AFTER NOW
     * @author  Joel Hart
     *
     */
    public function addIsActiveByDateNowFilter(){

        $now = date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time()));

        $select = $this->getSelect();
        $select->where('start_date is null or from_date <= ?', $now)
                    ->where('end_date is null or to_date >= ?', $now);

        return $this;
    }

    /**
     * Limit collection load by active field value
     * @param bool $isEnabled
     * @return $this
     */
    public function addActiveFilter($isEnabled = 1){
        $this->getSelect()->where('active = ?', $isEnabled);
        return $this;
    }

}