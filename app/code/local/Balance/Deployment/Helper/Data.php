<?php
/**
 *  extension for Magento
 *
 * Long description of this file (if any...)
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade
 * the Witchery Apparel21 module to newer versions in the future.
 * If you wish to customize the Witchery Apparel21 module for your needs
 * please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Witchery
 * @package    Witchery_Apparel21
 * @copyright  Copyright (C) 2012 
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Short description of the class
 *
 * Long description of the class (if any...)
 *
 * @category   Balance
 * @package    Balance_Deployment
 * @subpackage Helper
 * @author     
 */
class Balance_Deployment_Helper_Data extends Mage_Core_Helper_Data
{
	const XML_PATH_DEPLOYMENT_GIT_ENABLED = 'balance_deployment/git/enabled';
	const XML_PATH_DEPLOYMENT_VARNISH_ENABLED = 'balance_deployment/varnish/enabled';
	const XML_PATH_DEPLOYMENT_APC_ENABLED = 'balance_deployment/apc/enabled';
	const XML_PATH_DEPLOYMENT_MEMCACHED_ENABLED = 'balance_deployment/memcached/enabled';
	const XML_PATH_DEPLOYMENT_SERVER_USER = 'balance_deployment/about/user';
	
	public function canGit()
	{
		return Mage::getStoreConfigFlag(self::XML_PATH_DEPLOYMENT_GIT_ENABLED);
	}
	
	public function canVarnish()
	{
		return Mage::getStoreConfig(self::XML_PATH_DEPLOYMENT_VARNISH_ENABLED);
	}
	
	public function canApc()
	{
		return Mage::getStoreConfig(self::XML_PATH_DEPLOYMENT_APC_ENABLED);
	}
	
	public function canMemcached()
	{
		return Mage::getStoreConfig(self::XML_PATH_DEPLOYMENT_MEMCACHED_ENABLED);
	}
	
	public function getServerUser()
	{
		return Mage::getStoreConfig(self::XML_PATH_DEPLOYMENT_SERVER_USER);
	}
}
