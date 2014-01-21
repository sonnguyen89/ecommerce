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
class Balance_Deployment_Model_Service_Memcached extends Balance_Deployment_Model_Service_Abstract
{
	protected function _init()
	{
		$this->_repo = array('clear' => array('cmd'			=> '',
											  'description'	=> 'clear all magento cache',
										      'buttonText'   => 'Clear memcached server',
											  'type'			=> 'button',
											  'context' => 'both'),
		);
	}
	
	protected function _initName()
	{
		$this->setName('memcached');
	}
	
	/**
	 * execute command base on server type
	 * @param string $cmd
	 * @return string out;
	 */
	protected function _execute(Varien_Object $param)
	{
		Mage::app()->getCacheInstance()->flush();
		Mage::app()->cleanCache();
		$baseDir = Mage::getBaseDir('var').DS.'full_page_cache'.DS;
		if(file_exists($baseDir)){
			$cmd = 'rm -Rf '.$baseDir.'*';
			shell_exec($cmd);
		}
		return 'Magento Cache Cleared';
	}
}
