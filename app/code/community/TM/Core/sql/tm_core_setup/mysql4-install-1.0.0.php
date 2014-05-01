<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

/**
 * Create table 'tmcore/module'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('tmcore/module'))
    ->addColumn('code', Varien_Db_Ddl_Table::TYPE_TEXT, 50, array(
            'nullable'  => false,
            'primary'   => true,
        )
    )
//    ->addColumn('version', Varien_Db_Ddl_Table::TYPE_TEXT, 50)
    ->addColumn('data_version', Varien_Db_Ddl_Table::TYPE_TEXT, 50)
    ->addColumn('license_key', Varien_Db_Ddl_Table::TYPE_TEXT, 32);
$installer->getConnection()->createTable($table);

$installer->endSetup();
