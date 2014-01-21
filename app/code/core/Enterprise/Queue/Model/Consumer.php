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
 * Queue Consumer model
 *
 * @category    Enterprise
 * @package     Enterprise_Queue
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Enterprise_Queue_Model_Consumer
{
    /**
     * Queue instance
     *
     * @var Zend_Queue
     */
    private $_queue;

    /**
     * Initialize consumer
     *
     * @param array $args
     * @throws InvalidArgumentException
     */
    public function __construct($args)
    {
        $helper = isset($args['helper']) ? $args['helper'] : Mage::helper('enterprise_queue');
        $this->_queue = isset($args['queue']) ? $args['queue'] : $helper->getMainQueue();

        if (false === ($this->_queue instanceof Zend_Queue)) {
            throw new InvalidArgumentException('Given arguments does not have queue.');
        }
    }

    /**
     * Add task to queue
     *
     * array (
     *     'task_name' => '...',
     *     'params' => array(),
     * )
     *
     * @param array $task
     * @return Enterprise_Queue_Model_Consumer
     */
    public function addTask(array $task)
    {
        $this->_queue->send(Zend_Json::encode($task));
        return $this;
    }
}
