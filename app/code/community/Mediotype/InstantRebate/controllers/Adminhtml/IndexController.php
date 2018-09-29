<?php

class Mediotype_InstantRebate_Adminhtml_IndexController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        $this->loadLayout()->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {

        $this->loadLayout();

        $this->_setActiveMenu('promo/instant_rebate');

        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Rebate Manager'), Mage::helper('adminhtml')->__('Rebate Manager'));
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Rebate Editor'), Mage::helper('adminhtml')->__('Edit Video'));

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $this->_addContent($this->getLayout()->createBlock('mediotype_instant_rebate/adminhtml_rebates_edit'))
            ->_addLeft($this->getLayout()->createBlock('mediotype_instant_rebate/adminhtml_rebates_tabs'));

        $this->renderLayout();
    }

    public function saveAction()
    {
        try {

            $data = $this->getRequest()->getParams();
            unset($data['key']);
            unset($data['form_key']);
            $data['products'] = explode(',', $data['products']);
            $model = Mage::getModel('mediotype_instant_rebate/instantRebate');
            if ($id = $this->getRequest()->getParam('id')) {
                $model->load($id);
                if (!$model->getId()) {
                    throw new Exception('Failed to load rebate model. ID: ' . $id);
                }
            }

            /** @var Mediotype_InstantRebate_Helper_Data $helper */
            $helper = $this->getHelper();
            if ($id && isset($data['sponsor_logo_url']['delete']) && $data['sponsor_logo_url']['delete'] == 1) {
                $data['sponsor_logo_url'] = '';
            } else {
                //do not try to upload if the form changed without providing a new file
                if (isset($_FILES) && $_FILES['sponsor_logo_url']['size'] > 0) {
                    //Varien_File_Uploader requires an array that is a copy of the $_FILES array values
                    $fileInfo = array(
                        'name' => $_FILES['sponsor_logo_url']['name'],
                        'type' => $_FILES['sponsor_logo_url']['type'],
                        'tmp_name' => $_FILES['sponsor_logo_url']['tmp_name'],
                        'error' => $_FILES['sponsor_logo_url']['error'],
                        'size' => $_FILES['sponsor_logo_url']['size'],
                    );

                    $pathToSponsorsImageAssets = $helper->getMediaSponsorPath();

                    $uploader = new Varien_File_Uploader($fileInfo);
                    $uploader->setAllowedExtensions($this->_getAllowedExtensions());
                    $uploader->setAllowCreateFolders(true);
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);
                    $uploader->save($pathToSponsorsImageAssets);

                    $fileName = $uploader->getUploadedFileName();

                    if ($fileName) {
                        $data['sponsor_logo_url'] = $fileName;
                    }
                } else {
                    unset( $data['sponsor_logo_url']);
                }
            }

            $model->addData($data);
            $model->save();
            $this->_getSession()->addSuccess('Rebate Saved');
        } catch (Exception $e) {
            $this->_getSession()->addError('Failed to save rebate data - ' . $e->getMessage());
            Mage::log(array($e->getMessage(), $e->getTrace()));
        }

        $this->_redirect('mediotype_instantrebate/adminhtml_index/index');
    }

    public function deleteAction()
    {
        var_dump($this->getRequest()->getParams());
    }

    public function productGridAction()
    {
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('mediotype_instant_rebate/adminhtml_form_rebates_edit_products')->toHtml()
        );
    }

    public function massEnableRebatesAction()
    {
        $rebate_ids = $this->getRequest()->getParam('rebate_ids', array());
        $failed = array();
        $successCount = 0;
        foreach ($rebate_ids as $id) {
            $rebateModel = Mage::getModel('mediotype_instant_rebate/instantRebate')->load($id);
            $rebateModel->setData('active', true);
            $rebateModel->save();
            $rebateModel->revalidate();
            if ($rebateModel->getData('active') == false) {
                $failed[] = $rebateModel->getId();
            } else {
                $successCount++;
            }
        }
        $this->_getSession()->addSuccess('Activated ' . $successCount . ' Rebates successfully.');
        if (count($failed) > 0) {
            $this->_getSession()->addWarning('Failed to activate some rebates. This can happen becuase the rebate has expired, or the rebate limit has been reached. Failed Rebate ID\'s: ' . implode(", ", $failed));
        }
        $this->_redirectReferer();
    }

    /**
     * @return array
     */
    protected function _getAllowedExtensions()
    {
        return array('jpg', 'jpeg', 'png');
    }

    /**
     * Gets InstantRebate helper
     *
     * @return Mediotype_InstantRebate_Helper_Data
     */
    protected function getHelper()
    {
        return Mage::helper('mediotype_instant_rebate');
    }

    public function massDisableRebatesAction()
    {
        $rebate_ids = $this->getRequest()->getParam('rebate_ids', array());
        foreach ($rebate_ids as $id) {
            $rebateModel = Mage::getModel('mediotype_instant_rebate/instantRebate')->load($id);
            $rebateModel->setData('active', false);
            $rebateModel->save();
        }
        $this->_getSession()->addSuccess('Disabled ' . count($rebate_ids) . ' rebate(s) successfully.');
        $this->_redirectReferer();
    }

    public function toggleRebateAction()
    {
        $id = $this->getRequest()->getParam('rebate_id');
        $rebateModel = Mage::getModel('mediotype_instant_rebate/instantRebate')->load($id);
        if (!$rebateModel->getId()) {
            $this->_getSession()->addWarning("Failed to load rebate $id");
            $this->_redirectReferer();
            return;
        }

        // Disable Rebate
        if ($rebateModel->getData('active') == 1) {
            $rebateModel->setData('active', false);
            $rebateModel->save();
            $this->_getSession()->addSuccess('Disabled Rebate successfully.');
            $this->_redirectReferer();
            return;
        }


        // Enable Rebate
        $rebateModel->setData('active', true);
        $rebateModel->save();

        $rebateModel->revalidate();

        if ($rebateModel->getData('active') == false) {
            $this->_getSession()->addWarning("Failed to activate the rebate. This can happen because the rebate has expired, or the rebate limit has been reached. Failed Rebate ID: $id");
        } else {
            $this->_getSession()->addSuccess('Rebate successfully enabled.');
        }

        $this->_redirectReferer();

    }

    public function exportCsvAction()
    {
        $fileName = 'instant_rebates.csv';
        $content = $this->getLayout()->createBlock('mediotype_instant_rebate/adminhtml_rebates_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content, "text/csv");
        return $this;
    }

    public function updateInfoBlockAction()
    {
        $data = $this->getRequest()->getParams();
        /** @var Mediotype_InstantRebate_Block_Adminhtml_Rebates_Edit_Tab_Info_Products $products */
        $products = $this->getLayout()->createBlock('mediotype_instant_rebate/adminhtml_rebates_edit_tab_info_products');
        $products->setData('products',explode(',',$data['products']));
        echo $products->toHtml();
    }

}