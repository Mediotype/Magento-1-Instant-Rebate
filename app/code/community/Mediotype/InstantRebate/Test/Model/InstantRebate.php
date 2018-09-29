<?php
/**
 *
 * @author  Joel Hart
 */
class Mediotype_InstantRebate_Test_Model_InstantRebate extends EcomDev_PHPUnit_Test_Case{

    /**
     * @group InstantRebate
     * @test
     */
    public function getModel(){
        $rebate = Mage::getModel('mediotype_instant_rebate/InstantRebate');

        
        $this->assertTrue(true, 'passes');
    }

}