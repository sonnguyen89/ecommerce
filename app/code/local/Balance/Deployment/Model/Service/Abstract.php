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
abstract class Balance_Deployment_Model_Service_Abstract
{
	/**
	 * store key/value pair for command
	 * @var array
	 */
	protected $_repo = array();
	
	/**
	 * name of the service, will be used to generate js function name
	 * @var string
	 */
	protected $_name;
	
	/**
	 * server object
	 * @var Balance_Deployment_Model_Server_Abstract
	 */
	protected $_server;
	
	public function __construct()
	{
		$this->_init();
		$this->_initName();
	}
	
	abstract protected function _init();
	
	abstract protected function _initName();
	
	/**
	 * get repo array
	 * @return array
	 */
	public function getRepo()
	{
		return $this->_repo;
	}
	
	/**
	 * execute linux command
	 * @param $string $cmd
	 */
	public function execute(Varien_Object $param){
		if($this->canExecute($param->cmd)){
			return $this->_execute($param);
		}
		return false;
	}
	
	/**
	 * set service name
	 * @param string $name
	 * @return Balance_Deployment_Model_Service_Abstract
	 */
	public function setName($name)
	{
		$this->_name = $name;
		return $this;
	}
	
	/**
	 * get service name
	 */
	public function getName()
	{
		return $this->_name;
	}
	
	/**
	 * set server for the service to run
	 * @param Balance_Deployment_Model_Server_Abstract $server
	 * @return Balance_Deployment_Model_Service_Abstract
	 */
	public function setServer(Balance_Deployment_Model_Server_Abstract $server)
	{
		$this->_server = $server;
		return $this;
	}
	
	/**
	 * get server for the service to run
	 */
	public function getServer()
	{
		return $this->_server;
	}
	
	/**
	 * check if the command is available
	 * @param string $cmd
	 */
	public function canExecute($cmd)
	{
		return isset($this->_repo[$cmd]);
	}
	
	/**
	 * get ssh string for remote server
	 */
	protected function _getSshString()
	{
		$server = $this->getServer();
		return 'ssh '.Mage::helper('deployment')->getServerUser().'@'.$server->getIp().' -p '.$server->getPort().' exec ';
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
		$localCommand = $arr['cmd'];
		
		//append first parameter
		if(strlen($param->getData('param'))){
			$localCommand .= ' '.$param->param;
		}
		
		$localCommand .= ' "'.strtoupper($arr['description']).'"';
		
		if($server instanceof Balance_Deployment_Model_Server_Web && in_array($arr['context'], array('both','remote'))){
			$remoteCommand = $this->_getSshString().$localCommand;
			return shell_exec($remoteCommand);
		}elseif($server instanceof Balance_Deployment_Model_Server_Admin && in_array($arr['context'], array('both','local'))){
			return shell_exec($localCommand);
		}
	}
}
