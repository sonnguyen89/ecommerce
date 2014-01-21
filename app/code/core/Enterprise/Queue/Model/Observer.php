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

/**
 * Queue observer
 *
 * @category    Enterprise
 * @package     Enterprise_Queue
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Enterprise_Queue_Model_Observer
{
    /**
     * Queue task model instance
     *
     * @var Enterprise_Queue_Model_Queue_Task
     */
    protected $_queueTaskModel;

    /**
     * Queue helper instance
     *
     * @var Enterprise_Queue_Helper_Data
     */
    protected $_queueHelper;

    /**
     * Class constructor
     *
     * @param array $data
     * @throws InvalidArgumentException
     */
    public function __construct(array $data = array())
    {
        if (isset($data['queue_task_model'])) {
            if (!$data['queue_task_model'] instanceof Enterprise_Queue_Model_Queue_Task) {
                throw new InvalidArgumentException(
                    'Argument "queue_task_model" is expected to be an instance of "Enterprise_Queue_Model_Queue_Task".'
                );
            }
            $this->_queueTaskModel = $data['queue_task_model'];
            unset($data['queue_task_model']);
        }
        if (isset($data['queue_helper'])) {
            if (!$data['queue_helper'] instanceof Enterprise_Queue_Helper_Data) {
                throw new InvalidArgumentException(
                    'Argument "queue_helper" is expected to be an instance of "Enterprise_Queue_Helper_Data".'
                );
            }
            $this->_queueHelper = $data['queue_helper'];
            unset($data['queue_helper']);
        }
    }

    /**
     * Delete all completed/failed tasks
     */
    public function deleteUnnecessaryMessages()
    {
        $model = $this->_queueTaskModel ?: Mage::getModel('enterprise_queue/queue_task');
        $model->deleteTasks(array(), array(
            Enterprise_Queue_Model_Queue_Task::TASK_STATUS_PENDING,
            Enterprise_Queue_Model_Queue_Task::TASK_STATUS_IN_PROGRESS
        ));
    }

    /**
     * Execute queue tasks
     */
    public function runTasks()
    {
        $helper = $this->_queueHelper ?: Mage::helper('enterprise_queue');
        $helper->getReceiver()->work();
    }
}
