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
 * @package     Enterprise_Queue
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */

class Enterprise_Queue_Adminhtml_QueueController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Queue tasks list
     */
    public function indexAction()
    {
        $this->_title($this->__('Queue'))->_title($this->__('Tasks'));

        $this->loadLayout();
        $this->_setActiveMenu('system/tools/manage_queue');
        $this->renderLayout();
    }

    /**
     * Delete specified tasks using grid massaction
     */
    public function massDeleteAction()
    {
        $ids = $this->getRequest()->getParam('task');
        if (!is_array($ids)) {
            $this->_getSession()->addError($this->__('Please select task(s).'));
        } else {
            try {
                /** @var $model Enterprise_Queue_Model_Queue_Task */
                $model = Mage::getModel('enterprise_queue/queue_task');
                $model->deleteTasks($ids);

                $this->_getSession()->addSuccess(
                    $this->__('Task(s) has been successfully deleted.')
                );
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addError(
                    Mage::helper('enterprise_queue')->__('An error occurred while mass deleting tasks. Please review log and try again.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * Check the permission to run it
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        switch ($this->getRequest()->getActionName()) {
            case 'massDelete':
                $acl = 'admin/system/queue/manage';
                break;
            default:
                $acl = 'admin/system/queue';
        }
        return Mage::getSingleton('admin/session')->isAllowed($acl);
    }
}
