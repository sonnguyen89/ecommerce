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
 * Queue Helper
 *
 * @category    Enterprise
 * @package     Enterprise_Queue
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Enterprise_Queue_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Default queue name xml path
     *
     * @var string
     */
    const XML_PATH_DEFAULT_QUEUE_NAME = 'global/queue/default';

    /**
     * Consumer instance
     *
     * @var Enterprise_Queue_Model_Consumer
     */
    protected $_consumer;

    /**
     * Receiver instance
     *
     * @var Enterprise_Queue_Model_Receiver
     */
    protected $_receiver;

    /**
     * Config instance
     *
     * @var Mage_Core_Model_Config
     */
    protected $_config;

    /**
     * Factory instance
     *
     * @var Enterprise_Queue_Model_Factory
     */
    protected $_factory;

    /**
     * Class constructor
     *
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        $factory = !empty($data['factory']) ? $data['factory'] : Mage::getModel('enterprise_queue/factory');
        $this->_setFactory($factory);
        $config  = !empty($data['config']) ? $data['config'] : Mage::getConfig();
        $this->_setConfig($config);
    }

    /**
     * Retrieve instance of main queue
     *
     * @return Zend_Queue
     */
    public function getMainQueue()
    {
        return $this->_getQueue($this->_getDefaultQueueName());
    }

    /**
     * Retrieve consumer instance
     *
     * @return Enterprise_Queue_Model_Consumer
     */
    public function getConsumer()
    {
        if (null === $this->_consumer) {
            $this->_consumer = $this->_factory->getModel('enterprise_queue/consumer',
                array('queue' => $this->getMainQueue()
            ));
        }
        return $this->_consumer;
    }

    /**
     * Retrieve receiver instance
     *
     * @return Enterprise_Queue_Model_Receiver
     */
    public function getReceiver()
    {
        if (null === $this->_receiver) {
            $this->_receiver = $this->_factory->getModel('enterprise_queue/receiver',
                array('queue' => $this->getMainQueue())
            );
        }
        return $this->_receiver;
    }

    /**
     * Sets factory instance
     *
     * @param Enterprise_Queue_Model_Factory $factory
     */
    protected function _setFactory(Enterprise_Queue_Model_Factory $factory)
    {
        $this->_factory = $factory;
    }

    /**
     * Sets factory instance
     *
     * @param Mage_Core_Model_Config $config
     */
    protected function _setConfig(Mage_Core_Model_Config $config)
    {
        $this->_config = $config;
    }

    /**
     * Retrieve name of main queue
     *
     * @return string
     */
    protected function _getDefaultQueueName()
    {
        return (string)$this->_config->getNode(self::XML_PATH_DEFAULT_QUEUE_NAME);
    }

    /**
     * Retrieve queue instance by specified name
     *
     * @param string $name
     * @return Zend_Queue
     */
    protected function _getQueue($name)
    {
        $queue = $this->_factory->getModel('enterprise_queue/queue')->load($name, 'name');
        if (!$queue->getId()) {
            $queue->setName($name);
            $queue->save();
        }
        $adapter = $this->_factory->getModel('enterprise_queue/adapter_db');
        return $this->_factory->getZendQueue($adapter, $queue->getData());
    }
}
