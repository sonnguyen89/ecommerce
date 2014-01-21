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
 * Queue task resource model
 *
 * @category    Enterprise
 * @package     Enterprise_Queue
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Enterprise_Queue_Model_Resource_Queue_Task extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize main table and primary field
     */
    protected function _construct()
    {
        $this->_init('enterprise_queue/task', 'task_id');
    }

    /**
     * Delete tasks by specified criteria
     *
     * @param array $criteria
     * @return Enterprise_Queue_Model_Resource_Queue_Task
     */
    public function deleteTasksByCriteria(array $criteria)
    {
        $condition = array();

        if (isset($criteria['ids']) && is_array($criteria['ids'])) {
            $condition['task_id IN(?)'] = $criteria['ids'];
        }

        if (isset($criteria['statuses']) && is_array($criteria['statuses'])) {
            $condition['status NOT IN(?)'] = $criteria['statuses'];
        }

        $this->_getWriteAdapter()->delete($this->getMainTable(), $condition);
        return $this;
    }
}
