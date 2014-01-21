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
 * @category    Mage
 * @package     Mage_Catalog
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */

/**
 * @var $this Mage_Catalog_Model_Resource_Setup
 */

/**
 * Add unique index for catalog_product_entity_url_key table
 */
$this->getConnection()->addIndex(
    $this->getTable(array('catalog/product', 'url_key')),
    $this->getIdxName(
        array('catalog/product', 'url_key'),
        array('store_id', 'value'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    ),
    array('store_id', 'value'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
);


/**
 * Add unique index for catalog_category_entity_url_key table
 */
$this->getConnection()->addIndex(
    $this->getTable(array('catalog/category', 'url_key')),
    $this->getIdxName(
        array('catalog/category', 'url_key'),
        array('store_id', 'value'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    ),
    array('store_id', 'value'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
);
