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

/**
 * Catalog product resource model
 *
 * @category    Enterprise
 * @package     Enterprise_Catalog
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Enterprise_Catalog_Model_Resource_Product extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
    * Initialize resource
    */
    protected function _construct()
    {
        $this->_init('enterprise_catalog/product', 'id');
    }

    /**
     * Load Url rewrite by specified product
     *
     * @param Mage_Core_Model_Abstract $object
     * @param Mage_Catalog_Model_Product $product
     * @return Enterprise_Catalog_Model_Resource_Product
     */
    public function loadByProduct(Mage_Core_Model_Abstract $object, Mage_Catalog_Model_Product $product)
    {
        $idField = $this->_getReadAdapter()
            ->getIfNullSql('url_rewrite_cat.id', 'default_urc.id');
        $requestPath = $this->_getReadAdapter()
            ->getIfNullSql('url_rewrite.request_path', 'default_ur.request_path');

        $select = $this->_getReadAdapter()->select()
            ->from(array('main_table' => $this->getTable('catalog/product')),
                array($this->getIdFieldName() => $idField))
            ->where('main_table.entity_id = ?', (int)$product->getId())
            ->joinLeft(array('url_rewrite_cat' => $this->getTable('enterprise_catalog/product')),
                'url_rewrite_cat.product_id = main_table.entity_id AND url_rewrite_cat.store_id = ' .
                    (int)$product->getStoreId(),
                array(''))
            ->joinLeft(array('url_rewrite' => $this->getTable('enterprise_urlrewrite/url_rewrite')),
                'url_rewrite.url_rewrite_id = url_rewrite_cat.url_rewrite_id',
                array(''))
            ->joinLeft(array('default_urc' => $this->getTable('enterprise_catalog/product')),
                'default_urc.product_id = main_table.entity_id AND default_urc.store_id = 0',
                array(''))
            ->joinLeft(array('default_ur' => $this->getTable('enterprise_urlrewrite/url_rewrite')),
                'default_ur.url_rewrite_id = default_urc.url_rewrite_id',
                array('request_path' => $requestPath));
        $result = $this->_getReadAdapter()->fetchRow($select);

        if (isset($result['id']) && !empty($result['id'])) {
            $object->setData($result);
        }

        $this->unserializeFields($object);
        $this->_afterLoad($object);

        return $this;
    }
}
