<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$tableName = $installer->getTable('tmcore/module');
$installer->getConnection()->changeColumn($tableName, 'license_key', 'identity_key', 'TEXT', true);
$installer->getConnection()->addColumn($tableName, 'store_ids', 'VARCHAR(64) NOT NULL');

$installer->endSetup();
