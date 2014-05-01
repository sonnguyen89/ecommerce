<?php

class TM_Core_Adminhtml_Tmcore_ModuleController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('templates_master/tmcore_module')
            ->_addBreadcrumb('Templates Master', 'Templates Master')
            ->_addBreadcrumb(Mage::helper('tmcore')->__('Modules'), Mage::helper('tmcore')->__('Modules'));
        return $this;
    }

    public function indexAction()
    {
        $this->_initAction();
        $this->renderLayout();
    }

    /**
     * Placeholder grid for AJAX request
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function manageAction()
    {
        if (!$this->getRequest()->getParam('id')) {
            return $this->_redirect('*/*/index');
        }

        $module = Mage::getModel('tmcore/module');
        $module->load($this->getRequest()->getParam('id'));

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $module->addData($data);
        }

        Mage::register('tmcore_module', $module);

        $this->_initAction()
            ->_addBreadcrumb(Mage::helper('tmcore')->__('Manage'), Mage::helper('tmcore')->__('Manage'));
        $this->renderLayout();
    }

    public function runAction()
    {
        if (!$this->getRequest()->isPost()) {
            return $this->_redirect('*/*/index');
        }

        /**
         * @var TM_Core_Model_Module
         */
        $module = Mage::getModel('tmcore/module');
        $module->load($this->getRequest()->getParam('id'))
            ->setSkipUpgrade($this->getRequest()->getPost('skip_upgrade', false))
            ->setNewStores($this->getRequest()->getPost('new_stores', array()))
            ->setIdentityKey($this->getRequest()->getParam('identity_key'));

        $result = $module->validateLicense();
        if (is_array($result) && isset($result['error'])) {
            Mage::getSingleton('adminhtml/session')->setFormData($this->getRequest()->getPost());
            Mage::getSingleton('adminhtml/session')->addError(
                // try to translate remote response
                call_user_func_array(array(Mage::helper('tmcore'), '__'), $result['error'])
            );
            return $this->_redirect('*/*/manage', array('id' => $module->getId()));
        }

        $module->up();

        Mage::app()->cleanCache();
        Mage::dispatchEvent('adminhtml_cache_flush_system');

        $groupedErrors = $module->getMessageLogger()->getErrors();
        if (count($groupedErrors)) {
            foreach ($groupedErrors as $type => $errors) {
                foreach ($errors as $error) {
                    if (is_array($error)) {
                        $message = $error['message'];
                    } else {
                        $message = $error;
                    }
                    Mage::getSingleton('adminhtml/session')->addError($message);
                }
            }
            Mage::getSingleton('adminhtml/session')->setFormData($this->getRequest()->getPost());
            return $this->_redirect('*/*/manage', array('id' => $module->getId()));
        }

        Mage::getSingleton('adminhtml/session')->setFormData(false);
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('tmcore')->__("The module has been saved"));
        $this->_redirect('*/*/');
    }
}
