<?php
/**
 * Pronto product price extension for Magento
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
 * the Balance Deployment module to newer versions in the future.
 * If you wish to customize the Balance Deployment module for your needs
 * please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Balance
 * @package    Balance_Deployment
 * @copyright  Copyright (C) 2013 Balance Internet (http://balanceinternet.com.au)
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
 * @author     Richard Cai <richard@balanceinternet.com.au>
 */
class Balance_Deployment_Helper_Varnish extends Mage_Core_Helper_Data
{
	/**
	 * purge all varnish cache
	 * @return string $output
	 */
	public function purgeAll()
	{
		$paramObject = new Varien_Object();
		$paramObject->cmd = 'clear';
		
		$outputRenderer = Mage::getModel("deployment/itemrenderer_output");
		//init output
		$output = '';
		//get service object
		$serviceObject = Mage::getModel('deployment/service_varnish');
		//get admin server object
		$admin = Mage::getModel('deployment/server_admin');
		$output .= $admin->setOutputRenderer($outputRenderer)->executeService($serviceObject, $paramObject);
		//get web servers object
		$ips = Mage::getStoreConfig('balance_deployment/'.$service.'/ips');
		//@todo check ips
		if(strpos($ips, ';')){
			$ips = explode(';',$ips);
			foreach($ips as $ip){
				if(strlen($ip)){
					$web = Mage::getModel('deployment/server_web', $ip);
					$output .= $web->setOutputRenderer($outputRenderer)->executeService($serviceObject, $paramObject);
				}
			}
		}
		return strip_tags($output);
	}
}
