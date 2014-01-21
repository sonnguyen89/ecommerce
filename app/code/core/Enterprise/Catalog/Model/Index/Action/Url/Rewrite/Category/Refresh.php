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
 * Url Rewrite Category Refresh Action
 *
 * @category    Enterprise
 * @package     Enterprise_Catalog
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Enterprise_Catalog_Model_Index_Action_Url_Rewrite_Category_Refresh
    extends Enterprise_UrlRewrite_Model_Index_Action_Url_Rewrite_RefreshAbstract
    implements Enterprise_Mview_Model_Action_Interface
{
    /**
     * Base target path.
     */
    const BASE_TARGET_PATH = 'catalog/category/view/id/';

    /**
     * Initialize unique value, relation columns and relation
     *
     * @param array $args
     */
    public function __construct(array $args)
    {
        parent::__construct($args);

        $this->_relationColumns = array('category_id', 'store_id', 'url_rewrite_id');
        $this->_relationTableName = $this->_getTable('enterprise_catalog/category');
    }

    /**
     * Returns select query for deleting old url rewrites.
     *
     * @return Varien_Db_Select
     */
    protected function _getCleanOldUrlRewriteSelect()
    {
        return $this->_connection->select()
            ->from(array('ur' => $this->_getTable('enterprise_urlrewrite/url_rewrite')))
            ->join(array('rc' => $this->_getTable('enterprise_catalog/category')),
                'rc.url_rewrite_id = ur.url_rewrite_id', array());
    }

    /**
     * Prepares refresh relation select query
     *
     * @return Varien_Db_Select
     */
    protected function _getRefreshRelationSelectSql()
    {
        return $this->_connection->select()
            ->from(array('ur' => $this->_getTable('enterprise_urlrewrite/url_rewrite')),
                array('category_id' => 'uk.entity_id', 'uk.store_id', 'url_rewrite_id'))
            ->join(array('uk' => $this->_getTable(array('catalog/category', 'url_key'))),
                'uk.value_id = ur.value_id', array()
            )
            ->where('guid = ?', $this->_uniqueIdentifier);
    }

    /**
     * Prepares url rewrite select query
     *
     * @return Varien_Db_Select
     */
    protected function _getUrlRewriteSelectSql()
    {
        $caseSql = $this->_connection->getCaseSql('',
            array('ur.inc IS NULL OR ' .
                $this->_connection->quoteIdentifier('m.value') . ' = 1' => new Zend_Db_Expr("''")),
            $this->_connection->getConcatSql(array("'-'", 'ur.inc'))
        );

        $sRequestPath = $this->_connection->getConcatSql(array(
            $this->_connection->quoteIdentifier('uk.value'),
            $caseSql
        ));

        $sTargetPath = $this->_connection->getConcatSql(array("'" . self::BASE_TARGET_PATH . "'", 'uk.entity_id'));

        return $this->_connection->select()
            ->from(array('uk' => $this->_getTable(array('catalog/category', 'url_key'))),
                array(
                    'request_path'  => new Zend_Db_Expr($sRequestPath),
                    'target_path'   => new Zend_Db_Expr($sTargetPath),
                    'guid'          => new Zend_Db_Expr($this->_connection->quote($this->_uniqueIdentifier)),
                    'is_system'     => new Zend_Db_Expr(1),
                    'identifier'    => new Zend_Db_Expr($sRequestPath),
                    'value_id'      => 'uk.value_id'
                ))
            ->joinLeft(array('ur' => $this->_getTable('enterprise_urlrewrite/url_rewrite')),
                'ur.identifier = ' . $this->_connection->quoteIdentifier('uk.value'), array())
            ->joinLeft(array('m' => $this->_getTable('enterprise_index/multiplier')),
                'ur.identifier IS NOT NULL', array());
    }
}
