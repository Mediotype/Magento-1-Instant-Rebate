<?php
class Mediotype_InstantRebate_Block_Adminhtml_Rebates_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function _prepareLayout()
    {
        $this->addExportType('*/*/exportCsv', 'CSV');

        //TODO redo import process
//        $this->setChild(
//            'import_button',
//            $this->getLayout()->createBlock('adminhtml/widget_button')
//                ->setData(
//                    array(
//                        'label' => Mage::helper('adminhtml')->__('Import CSV'),
//                        'onclick' => 'setLocation(\'' . Mage::helper('adminhtml')->getUrl(
//                                '*/adminhtml_import/index'
//                            ) . '\')',
//                        'class' => 'add success'
//                    )
//                )
//        );
        return parent::_prepareLayout();
    }

    protected function _afterLoadCollection()
    {

    }

    public function getExportButtonHtml()
    {
        return parent::getExportButtonHtml() . " " . $this->getChildHtml('import_button');
    }

    public function _prepareCollection()
    {
        /** @var Mediotype_InstantRebate_Model_Resource_InstantRebate_Collection $collection */
        $collection = Mage::getModel('mediotype_instant_rebate/instantRebate')->getCollection();

        if ($this->_isExport) {
            $collection->getSelect()
                ->joinLeft(
                    array('t1' => 'mediotype_instant_rebate_products'),
                    "t1.instant_rebate_id = main_table.id"
                )
                ->reset('columns')
                ->columns('main_table.*')
                ->columns("group_concat(t1.product_sku) as product_skus")
                ->group('main_table.id');
        }

        $this->setCollection($collection);
        parent::_prepareCollection();
    }

    public function _prepareColumns()
    {
        if ($this->_isExport) {
            $this->_prepareExportColumns();
            return $this;
        }

        $this->addColumn(
            'id',
            array(
                'header' => $this->__('Rebate Id'),
                'index' => 'id',
                'width' => '25px',
                'align' => 'center'
            )
        );

        $this->addColumn(
            'instant_rebate_sponsor',
            array(
                'header' => $this->__('Rebate Sponsor'),
                'index' => 'instant_rebate_sponsor',
            )
        );


        $this->addColumn(
            'rebate_title',
            array(
                'header' => $this->__('Rebate Title'),
                'index' => 'rebate_title',
            )
        );

        $this->addColumn(
            'zip_codes',
            array(
                'header' => $this->__('Zip Codes'),
                'index' => 'zip_codes',
            )
        );

        $this->addColumn(
            'active',
            array(
                'header' => $this->__('Status'),
                'index' => 'active',
                'type' => 'option',
                'align' => 'center',
                'filter' => 'mediotype_instant_rebate/adminhtml_grid_column_filter_active',
                'renderer' => 'Mediotype_InstantRebate_Block_Adminhtml_Grid_Column_Active',
            )
        );

    }

    public function _prepareExportColumns()
    {

        $indexes = array(
            'id',
            'instant_rebate_sponsor',
            'sponsor_logo_url',
            'rebate_title',
            'zip_codes',
            'marketing_message',
            'product_message',
            'amount_exceeded_message',
            'start_date',
            'end_date',
            'max_program_amount'
        );

        foreach ($indexes as $index) {
            $this->addColumn(
                $index,
                array(
                    'header' => $index,
                    'index' => $index,
                )
            );
        }

        $this->addColumn(
            'product_skus',
            array(
                'header' => 'product_skus',
                'index' => 'product_skus',
            )
        );
    }

    public function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('rebate_ids');
        $this->getMassactionBlock()->setUseSelectAll(false);

        $this->getMassactionBlock()->addItem(
            'disable',
            array(
                'label' => $this->__('Disable Rebates'),
                'url' => $this->getUrl('*/*/massDisableRebates'),

            )
        );
        $this->getMassactionBlock()->addItem(
            'enable',
            array(
                'label' => $this->__('Enable Rebates'),
                'url' => $this->getUrl('*/*/massEnableRebates'),
            )
        );


        return $this;
    }

    public function getRowUrl($item)
    {
        return $this->getUrl('*/*/edit', array("id" => $item->getId()));
    }

    public function getEmptyText()
    {
        $newUrl = $this->getUrl('*/*/new');
        //$templateUrl = $this->getUrl('*/adminhtml_import/downloadCsvTemplate');
        //$importUrl = $this->getUrl('*/adminhtml_import/index');
        return "
        <p>
            <center>
                <h4>You currently have no rebates.</h4><br/>
                Click <a href='$newUrl'>Here</a> to create a rebate now.
            </center>
        </p>
        ";
        /* old message
         * <p>
            <center>
                <h4>You currently have no rebates.</h4><br/>
                Click <a href='$newUrl'>Here</a> to create a rebate now. <br/>
                <b>- or - </b><br/>
                Download the <a href='$templateUrl'>CSV Template</a> and <a href='$importUrl'>Import</a> them.
            </center>
        </p>
         */
    }
}