<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition License
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magentocommerce.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Enterprise
 * @package     Enterprise_Catalog
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */

/** @var $this Mage_Core_Model_Resource_Setup */

/**
 * Create enterprise_catalog_redirect_category table
 */
$table = $this->getConnection()
    ->newTable($this->getTable('enterprise_catalog/category'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
    'identity'  => true,
    'unsigned'  => true,
    'nullable'  => false,
    'primary'   => true,
), 'Relation Id')
    ->addColumn('category_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
    'nullable'  => false,
    'unsigned'  => true,
), 'Category Id')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
    'nullable'  => false,
    'unsigned'  => true,
), 'Store Id')
    ->addColumn('url_rewrite_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
    'nullable'  => false,
    'unsigned'  => true,
), 'Rewrite Id')
    ->addIndex(
    $this->getIdxName(
        'enterprise_catalog/category', array('category_id', 'store_id'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE),
    array('category_id', 'store_id'),
    array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
)
    ->addForeignKey(
    $this->getFkName(
        'enterprise_catalog/category', 'url_rewrite_id',
        'enterprise_urlrewrite/url_rewrite', 'url_rewrite_id'
    ),
    'url_rewrite_id',
    $this->getTable('enterprise_urlrewrite/url_rewrite'),
    'url_rewrite_id',
    Varien_Db_Ddl_Table::ACTION_CASCADE,
    Varien_Db_Ddl_Table::ACTION_NO_ACTION
)
    ->setComment('Relation between rewrites and categories');

$this->getConnection()->createTable($table);

/**
 * Create enterprise_catalog_redirect_product table
 */
$table = $this->getConnection()
    ->newTable($this->getTable('enterprise_catalog/product'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
    'identity'  => true,
    'unsigned'  => true,
    'nullable'  => false,
    'primary'   => true,
), 'Relation Id')
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
    'nullable'  => false,
    'unsigned'  => true,
), 'Product Id')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
    'nullable'  => false,
    'unsigned'  => true,
), 'Store Id')
    ->addColumn('url_rewrite_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
    'nullable'  => false,
    'unsigned'  => true,
), 'Rewrite Id')
    ->addIndex(
    $this->getIdxName(
        'enterprise_catalog/category', array('product_id', 'store_id'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE),
    array('product_id', 'store_id'),
    array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
)
    ->addForeignKey(
    $this->getFkName(
        'enterprise_catalog/product', 'url_rewrite_id',
        'enterprise_urlrewrite/url_rewrite', 'url_rewrite_id'
    ),
    'url_rewrite_id',
    $this->getTable('enterprise_urlrewrite/url_rewrite'),
    'url_rewrite_id',
    Varien_Db_Ddl_Table::ACTION_CASCADE,
    Varien_Db_Ddl_Table::ACTION_NO_ACTION
)
    ->setComment('Relation between rewrites and products');

$this->getConnection()->createTable($table);

$rows = $this->getConnection()->fetchAll(
    $this->getConnection()->select()
        ->from($this->getTable('core/config_data'))
        ->where('path IN (?)', array('catalog/seo/product_url_suffix','catalog/seo/category_url_suffix'))
        ->where('value <>  \'\'')
        ->where('value IS NOT NULL')
);

foreach ($rows as $row) {
    $row['value'] = ltrim($row['value'], '.'); // remove dot "." from start
    $this->getConnection()->update($this->getTable('core/config_data'), $row, 'config_id=' . $row['config_id']);
}

/** @var $metadata Enterprise_Mview_Model_Metadata */
$metadata = Mage::getModel('enterprise_mview/metadata');
$metadata->setViewName('enterprise_url_rewrite_category')
    ->setTableName($this->getTable('enterprise_url_rewrite_category'))
    ->setStatus(Enterprise_Mview_Model_Metadata::STATUS_INVALID)
    ->save();

$metadata = Mage::getModel('enterprise_mview/metadata');
$metadata->setViewName('enterprise_url_rewrite_product')
    ->setTableName($this->getTable('enterprise_url_rewrite_product'))
    ->setStatus(Enterprise_Mview_Model_Metadata::STATUS_INVALID)
    ->save();

/* @var  $client Enterprise_Mview_Model_Client */
$client = Mage::getModel('enterprise_mview/client');
$client->init('enterprise_url_rewrite_product');
$client->getMetadata()
    ->setKeyColumn('entity_id')
    ->setGroupCode('catalog_url_product')
    ->setStatus(Enterprise_Mview_Model_Metadata::STATUS_INVALID)
    ->save();

$client->execute('enterprise_urlrewrite/index_action_url_rewrite_changelog_create', array(
    'table_name' => $this->getTable(array('catalog/product', 'url_key')),
));

$client->execute('enterprise_mview/action_changelog_subscription_create', array(
    'target_table'  => $this->getTable(array('catalog/product', 'url_key')),
    'target_column' => 'entity_id'
));


/* @var $client Enterprise_Mview_Model_Client */
$client = Mage::getModel('enterprise_mview/client');
$client->init('enterprise_url_rewrite_category');
$client->getMetadata()
    ->setKeyColumn('entity_id')
    ->setGroupCode('catalog_url_category')
    ->setStatus(Enterprise_Mview_Model_Metadata::STATUS_INVALID)
    ->save();

$client->execute('enterprise_urlrewrite/index_action_url_rewrite_changelog_create', array(
    'table_name' => $this->getTable(array('catalog/category', 'url_key'))
));

$client->execute('enterprise_mview/action_changelog_subscription_create', array(
    'target_table'  => $this->getTable(array('catalog/category', 'url_key')),
    'target_column' => 'entity_id'
));

/** @var $client Enterprise_Mview_Model_Client */
$client = Mage::getModel('enterprise_mview/client');
$client->init('catalog_category_product_index');
$client->getMetadata()
    ->setKeyColumn('product_id')
    ->setViewName('catalog_category_product_view')
    ->setGroupCode('catalog_category_product')
    ->setStatus(Enterprise_Mview_Model_Metadata::STATUS_INVALID)
    ->save();

$client->execute('enterprise_catalog/index_action_catalog_category_product_changelog_create', array(
    'table_name' => $this->getTable('catalog/category_product_index'),
));

$productSubscriptions = array(
    $this->getTable('catalog/category_product')      => 'product_id',
    $this->getTable(array('catalog/product', 'int')) => 'entity_id',
);

foreach($productSubscriptions as $targetTable => $targetColumn) {
    $arguments = array(
        'target_table'  => $targetTable,
        'target_column' => $targetColumn,
    );
    $client->execute('enterprise_mview/action_changelog_subscription_create', $arguments);
}

$client->init('catalog_category_product_cat');
$client->getMetadata()
    ->setKeyColumn('category_id')
    ->setViewName('catalog_category_product_cat_view')
    ->setGroupCode('catalog_category_product')
    ->setStatus(Enterprise_Mview_Model_Metadata::STATUS_INVALID)
    ->save();

$client->execute('enterprise_catalog/index_action_catalog_category_product_changelog_create', array(
    'table_name' => $this->getTable('catalog/category_product_index'),
));

$categorySubscriptions = array(
    $this->getTable('catalog/category')               => 'entity_id',
    $this->getTable(array('catalog/category', 'int')) => 'entity_id',
);
foreach($categorySubscriptions as $targetTable => $targetColumn) {
    $arguments = array(
        'target_table'  => $targetTable,
        'target_column' => $targetColumn,
    );
    $client->execute('enterprise_mview/action_changelog_subscription_create', $arguments);
}

$events = array();
/** @var $eventModel  Enterprise_Mview_Model_Event */
$eventCollection = Mage::getModel('enterprise_mview/event')->getCollection()->load();
foreach ($eventCollection as $event) {
    /** @var $event Enterprise_Mview_Model_Event */
    $events[$event->getName()] = $event->getMviewEventId();
}

$eventsMetadataMapping = array(
    'catalog_category_product_index' => array(
        'add_store',
        'delete_store',
        'delete_store_group',
        'delete_website',
    ),
);
/** @var $metadataModel Enterprise_Mview_Model_Metadata */
$metadataModel = Mage::getModel('enterprise_mview/metadata');
foreach ($eventsMetadataMapping as $indexTable => $mappedEvents) {
    $metadataModel->load($this->getTable($indexTable), 'table_name');
    foreach ($mappedEvents as $eventName) {
        $data = array(
            'mview_event_id' => $events[$eventName],
            'metadata_id'    => $metadataModel->getId(),
        );
        $this->getConnection()->insert($this->getTable('enterprise_mview/metadata_event'), $data);
    }
}
