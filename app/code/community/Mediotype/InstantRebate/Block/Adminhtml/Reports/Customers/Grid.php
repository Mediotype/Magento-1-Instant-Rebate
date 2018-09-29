<?php
class Mediotype_InstantRebate_Block_Adminhtml_Reports_Customers_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function _prepareLayout()
    {
        $this->addExportType('*/*/customerInformationByRebateCsv', Mage::helper('customer')->__('CSV'));
        return parent::_prepareLayout();
    }

    public function _prepareCollection()
    {
        /**
         *
         * LEFT JOIN `sales_flat_order` ON `sales_flat_order`.`entity_id` = `main_table`.`order_id`
         * LEFT JOIN `customer_entity` AS `e` ON `e`.`entity_id` = `sales_flat_order`.`customer_id`
         *
         */
        $collection = Mage::getModel('mediotype_instant_rebate/conversions')->getCollection();
        $collection->getSelect()
            ->joinLeft(array("t1" => "sales_flat_order"), "`t1`.`entity_id` = `main_table`.`order_id`")
            ->joinLeft(array("t2" => "customer_entity"), "`t2`.`entity_id` = `t1`.`customer_id`")
            ->joinLeft(array("t3" => "mediotype_instant_rebate"), "`t3`.`id` = `main_table`.`instant_rebate_id` ")
            ->joinLeft(array("t4" => "sales_flat_order_address"), "`t4`.`entity_id` = `t1`.`billing_address_id` ")
            ->reset('columns')
            ->columns('main_table.*')
            ->columns('t1.increment_id as order_increment_id')
            ->columns('t1.created_at as date_order_placed')
            ->columns("SUM(main_table.instant_rebate_amount_applied) as discount_sum")
            ->columns('t3.instant_rebate_sponsor')
            ->columns('t3.rebate_title')
            ->columns('t4.firstname')
            ->columns('t4.lastname')
            ->columns('t4.telephone')
            ->columns('t4.email')
            ->columns('t4.postcode')
            ->group(array('main_table.instant_rebate_id', 'main_table.order_id'));


        $collection = Mage::helper('mediotype_core')->joinEavTablesIntoCollection(
            $collection,
            'customer_id',
            'customer',
            0
        );

        $this->setCollection($collection);
        parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'date_order_placed',
            array(
                'header' => Mage::helper('customer')->__('Date'),
                'width' => '150px',
                'index' => 'date_order_placed',
                'type' => 'datetime',
            )
        );

        $this->addColumn(
            'order_increment_id',
            array(
                'header' => Mage::helper('customer')->__('Order Id'),
                'width' => '50px',
                'index' => 'order_increment_id',
                'type' => 'text',
            )
        );

        $this->addColumn(
            'instant_rebate_sponsor',
            array(
                'header' => Mage::helper('customer')->__('Rebate Sponsor'),
                'index' => 'instant_rebate_sponsor',
            )
        );
        $this->addColumn(
            'rebate_title',
            array(
                'header' => Mage::helper('customer')->__('Rebate Title'),
                'index' => 'rebate_title',
            )
        );


        $this->addColumn(
            'firstname',
            array(
                'header' => Mage::helper('customer')->__('First Name'),
                'index' => 'firstname'
            )
        );
        $this->addColumn(
            'lastname',
            array(
                'header' => Mage::helper('customer')->__('Last Name'),
                'index' => 'lastname'
            )
        );

        $this->addColumn(
            'email',
            array(
                'header' => Mage::helper('customer')->__('Email'),
                'width' => '150',
                'index' => 'email'
            )
        );

        $this->addColumn(
            'Telephone',
            array(
                'header' => Mage::helper('customer')->__('Telephone'),
                'width' => '100',
                'index' => 'telephone'
            )
        );

        $this->addColumn(
            'billing_postcode',
            array(
                'header' => Mage::helper('customer')->__('ZIP'),
                'width' => '90',
                'index' => 'postcode',
            )
        );

        $this->addColumn(
            'discount_sum',
            array(
                'type' => 'currency',
                'header' => Mage::helper('customer')->__('Total Rebates'),
                'index' => 'discount_sum',
            )
        );

        $optInColumnType = 'checkbox';
        if ($this->_isExport) {
            $optInColumnType = 'text';
        }

        $this->addColumn(
            'opt_in',
            array(
                'type' => $optInColumnType,
                'exportType' => 'text',
                'header' => Mage::helper('customer')->__('Opt In'),
                'index' => 'opt_in',
                'values' => array(1),
                'readonly' => true
            )
        );

        return parent::_prepareColumns();
    }

}