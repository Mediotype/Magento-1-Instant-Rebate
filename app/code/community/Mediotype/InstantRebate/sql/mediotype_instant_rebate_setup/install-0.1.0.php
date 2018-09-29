<?php
/**
 * Data tables for instant rebate feature
 *
 * @author  Joel Hart   <joel@mediotype.com>
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$instantRebateTable = $installer->getConnection()->newTable($this->getTable('mediotype_instant_rebate/instantRebate'));

$instantRebateTable->addColumn('id',
    Varien_Db_Ddl_Table::TYPE_INTEGER,
    11,
    array(
        'primary' => true,
        'identity' => true,
        'nullable' => false,
        'unsigned' => true,
    )
)->addcolumn('rebate_title',
        Varien_Db_Ddl_Table::TYPE_VARCHAR,
        255
    )->addColumn('zip_codes',
        Varien_Db_Ddl_Table::TYPE_TEXT
    )->addColumn('instant_rebate_sponsor',
        Varien_Db_Ddl_Table::TYPE_VARCHAR,
        255
    )->addColumn('marketing_message',
        Varien_Db_Ddl_Table::TYPE_TEXT
    )->addColumn('product_message',
        Varien_Db_Ddl_Table::TYPE_TEXT
    )->addColumn('amount_exceeded_message',
        Varien_Db_Ddl_Table::TYPE_TEXT
    )->addColumn('sponsor_logo_url',
        Varien_Db_Ddl_Table::TYPE_VARCHAR,
        1024
    )->addColumn('start_date',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(
            'nullable' => false,
            'default' => 'NOW()'
        )
    )->addColumn('end_date',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null
    )->addColumn('max_program_amount',
        Varien_Db_Ddl_Table::TYPE_DECIMAL,
        '12,4'
    )->addColumn('active',
        Varien_Db_Ddl_Table::TYPE_TINYINT,
        4,
        array(
            'nullable' => false,
            'default' => 0
        )
    )->addIndex($this->getIdxName($this->getTable('mediotype_instant_rebate/instantRebate'), array('id')),
        array('id')
    );

$installer->getConnection()->createTable($instantRebateTable);


$instantRebateProductTable = $installer->getConnection()->newTable($this->getTable('mediotype_instant_rebate/products'));

$instantRebateProductTable->addColumn('id',
    Varien_Db_Ddl_Table::TYPE_INTEGER,
    11,
    array(
        'primary' => true,
        'identity' => true,
        'nullable' => false,
        'unsigned' => true,
    )
    )->addcolumn('product_sku',
        Varien_Db_Ddl_Table::TYPE_VARCHAR,
        64,
        array(
            'nullable' => false,
        )
    )->addColumn('max_instant_rebate_uses',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        11,
        array(
            'nullable' => false
        )
    )->addColumn('instant_rebate_amount',
        Varien_Db_Ddl_Table::TYPE_DECIMAL,
        '12,4',
        array(
            'nullable' => false,
            'default' => '0.0000',
        )
    )->addColumn('sort_order',
        Varien_Db_Ddl_Table::TYPE_DECIMAL,
        '9,3'
    )->addColumn('instant_rebate_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        11,
        array(
            'nullable' => false
    )
    )->addColumn('product_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        11,
        array(
            'nullable'  => false,
        )
    )->addColumn('shopping_cart_rule_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        11
    )->addForeignKey(
        $installer->getFkName(
            'mediotype_instant_rebate/products',
            'product_id',
            'catalog/product',
            'entity_id'
        ),
        'product_id',
        $installer->getTable('catalog/product'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )->addForeignKey(
        $installer->getFkName(
            'mediotype_instant_rebate/products',
            'product_sku',
            'catalog/product',
            'sku'
        ),
        'product_sku',
        $installer->getTable('catalog/product'),
        'sku',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )->addForeignKey(
        $installer->getFkName(
            'mediotype_instant_rebate/products',
            'instant_rebate_id',
            'mediotype_instant_rebate/instantRebate',
            'id'
        ),
        'instant_rebate_id',
        $installer->getTable('mediotype_instant_rebate/instantRebate'),
        'id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )->addForeignKey(
        $installer->getFkName(
            'mediotype_instant_rebate/products',
            'shopping_cart_rule_id',
            'salesrule/rule',
            'rule_id'
        ),
        'shopping_cart_rule_id',
        $installer->getTable('salesrule/rule'),
        'rule_id',
        Varien_Db_Ddl_Table::ACTION_SET_NULL,
        Varien_Db_Ddl_Table::ACTION_SET_NULL
    );

$installer->getConnection()->createTable($instantRebateProductTable);


$instantRebateConversionsTable = $installer->getConnection()->newTable($this->getTable('mediotype_instant_rebate/conversions'));

$instantRebateConversionsTable->addColumn('id',
    Varien_Db_Ddl_Table::TYPE_INTEGER,
    11,
    array(
        'primary' => true,
        'identity' => true,
        'nullable' => false
    )
)->addColumn('order_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        11,
        array(
            'nullable' => false
        )
    )->addColumn('order_item_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        11,
        array(
            'nullable' => false
        )
    )->addColumn('product_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        11,
        array(
            'nullable' => false
        )
    )->addColumn('customer_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        11,
        array(
            'nullable' => true
        )
    )->addColumn('instant_rebate_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        11,
        array(
            'nullable' => false
        )
    )->addColumn('instant_rebate_amount_applied',
        Varien_Db_Ddl_Table::TYPE_DECIMAL,
        '12,4',
        array(
            'nullable' => false,
            'default' => '0.0000'

        )
    )->addColumn('opt_in',
        Varien_Db_Ddl_Table::TYPE_TINYINT,
        4,
        array(
            'nullable' => false,
            'default' => 1
        )
    )->addForeignKey(
        $installer->getFkName(
            'mediotype_instant_rebate/conversions',
            'order_id',
            'sales/order',
            'entity_id'
        ),
        'order_id',
        $installer->getTable('sales/order'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_RESTRICT,
        Varien_Db_Ddl_Table::ACTION_RESTRICT
    )->addForeignKey(
        $installer->getFkName(
            'mediotype_instant_rebate/conversions',
            'order_item_id',
            'sales/order_item',
            'entity_id'
        ),
        'order_item_id',
        $installer->getTable('sales/order_item'),
        'item_id',
        Varien_Db_Ddl_Table::ACTION_RESTRICT,
        Varien_Db_Ddl_Table::ACTION_RESTRICT
    )->addForeignKey(
        $installer->getFkName(
            'mediotype_instant_rebate/conversions',
            'product_id',
            'catalog/product',
            'entity_id'
        ),
        'product_id',
        $installer->getTable('catalog/product'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_NO_ACTION,
        Varien_Db_Ddl_Table::ACTION_NO_ACTION
    )->addForeignKey(
        $installer->getFkName(
            'mediotype_instant_rebate/conversions',
            'instant_rebate_id',
            'mediotype_instant_rebate/instantRebate',
            'id'
        ),
        'instant_rebate_id',
        $installer->getTable('mediotype_instant_rebate/instantRebate'),
        'id',
        Varien_Db_Ddl_Table::ACTION_RESTRICT,
        Varien_Db_Ddl_Table::ACTION_RESTRICT
    );

$installer->getConnection()->createTable($instantRebateConversionsTable);

//set product skus to be able to be used in shopping cart rules
$collection = Mage::getResourceModel('catalog/product_attribute_collection')->addFieldToFilter('attribute_code','sku');
foreach($collection->getItems() as $skuAttribute)
{
    $skuAttribute->setData('is_used_for_promo_rules',1);
    $skuAttribute->save();
}

$installer->endSetup();