<?php
class Mediotype_InstantRebate_Adminhtml_ImportController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout()->renderLayout();
    }

    public function importCsvAction()
    {
        if (isset($_FILES['csv_file']['name']) and (file_exists($_FILES['csv_file']['tmp_name']))) {

            try {
                $uploader = new Varien_File_Uploader('csv_file');
                $uploader->setAllowedExtensions(array('csv', 'CSV'));
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);
                $path = Mage::getBaseDir('var') . DS . 'import' . DS;
                $uploader->save($path, $_FILES['csv_file']['name']);

                $fullPathAndFile = $path . $_FILES['csv_file']['name'];
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addWarning($e->getMessage());
                $this->_redirect('*/adminhtml_index/index');
            }

            $import = Mage::getModel('mediotype_instant_rebate/import_io');
            $import->setImportFile($fullPathAndFile);
            $strategy = Mage::getModel('mediotype_instant_rebate/convert_adapter_rebates');

            try {
                $import->importDataByRow($strategy);

            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addWarning("No File Was Provided For Processing");
                $this->_redirect('*/adminhtml_index/index');
            }

        } else {
            Mage::getSingleton('adminhtml/session')->addWarning("No File Was Provided For Processing");
            $this->_redirect('*/adminhtml_index/index');

        }

        Mage::getSingleton('adminhtml/session')->addSuccess("Import Process Completed.");
        $this->_redirect('*/adminhtml_index/index');

    }

    public function downloadCsvTemplateAction()
    {
        $fileName = 'instant_rebates.csv';
        $content = array(
            'instant_rebate_sponsor',
            'sponsor_logo_url',
            'rebate_title',
            'max_instant_rebate_uses',
            'instant_rebate_amount',
            'zip_codes',
            'marketing_message',
            'product_message',
            'amount_exceeded_message',
            'start_date',
            'end_date',
            'max_program_amount',
            'product_skus'
        );
        $content = '"' . implode('","', $content) . '"';
        $this->_prepareDownloadResponse($fileName, $content, "text/csv");
        return $this;
    }
}