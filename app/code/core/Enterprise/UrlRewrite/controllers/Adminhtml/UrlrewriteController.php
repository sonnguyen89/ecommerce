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
 * Urlrewrites adminhtml controller
 *
 * @category    Enterprise
 * @package     Enterprise_UrlRewrite
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Enterprise_UrlRewrite_Adminhtml_UrlrewriteController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Get and load by ID redirect model
     *
     * @return Enterprise_UrlRewrite_Model_Redirect
     */
    protected function _getRedirect()
    {
        if (!Mage::registry('current_url_redirect')) {
            /* @var $redirect Enterprise_UrlRewrite_Model_Redirect */
            $redirect = Mage::getSingleton('enterprise_urlrewrite/redirect');
            Mage::register('current_url_redirect', $redirect);

            $id = $this->_getRedirectId();
            if ($id) {
                $redirect->load($id);
            }
        } else {
            $redirect = Mage::registry('current_url_redirect');
        }

        return $redirect;
    }

    /**
     * Get redirect ID
     *
     * @return int|null
     */
    protected function _getRedirectId()
    {
        return $this->getRequest()->getParam('id');
    }

    /**
     * Show url redirect index page
     */
    public function indexAction()
    {
        $this->_title($this->__('Redirect Rules'));
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Select page for choose source of redirect
     */
    public function selectAction()
    {
        $this->_title($this->__('Redirect Rules'))
            ->_title($this->__('Select URL Redirect Type'));

        $this->_getRedirect(); //init redirect model

        $this->_initLayout();
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->renderLayout();
    }

    /**
     * Initialize additional handle once select action is executed.
     *
     * @return Enterprise_UrlRewrite_Adminhtml_UrlrewriteController
     */
    protected function _initLayout()
    {
        $update = $this->getLayout()->getUpdate();
        $update->addHandle('default');
        $this->addActionLayoutHandles();
        $type = (string)$this->getRequest()->getParam('type');
        if ($type) {
            $this->getLayout()->getUpdate()->addHandle('adminhtml_urlrewrite_redirect_type_' . $type);
        }
        $this->loadLayoutUpdates();

        $this->generateLayoutXml()->generateLayoutBlocks();
        $this->_isLayoutLoaded = true;
        $this->_initLayoutMessages('adminhtml/session');
        return $this;
    }

    /**
     * Show url redirect edit/create page
     */
    public function editAction()
    {
        $this->_title($this->__('Redirect Rules'))
            ->_title($this->__('URL Redirect'));

        $redirect = $this->_getRedirect();
        $sessionData = $this->_getSession()->getData('url_redirect_data');
        if ($sessionData) {
            $redirect->addData($sessionData);
        }

        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * URL redirect save action
     */
    public function saveAction()
    {
        try {
            if ($this->getRequest()->isPost()) {
                $params = $this->getRequest()->getPost();
                unset($params['form_key']);
                $this->_getSession()->setData('url_redirect_data', $params);

                $model = $this->_getRedirect();
                if (!$model->getRedirectId() && isset($params['identifier'])) {
                    $model->load($params['identifier'], 'identifier');
                    if ($model->getRedirectId()) {
                        Mage::throwException($this->__('URL Redirect with same Request Path already exists.'));
                    }
                }

                $model->addData($params);
                $model->save();
                $this->_getSession()->unsetData('url_redirect_data');
                $this->_getSession()->addSuccess($this->__('Redirect item has been saved.'));
            }
            $this->_redirect('*/*/');
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->_redirectReferer($this->getUrl('*/*/edit', array('id' => $this->_getRedirectId())));
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError($this->__('An error occurred while saving the URL redirect.'));
            $this->_redirect('*/*/edit', array('id' => $this->_getRedirectId()));
        }
    }

    /**
     * URL redirect delete action
     */
    public function deleteAction()
    {
        try {
            $model = $this->_getRedirect();
            if ($model->getId()) {
                $model->delete();
                $this->_getSession()->addSuccess($this->__('Redirect item has been deleted.'));
            } else {
                $this->_getSession()->addError($this->__('Redirect item not found.'));
            }
            $this->_redirect('*/*/');
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->_redirect('*/*/edit', array('id' => $this->_getRedirectId()));
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError($this->__('An error occurred during deleting the redirect item.'));
            $this->_redirect('*/*/edit', array('id' => $this->_getRedirectId()));
        }
    }

    /**
     * Check whether this controller is allowed in admin permissions
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('catalog/urlrewrite');
    }
}
