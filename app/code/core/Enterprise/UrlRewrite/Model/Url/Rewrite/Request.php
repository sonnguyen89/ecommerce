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
 * Class for HTTP request rewrite
 *
 * @category    Enterprise
 * @package     Enterprise_UrlRewrite
 * @author      Magento Core Team <core@magentocommerce.com>
 * @property Enterprise_UrlRewrite_Model_Url_Rewrite _rewrite
 */
class Enterprise_UrlRewrite_Model_Url_Rewrite_Request extends Mage_Core_Model_Url_Rewrite_Request
{

    /**
     * Set URL rewrite instance
     *
     * @param array $args
     */
    public function __construct(array $args = array())
    {
        if (empty($args['rewrite'])) {
            $args['rewrite'] = Mage::getModel('enterprise_urlrewrite/url_rewrite');
        }
        parent::__construct($args);
    }

    /**
     * Implement logic of custom rewrites
     *
     * @return bool
     */
    protected function _rewriteDb()
    {
        $this->_loadRewrite();

        if (!$this->_rewrite->getId()) {
            return false;
        }

        $this->_setRequestPathAlias()
            ->_processRedirectOptions();

        return true;
    }

    /**
     * Set request path alias to request model
     *
     * @return Enterprise_UrlRewrite_Model_Url_Rewrite_Request
     */
    protected function _setRequestPathAlias()
    {
        $this->_request->setAlias(
            Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
            $this->_rewrite->getRequestPath()
        );
        return $this;
    }

    /**
     * Load rewrite model
     *
     * @return Enterprise_UrlRewrite_Model_Url_Rewrite_Request
     */
    protected function _loadRewrite()
    {
        $requestPath = $this->_getRequestPath();

        $paths = $this->_getSystemPaths($requestPath);
        if (count($paths)) {
            $this->_rewrite->loadByRequestPath($paths);
        }

        if (!$this->_rewrite->getId() || ($this->_rewrite->getId() && !$this->_rewrite->getIsSystem())) {
            /**
             * Try to load data by request path from redirect model
             */
            $this->_rewrite->setData(
                $this->_getRedirect($requestPath)->getData()
            );
        }

        return $this;
    }

    /**
     * Get redirect model with load data by request path
     *
     * @param string $requestPath
     * @return Enterprise_UrlRewrite_Model_Redirect
     */
    protected function _getRedirect($requestPath)
    {
        /** @var $redirect Enterprise_UrlRewrite_Model_Redirect */
        $redirect = $this->_factory->getModel('enterprise_urlrewrite/redirect');
        $redirect->loadByRequestPath($requestPath);

        return $redirect;
    }

    /**
     * Get system path from request path
     *
     * @param string $requestPath
     * @return array
     */
    protected function _getSystemPaths($requestPath)
    {
        $systemPath = explode('/', $requestPath);
        $suffixPart = array_pop($systemPath);
        if (false !== strrpos($suffixPart, '.')) {
            $suffixPart = substr($suffixPart, 0, strrpos($suffixPart, '.'));
        }
        $systemPath[] = $suffixPart;

        return $systemPath;
    }

    /**
     * Get request path from requested path info
     *
     * @return string
     */
    protected function _getRequestPath()
    {
        $pathInfo    = $this->_request->getPathInfo();
        $requestPath = trim($pathInfo, '/');

        return $requestPath;
    }
}
