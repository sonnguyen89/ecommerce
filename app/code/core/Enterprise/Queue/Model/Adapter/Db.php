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
 * Queue Db Adapter model
 *
 * @category    Enterprise
 * @package     Enterprise_Queue
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Enterprise_Queue_Model_Adapter_Db extends Zend_Queue_Adapter_AdapterAbstract
{
    /**
     * Queue instance
     *
     * @var Enterprise_Queue_Model_Queue
     */
    protected $_queueModel;

    /**
     * Queue task instance
     *
     * @var Enterprise_Queue_Model_Queue_Task
     */
    protected $_queueTaskModel;

    /**
     * Logger instance
     *
     * @var Mage_Core_Model_Logger
     */
    protected $_logger;

    /**
     * Queue collection instance
     *
     * @var Enterprise_Queue_Model_Resource_Queue_Collection
     */
    protected $_queueCollection;

    /**
     * Queue task collection instance
     *
     * @var Enterprise_Queue_Model_Resource_Queue_Task_Collection
     */
    protected $_queueTaskCollection;

    public function __construct($options, Zend_Queue $queue = null)
    {
        $this->_queueModel = isset($options['queue_model'])
            ? $options['queue_model'] : Mage::getModel('enterprise_queue/queue');
        unset($options['queue_model']);

        $this->_queueTaskModel = isset($options['task_model'])
            ? $options['task_model'] : Mage::getModel('enterprise_queue/queue_task');
        unset($options['task_model']);

        $this->_queueCollection = isset($options['queue_collection'])
            ? $options['queue_collection'] : Mage::getResourceModel('enterprise_queue/queue_collection');
        unset($options['queue_collection']);

        $this->_queueTaskCollection = isset($options['queue_task_collection'])
            ? $options['queue_task_collection'] : Mage::getResourceModel('enterprise_queue/queue_task_collection');
        unset($options['queue_task_collection']);

        $this->_logger = isset($options['logger'])
            ? $options['logger'] : Mage::getModel('core/logger');
        unset($options['logger']);

        parent::__construct($options, $queue);
    }

    /**
     * Get the queue ID
     *
     * @param string $name
     * @return int
     * @throws Enterprise_Queue_Exception
     */
    protected function _getQueueId($name)
    {
        if (array_key_exists($name, $this->_queues)) {
            return $this->_queues[$name];
        }

        $queue = $this->_getQueueByName($name);

        if ($queue === false) {
            throw new Enterprise_Queue_Exception('Queue does not exist: ' . $name);
        }

        $this->_queues[$name] = (int)$queue->getId();
        return $this->_queues[$name];
    }

    /**
     * Retrieve queue instance by specified name.
     *
     * @param string $name
     * @return bool|Enterprise_Queue_Model_Queue
     */
    protected function _getQueueByName($name)
    {
        $queue = $this->_queueModel->load($name, 'name');
        return is_null($queue->getId()) ? false : $queue;
    }

    /**
     * Check whether queue with specified name exists
     *
     * @param string $name
     * @return bool
     */
    public function isExists($name)
    {
        return (bool)$this->_getQueueByName($name);
    }

    /**
     * Create a new queue
     *
     * @param string $name
     * @param int $timeout
     * @return bool
     */
    public function create($name, $timeout = null)
    {
        if ($this->isExists($name)) {
            return false;
        }

        $this->_queueModel->setName($name);
        $this->_queueModel->setTimeout(($timeout === null) ? self::CREATE_TIMEOUT_DEFAULT : (int)$timeout);

        try {
            $this->_queueModel->save();
            return true;
        } catch (Exception $e) {
            $this->_logger->logException($e);
            return false;
        }
    }

    /**
     * Delete a queue and all of it's messages
     *
     * @param string $name
     * @return bool
     * @throws Enterprise_Queue_Exception
     */
    public function delete($name)
    {
        $queue = $this->_getQueueByName($name);

        if ($queue === false) {
            return false;
        }

        try {
            $queue->delete();
        } catch (Exception $e) {
            throw new Enterprise_Queue_Exception($e->getMessage(), $e->getCode(), $e);
        }

        if (array_key_exists($name, $this->_queues)) {
            unset($this->_queues[$name]);
        }

        return true;
    }

    /*
     * Retrieve list of all available queues
     *
     * @return array
     */
    public function getQueues()
    {
        if (empty($this->_queues)) {
            $this->_queues = $this->_queueCollection->toOptionHash();
        }
        return array_keys($this->_queues);
    }



    /**
     * Return the approximate number of messages in the queue
     *
     * @param Zend_Queue|null $queue
     * @return int
     */
    public function count(Zend_Queue $queue = null)
    {
        if ($queue === null) {
            $queue = $this->_queue;
        }

        $this->_queueTaskCollection->addFieldToFilter('queue_id', $this->_getQueueId($queue->getName()));
        return $this->_queueTaskCollection->count();
    }

    /**
     * Send a message to the queue
     *
     * @param string $message
     * @param Zend_Queue|null $queue
     * @return Zend_Queue_Message
     * @throws Enterprise_Queue_Exception
     */
    public function send($message, Zend_Queue $queue = null)
    {
        if ($queue === null) {
            $queue = $this->_queue;
        }

        if (is_scalar($message)) {
            $message = (string)$message;
        }
        if (is_string($message)) {
            $message = trim($message);
        }

        if (!$this->isExists($queue->getName())) {
            throw new Enterprise_Queue_Exception('Queue does not exist: ' . $queue->getName());
        }

        $this->_queueTaskModel->setData(array(
            'queue_id' => $this->_getQueueId($queue->getName()),
            'handle' => md5(uniqid(rand(), true)),
            'data' => $message,
            'status' => Enterprise_Queue_Model_Queue_Task::TASK_STATUS_PENDING,
        ));

        try {
            $this->_queueTaskModel->save();
        } catch (Exception $e) {
            throw new Enterprise_Queue_Exception($e->getMessage(), $e->getCode(), $e);
        }

        $className = $queue->getMessageClass();
        return new $className(array(
            'queue' => $queue,
            'data'  => $this->_queueTaskModel->getData(),
        ));
    }

    /**
     * Get messages in the queue
     *
     * @param int $maxMessages
     * @param int $timeout
     * @param Zend_Queue $queue
     * @return Zend_Queue_Message_Iterator
     * @throws Enterprise_Queue_Exception
     */
    public function receive($maxMessages = null, $timeout = null, Zend_Queue $queue = null)
    {
        if ($maxMessages === null) {
            $maxMessages = 1;
        }
        if ($queue === null) {
            $queue = $this->_queue;
        }

        $this->_queueTaskCollection->addFieldToFilter('queue_id', $this->_getQueueId($queue->getName()))
            ->addFieldToFilter('status', Enterprise_Queue_Model_Queue_Task::TASK_STATUS_PENDING)
            ->setLimit($maxMessages);

        $tasks = array();
        foreach ($this->_queueTaskCollection as $item) {
            $tasks[] = $item->getData();
        }

        $options = array(
            'queue'        => $queue,
            'data'         => $tasks,
            'messageClass' => $queue->getMessageClass(),
        );

        $className = $queue->getMessageSetClass();
        if (!class_exists($className)) {
            Zend_Loader::loadClass($className);
        }
        return new $className($options);
    }

    /**
     * Delete a message from the queue
     *
     * @param Zend_Queue_Message $message
     * @return bool
     */
    public function deleteMessage(Zend_Queue_Message $message)
    {
        try {
            $msg = $this->_queueTaskModel->load($message->handle, 'handle');
            if (!is_null($msg->getId())) {
                $msg->delete();
                return true;
            }
        } catch (Exception $e) {
            $this->_logger->logException($e);
        }
        return false;
    }

    /**
     * Return a list of queue capabilities functions
     *
     * @return array
     */
    public function getCapabilities()
    {
        return array(
            'create'        => true,
            'delete'        => true,
            'send'          => true,
            'receive'       => true,
            'deleteMessage' => true,
            'getQueues'     => true,
            'count'         => true,
            'isExists'      => true
        );
    }
}
