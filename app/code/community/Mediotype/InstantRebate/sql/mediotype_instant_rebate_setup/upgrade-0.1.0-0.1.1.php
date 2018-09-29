<?php
/**
 *
 * @author  Joel Hart   <joel@mediotype.com>
 */

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$zipCodeTable = $installer->getConnection()->newTable($this->getTable('mediotype_instant_rebate/zipcode'));
$zipCodeTable->addColumn(
    'id',
    Varien_Db_Ddl_Table::TYPE_INTEGER,
    null,
    array(
        'identity' => '1',
        'unsigned' => '1',
        'nullable' => '',
        'primary' => '1',
    ),
    'Id'
)->addColumn(
        'zip_code',
        Varien_Db_Ddl_Table::TYPE_TEXT,
        '5',
        array(
            'nullable' => '',
        ),
        'Zip Code'
    )->addColumn(
        'city',
        Varien_Db_Ddl_Table::TYPE_TEXT,
        '50',
        array(),
        'City'
    )->addColumn(
        'county',
        Varien_Db_Ddl_Table::TYPE_TEXT,
        '50',
        array(),
        'County'
    )->addColumn(
        'state_name',
        Varien_Db_Ddl_Table::TYPE_TEXT,
        '50',
        array(),
        'State Name'
    )->addColumn(
        'state_prefix',
        Varien_Db_Ddl_Table::TYPE_TEXT,
        '2',
        array(),
        'State Prefix'
    )->addColumn(
        'area_code',
        Varien_Db_Ddl_Table::TYPE_TEXT,
        '3',
        array(),
        'Area Code'
    )->addColumn(
        'time_zone',
        Varien_Db_Ddl_Table::TYPE_TEXT,
        '50',
        array(),
        'Time Zone'
    )->addColumn(
        'lat',
        Varien_Db_Ddl_Table::TYPE_FLOAT,
        null,
        array(
            'nullable' => false,
        ),
        'Lat'
    )->addColumn(
        'lon',
        Varien_Db_Ddl_Table::TYPE_FLOAT,
        null,
        array(
            'nullable' => false,
        ),
        'Lon'
    );

$installer->getConnection()->createTable($zipCodeTable);

$installer->endSetup();