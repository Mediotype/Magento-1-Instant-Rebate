<?php
/**
 * Magento / Mediotype Module
 *
 *
 * @desc        Simple CSV parser into key => val array from file
 * @category    Mediotype
 * @package     Htc
 * @class       Mediotype_Htc_Model_Import_Io
 * @copyright   Copyright (c) 2013 Mediotype (http://www.mediotype.com)
 *              Copyright, 2013, Mediotype, LLC - US license
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      Mediotype (SZ,JH) <diveinto@mediotype.com>
 */
class Mediotype_InstantRebate_Model_Import_Io {

    protected $_lineLength= 0;
    protected $_delimiter = ',';
    protected $_enclosure = '"';

    protected $_csv;

    protected $_ioHandler;

    public function __contruct(){
        $this->_ioHandler = new Varien_File_Csv();
    }

    public function importDataByRow( $importerStrategy )
    {
        if (!file_exists($this->_csv)) {
            throw new Exception('File "'.$this->_csv.'" do not exists');
        }

        $fh = fopen($this->_csv, 'r');
        $keyData = fgetcsv($fh, $this->_lineLength, $this->_delimiter, $this->_enclosure);
        while ($rowData = fgetcsv($fh, $this->_lineLength, $this->_delimiter, $this->_enclosure)) {

            $data = array();
            $i = 0;
            foreach($keyData as $index){
                $data[$index] = $rowData[$i];
                $i++;
            }

            try{
                $importerStrategy->saveRow($data);
            } catch (Exception $e){
                $failedRowArray[] = array( "ERROR" => $e->getMessage(), "DATA" => $data);
            }
        }

        if(!empty($failedRowArray)){
            Mage::log($failedRowArray, NULL, 'Mediotype.log');
        }

        return true;
    }

    public function setImportFile($file)
    {
        $this->_csv = $file;
    }

}