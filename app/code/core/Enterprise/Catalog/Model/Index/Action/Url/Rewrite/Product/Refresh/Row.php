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
 * Url Rewrite Product Refresh Action
 *
 * @category    Enterprise
 * @package     Enterprise_Catalog
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Enterprise_Catalog_Model_Index_Action_Url_Rewrite_Product_Refresh_Row
    extends Enterprise_Catalog_Model_Index_Action_Url_Rewrite_Product_Refresh
{
    /**
     * Product id
     *
     * @var int
     */
    protected $_productId;

    /**
     * Constructor with parameters
     * Array of arguments with keys
     *  - 'product_id' int
     *
     * @param array $args
     */
    public function __construct(array $args)
    {
        parent::__construct($args);
        $this->_productId = $args['product_id'];
    }

    /**
     * Execute refresh operation.
     *  - clean product url rewrites
     *  - refresh product url rewrites
     *  - refresh product to url rewrite relations
     *
     * @return Enterprise_Mview_Model_Action_Interface
     * @throws Enterprise_Index_Exception
     */
    public function execute()
    {
        if (!$this->_productId) {
            return $this;
        }

        $this->_connection->beginTransaction();
        try {
            $this->_cleanOldUrlRewrite();
            $this->_refreshUrlRewrite();
            $this->_refreshRelation();
            $this->_connection->commit();
        } catch (Exception $e) {
            $this->_connection->rollBack();
            throw new Enterprise_Index_Exception($e->getMessage(), $e->getCode());
        }

        return $this;
    }

    /**
     * Prepares url rewrite select query
     *
     * @return Varien_Db_Select
     */
    protected function _getUrlRewriteSelectSql()
    {
        $select = parent::_getUrlRewriteSelectSql();
        $select->where('uk.entity_id = ?', $this->_productId);
        return $select;
    }

    /**
     * Prepares refresh relation select query for given product_id
     *
     * @return Varien_Db_Select
     */
    protected function _getRefreshRelationSelectSql()
    {
        $select = parent::_getRefreshRelationSelectSql();
        $select->where('uk.entity_id = ?', $this->_productId);
        return $select;
    }

    /**
     * Returns select query for deleting old url rewrites.
     *
     * @return Varien_Db_Select
     */
    protected function _getCleanOldUrlRewriteSelect()
    {
        $select = parent::_getCleanOldUrlRewriteSelect();
        $select->where('rp.product_id = ?', $this->_productId);
        return $select;
    }
}
