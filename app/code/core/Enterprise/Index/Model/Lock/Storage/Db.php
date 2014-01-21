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
 * @package     Enterprise_Index
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */

/**
 * Database lock storage
 *
 * @category    Enterprise
 * @package     Enterprise_Index
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Enterprise_Index_Model_Lock_Storage_Db implements Enterprise_Index_Model_Lock_Storage_Interface
{
    /**
     * @var Enterprise_Index_Model_Resource_Helper_Abstract
     */
    protected $_helper;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_helper = Mage::getResourceHelper('enterprise_index');
    }

    /**
     * Set named lock
     *
     * @param string $lockName
     * @return int
     */
    public function setLock($lockName)
    {
        return $this->_helper->setLock($lockName);
    }

    /**
     * Release named lock
     *
     * @param string $lockName
     * @return int|null
     */
    public function releaseLock($lockName)
    {
        return $this->_helper->releaseLock($lockName);
    }

    /**
     * Check whether the lock exists
     *
     * @param string $lockName
     * @return bool
     */
    public function isLockExists($lockName)
    {
        return $this->_helper->isLocked($lockName);
    }
}
