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
 * Queue Task model
 *
 * @category    Enterprise
 * @package     Enterprise_Queue
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Enterprise_Queue_Model_Queue_Task extends Mage_Core_Model_Abstract
{

    /**#@+
     * Task statuses
     */
    const TASK_STATUS_PENDING       = 0;
    const TASK_STATUS_IN_PROGRESS   = 1;
    const TASK_STATUS_COMPLETED     = 2;
    const TASK_STATUS_SKIPPED       = 3;
    /**#@-*/

    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init('enterprise_queue/queue_task');
    }

    /**
     * Delete tasks by specified ids and statuses
     *
     * @param array $ids
     * @param array $exceptStatuses
     * @return Enterprise_Queue_Model_Queue_Task
     */
    public function deleteTasks(array $ids, array $exceptStatuses = array())
    {
        if (!in_array(self::TASK_STATUS_IN_PROGRESS, $exceptStatuses)) {
            $exceptStatuses[] = self::TASK_STATUS_IN_PROGRESS;
        }
        $criteria = array('statuses' => $exceptStatuses);

        if (!empty($ids)) {
            $criteria['ids'] = $ids;
        }

        $this->_getResource()->deleteTasksByCriteria($criteria);
        return $this;
    }

    /**
     * Set timestamp for new task
     *
     * @return Mage_Core_Model_Abstract
     */
    protected function _beforeSave()
    {
        $this->_updateDataOnSave();
        return parent::_beforeSave();
    }

    /**
     * Update data on save
     *
     * Update "created at" value on first save
     *
     * @return Enterprise_Queue_Model_Queue_Task
     */
    protected function _updateDataOnSave()
    {
        if (!$this->getId() && !$this->hasCreatedAt()) {
            $this->setCreatedAt($this->getResource()->formatDate(time()));
        }
        return $this;
    }

    /**
     * Retrieve list of queue task statuses
     *
     * @return array
     */
    public function getStatusesArray()
    {
        return array(
            self::TASK_STATUS_IN_PROGRESS => Mage::helper('enterprise_queue')->__('In Progress'),
            self::TASK_STATUS_PENDING => Mage::helper('enterprise_queue')->__('Pending'),
            self::TASK_STATUS_COMPLETED => Mage::helper('enterprise_queue')->__('Completed'),
            self::TASK_STATUS_SKIPPED => Mage::helper('enterprise_queue')->__('Skipped')
        );
    }
}
