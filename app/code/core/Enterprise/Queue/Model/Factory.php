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
 * Queue Factory class
 *
 * @category    Enterprise
 * @package     Enterprise_Queue
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Enterprise_Queue_Model_Factory
{
    /**
     * Instantiates and returns model instance
     *
     * @param string $className
     * @param array $args
     * @return false|Mage_Core_Model_Abstract
     */
    public function getModel($className, array $args = array())
    {
        return Mage::getModel($className, $args);
    }

    /**
     * Returns Zend_Queue instance
     *
     * @param string|Zend_Queue_Adapter|array|Zend_Config|null String or adapter instance,
     * or options array or Zend_Config instance $adapter
     * @param Zend_Config|array $options Zend_Config or a configuration array
     * @return Zend_Queue
     */
    public function getZendQueue($adapter, $options)
    {
        return new Zend_Queue($adapter, $options);
    }
}
