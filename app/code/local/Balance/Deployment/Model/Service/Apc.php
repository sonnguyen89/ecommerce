<?php
/**
 * Deployment script across server cluster extension for Magento
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
 * @copyright  Copyright (C) 2012 Balance Internet (http://www.balanceinternet.com.au/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Short description of the class
 *
 * Long description of the class (if any...)
 *
 * @category   Balance
 * @package    Balance_Deployment
 * @subpackage Model
 * @author     Richard Cai <richard@balanceinternet.com.au>
 */
class Balance_Deployment_Model_Service_Apc extends Balance_Deployment_Model_Service_Abstract
{
 	protected function _init()
    {
    	$this->_repo = array('clear' => array('cmd'			=> '',
    										  'description'	=> 'Clear APC',
    										  'buttonText'   => 'Clear APC Cache',
    										  'type'			=> 'button',
    										  'context' => 'both'),
    						);
    }
    
    protected function _initName()
    {
    	$this->setName('apc');
    }
    
	/**
	 * execute command base on server type
	 * @param string $cmd
	 * @return string out;
	 */
	protected function _execute(Varien_Object $param)
	{
		$arr = $this->_repo[$param->getData('cmd')];
		$server = $this->getServer();
		
		if($server instanceof Balance_Deployment_Model_Server_Web && in_array($arr['context'], array('both','remote'))){
				$url = 'http://'.$server->getIp().'/apc.php';
				$client = new Zend_Http_Client($url);
				return strtoupper($arr['description']).' '.$client->request();
		}else{
				$ip = $_SERVER['SERVER_ADDR'];
				$url = 'http://'.$ip.'/apc.php';
				$client = new Zend_Http_Client($url);
				return strtoupper($arr['description']).' '.$client->request();
		}
	}
}
