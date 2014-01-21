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

/* @var $this Mage_Core_Model_Resource_Setup */

$tables = array();
$taskTable = $this->getTable('enterprise_queue/task');
$queueTable = $this->getTable('enterprise_queue/queue');

$tables[] = $this->getConnection()
    ->newTable($taskTable)
    ->addColumn('task_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'nullable' => false,
        'primary' => true
    ), 'Task Id')
    ->addColumn('queue_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array('nullable' => false), 'Queue Id')
    ->addColumn('handle', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(), 'Task Handle')
    ->addColumn('data', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(), 'Task Data')
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
        'nullable' => false
    ), 'Task Status')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => false
    ), 'Created At')
    ->setComment('Enterprise Queue Task');

$tables[] = $this->getConnection()
    ->newTable($queueTable)
    ->addColumn('queue_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'nullable' => false,
        'primary' => true
    ), 'Queue Id')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, 100, array('nullable' => false), 'Queue Name')
    ->setComment('Enterprise Queue');

foreach ($tables as $table) {
    $this->getConnection()->createTable($table);
}

$this->getConnection()->addIndex(
    $taskTable,
    $this->getIdxName('enterprise_queue/task', 'queue_id', Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX),
    'queue_id'
);

$this->getConnection()->addForeignKey(
    $this->getFkName('enterprise_queue/task', 'queue_id', 'enterprise_queue/queue', 'queue_id'),
    $taskTable,
    'queue_id',
    $queueTable,
    'queue_id'
);
