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
 * Native worker reporter
 *
 * @category    Enterprise
 * @package     Enterprise_Queue
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Enterprise_Queue_Model_Worker_Reporter_Native implements Enterprise_Queue_Model_Worker_Reporter_Interface
{
    /**
     * Queue task instance
     *
     * @var Enterprise_Queue_Model_Queue_Task
     */
    protected $_task;

    public function __construct($args)
    {
        $this->_task = isset($args['task']) ? $args['task'] : Mage::getModel('enterprise_queue/queue_task');
    }

    /**
     * Report processing task
     *
     * @param int $taskId
     * @return bool|Enterprise_Queue_Model_Worker_Reporter_Native
     */
    public function reportTaskProcessing($taskId)
    {
        $this->_task->load($taskId);
        if ($this->_task->getStatus() == Enterprise_Queue_Model_Queue_Task::TASK_STATUS_SKIPPED) {
            return true;
        }
        $this->_task->setStatus(Enterprise_Queue_Model_Queue_Task::TASK_STATUS_IN_PROGRESS);
        $this->_task->save();
        return $this;
    }

    /**
     * Report completed task
     *
     * @param int $taskId
     * @return Enterprise_Queue_Model_Worker_Reporter_Native
     */
    public function reportTaskCompleted($taskId)
    {
        $this->_task->setStatus(Enterprise_Queue_Model_Queue_Task::TASK_STATUS_COMPLETED);
        $this->_task->save();
        return $this;
    }

    /**
     * Report failed task
     *
     * @param int $taskId
     * @return Enterprise_Queue_Model_Worker_Reporter_Native
     */
    public function reportTaskFailed($taskId)
    {
        $this->_task->setStatus(Enterprise_Queue_Model_Queue_Task::TASK_STATUS_SKIPPED);
        $this->_task->save();
        return $this;
    }
}
