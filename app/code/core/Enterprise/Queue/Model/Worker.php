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
 * Queue Worker
 *
 * @category    Enterprise
 * @package     Enterprise_Queue
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Enterprise_Queue_Model_Worker
{
    /**
     * Queue instance
     *
     * @var Zend_Queue
     */
    private $_queue;

    /**
     * Application instance
     *
     * @var Mage_Core_Model_App
     */
    protected $_app;

    /**
     * Config instance
     *
     * @var Mage_Core_Model_Config
     */
    protected $_config;

    /**
     * Logger instance
     *
     * @var Mage_Core_Model_Logger
     */
    protected $_logger;

    /**
     * Initialize worker
     *
     * @param array $args
     */
    public function __construct($args)
    {
        $this->_app = isset($args['app']) ? $args['app'] : Mage::app();
        $this->_config = isset($args['config']) ? $args['config'] : Mage::getConfig();
        $this->_logger = isset($args['logger']) ? $args['logger'] : Mage::getModel('core/logger');
        $helper = isset($args['helper']) ? $args['helper'] : Mage::helper('enterprise_queue');
        $this->_queue = isset($args['queue']) ? $args['queue'] : $helper->getMainQueue();
    }

    /**
     * Execute specified task
     *
     * @param array $task
     * @return bool
     */
    public function execute($task)
    {
        $taskDetails = Zend_Json::decode($task['data']);
        if (isset($taskDetails['task_name'])) {
            try {
                $this->_config->loadEventObservers('workers');
                $this->_app->addEventArea('workers');
                $this->getReporter()->reportTaskProcessing($task['task_id']);
                $this->_dispatchEvent($taskDetails['task_name'], $taskDetails['params']);
                $this->getReporter()->reportTaskCompleted($task['task_id']);
            } catch (Exception $e) {
                $this->getReporter()->reportTaskFailed($task['task_id']);
                $this->_logger->logException($e);
            }
        }
        return true;
    }

    /**
     * Retrieve reporter instance
     *
     * @return Enterprise_Queue_Model_Worker_Reporter_Interface
     */
    public function getReporter()
    {
        return ($this->_queue->getAdapter() instanceof Enterprise_Queue_Model_Adapter_Db)
            ? Mage::getModel('enterprise_queue/worker_reporter_native')
            : Mage::getModel('enterprise_queue/worker_reporter_null');
    }

    /**
     * Dispatch event
     *
     * @param string $name
     * @param array $data
     */
    protected function _dispatchEvent($name, array $data = array())
    {
        Mage::dispatchEvent($name, $data);
    }
}
