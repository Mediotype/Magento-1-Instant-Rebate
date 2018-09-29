<?php
class Mediotype_InstantRebate_Adminhtml_ReportsController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout()->renderLayout();
    }

    public function productsSoldByRebateAction()
    {
        $this->loadLayout()->renderLayout();
    }
    public function productsSoldByRebateCsvAction()
    {
        $fileName = 'productsSoldByRebate.csv';

        /** @var Mediotype_InstantRebate_Block_Adminhtml_Reports_Products_Grid $block */
        $block = $this->getLayout()->createBlock('mediotype_instant_rebate/adminhtml_reports_products_grid');
        $content =  $block->getCsv();
        $this->_prepareDownloadResponse($fileName, $content, "text/csv");
        return $this;
    }



    public function customerInformationByRebateAction()
    {
        $this->loadLayout()->renderLayout();
    }
    public function customerInformationByRebateCsvAction()
    {
        $fileName = 'customerInformationByRebate.csv';

        /** @var Mediotype_InstantRebate_Block_Adminhtml_Reports_Customers_Grid $block */
        $block = $this->getLayout()->createBlock('mediotype_instant_rebate/adminhtml_reports_customers_grid');
        $content =  $block->getCsv();
        $this->_prepareDownloadResponse($fileName, $content, "text/csv");
        return $this;
    }
}