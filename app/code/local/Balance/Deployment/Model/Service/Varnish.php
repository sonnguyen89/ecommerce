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
class Balance_Deployment_Model_Service_Varnish extends Balance_Deployment_Model_Service_Abstract
{
	protected function _init()
	{
		$this->_repo = array('clear' => array('cmd'			=> 'varnishadm -T $1:6082 ban.url "^/.*"',
											  'description'	=> 'Clear Varnish',
										      'buttonText'   => 'Clear Varnish Cache',
											  'type'			=> 'button',
											  'context' => 'both'),
		);
	}
	
	protected function _initName()
	{
		$this->setName('varnish');
	}

	protected function _getSshString()
	{
		return '';
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
			$cmd = $this->_getSshString().str_replace('$1', $server->getIp(), $arr['cmd']);
		}else{
			$cmd = str_replace('$1', '127.0.0.1', $arr['cmd']);
		}
		return strtoupper($arr['description']).' '.shell_exec($cmd);
	}
}
