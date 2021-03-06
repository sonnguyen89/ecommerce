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
 * @category    Mage
 * @package     Mage_Core
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */


/**
 * Mysqi Resource
 *
 * @category   Mage
 * @package    Mage_Core
 * @module     Core
 */
class Mage_Core_Model_Resource_Type_Db_Mysqli extends Mage_Core_Model_Resource_Type_Db
{
    /**
     * Get Connection
     *
     * @param Array $config
     * @return Varien_Db_Adapter_Mysqli 
     */
    public function getConnection($config)
    {
        $configArr = (array)$config;
        $configArr['profiler'] = !empty($configArr['profiler']) && $configArr['profiler']!=='false';

        $conn = new Varien_Db_Adapter_Mysqli($configArr);

        if (!empty($configArr['initStatements']) && $conn) {
            $conn->query($configArr['initStatements']);
        }

        return $conn;
    }
}
