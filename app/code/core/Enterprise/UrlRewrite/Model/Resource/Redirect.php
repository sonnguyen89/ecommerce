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
 * @package     Enterprise_UrlRewrite
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */

/**
 * UrlRewrite redirect resource model
 *
 * @category    Enterprise
 * @package     Enterprise_UrlRewrite
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Enterprise_UrlRewrite_Model_Resource_Redirect extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize resource
     */
    protected function _construct()
    {
        $this->_init('enterprise_urlrewrite/redirect', 'redirect_id');
    }

    /**
     * Load rewrite object by request path
     *
     * @param Mage_Core_Model_Abstract $object
     * @param string $requestPath
     * @return Enterprise_UrlRewrite_Model_Url_Rewrite
     */
    public function loadByRequestPath(Mage_Core_Model_Abstract $object, $requestPath)
    {
        $select = $this->_getReadAdapter()->select()
            ->from(array('main_table' => $this->getMainTable()))
            ->join(array('url_rewrite' => $this->getTable('enterprise_urlrewrite/url_rewrite')),
                'url_rewrite.request_path = ' . $this->_getReadAdapter()->quote($requestPath),
                array('request_path', 'url_rewrite_id', 'is_system')
            )
            ->join(array('rewrite_relation' => $this->getTable('enterprise_urlrewrite/redirect_rewrite')),
                'rewrite_relation.url_rewrite_id = url_rewrite.url_rewrite_id', array()
            )->where('main_table.redirect_id = rewrite_relation.redirect_id');

        $result = $this->_getReadAdapter()->fetchRow($select);
        if (!empty($result)) {
            $object->setData($result);
        }

        return $this;
    }
}
