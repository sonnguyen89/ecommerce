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
 * Catalog event listener class
 * The following events are used:
 *  -
 *
 * @category    Enterprise
 * @package     Enterprise_Catalog
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Enterprise_Catalog_Model_Observer
{
    /**
     * Base category target path.
     */
    const BASE_CATEGORY_TARGET_PATH = 'catalog/category/view/id/';

    /**
     * Base product target path.
     */
    const BASE_PRODUCT_TARGET_PATH  = 'catalog/product/view/id/';

    /**
     * Factory instance
     *
     * @var Mage_Core_Model_Factory
     */
    protected $_factory;

    /**
     * App model
     *
     * @var Mage_Core_Model_App
     */
    protected $_app;

    /**
     * Constructor with parameters.
     *
     * Array of arguments with keys:
     *  - 'factory' Enterprise_Mview_Model_Factory
     *
     * @param array $args
     */
    public function __construct(array $args = array())
    {
        $this->_factory = !empty($args['factory']) ? $args['factory'] : Mage::getSingleton('core/factory');
        $this->_app     = !empty($args['app']) ? $args['app'] : Mage::app();
    }

    /**
     * Save custom redirect for product
     *
     * @param Varien_Event_Observer $observer
     */
    public function saveCategoryCustomRedirect(Varien_Event_Observer $observer)
    {
        /** @var $category Mage_Catalog_Model_Category */
        $category = $observer->getEvent()->getCategory();
        if (!is_object($category) || !$category->getId()) {
            return;
        }

        if ($category->getData('save_rewrites_history')) {
            //Initialize request_path value
            $requestPath = $this->_getCategoryRequestPath($category);
            if ($requestPath) {
                $this->_saveRedirect(
                    $requestPath,
                    self::BASE_CATEGORY_TARGET_PATH . $category->getId()
                );
            }
        }
    }

    /**
     * Save custom redirect for product
     *
     * @param Varien_Event_Observer $observer
     */
    public function saveProductCustomRedirect(Varien_Event_Observer $observer)
    {
        /** @var $product Mage_Catalog_Model_Product */
        $product = $observer->getEvent()->getProduct();
        if (!is_object($product) || !$product->getId()) {
            return;
        }

        if ($product->getData('save_rewrites_history')) {
            $requestPath = $this->_getProductRequestPath($product);
            if ($requestPath) {
                $this->_saveRedirect(
                    $requestPath,
                    self::BASE_PRODUCT_TARGET_PATH . $product->getId()
                );
            }
        }
    }

    /**
     * Get product request path
     *
     * @param Mage_Catalog_Model_Product $product
     * @return string
     */
    protected function _getProductRequestPath(Mage_Catalog_Model_Product $product)
    {
        /**
         * Initialize request_path value
         */
        $product->getProductUrl();

        /** @var $helper Enterprise_Catalog_Helper_Data */
        $helper = $this->_factory->getHelper('enterprise_catalog');
        return $helper->getProductRequestPath($product->getRequestPath(), $product->getStoreId());
    }

    /**
     * Get category request path
     *
     * @param Mage_Catalog_Model_Category $category
     * @return string
     */
    protected function _getCategoryRequestPath(Mage_Catalog_Model_Category $category)
    {
        /**
         * Initialize request_path value
         */
        $category->getUrl();

        /** @var $helper Enterprise_Catalog_Helper_Data */
        $helper = $this->_factory->getHelper('enterprise_catalog');
        return $helper->getCategoryRequestPath($category->getRequestPath(), $category->getStoreId());
    }

    /**
     * Save request and target paths via redirect model
     *
     * @param string $requestPath
     * @param string $targetPath
     * @return Enterprise_Catalog_Model_Observer
     */
    protected function _saveRedirect($requestPath, $targetPath)
    {
        /* @var $model Enterprise_UrlRewrite_Model_Redirect */
        $model = $this->_factory->getModel('enterprise_urlrewrite/redirect');
        $model->setIdentifier($requestPath)
            ->setTargetPath($targetPath)
            ->save();
        return $this;
    }

    /**
     * Set form renderer for url_key attribute
     *
     * @param Varien_Event_Observer $observer
     */
    public function setFormRendererAttributeUrlkey(Varien_Event_Observer $observer)
    {
        $urlKey = $observer->getEvent()->getForm()->getElement('url_key');

        if ($urlKey instanceof Varien_Data_Form_Element_Abstract) {
            $urlKey->setRenderer(
                $this->_app->getFrontController()->getAction()->getLayout()
                    ->createBlock('enterprise_catalog/adminhtml_catalog_form_renderer_attribute_urlkey')
            );
        }
    }

    /**
     * Change status on Require Reindex for Product Attributes indexer
     *
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function invalidateAttributeIndexer(Varien_Event_Observer $observer)
    {
        /** @var $process Mage_Index_Model_Process */
        $process = Mage::getSingleton('index/indexer')->getProcessByCode('catalog_product_attribute');
        if ($process) {
            $process->changeStatus(Mage_Index_Model_Process::STATUS_REQUIRE_REINDEX);
        }
        return $this;
    }
}
