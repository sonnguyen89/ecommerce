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
abstract class Balance_Deployment_Model_Server_Abstract
{
	/**
	 * render the output to terminal
	 * @var Balance_Deployment_Model_Itemrenderer_Output
	 */
	protected $_outputRenderer;
	
	/**
	 * set output render
	 * @param Balance_Deployment_Model_Itemrenderer_Output $render
	 * @return Balance_Deployment_Model_Server_Admin
	 */
	public function setOutputRenderer(Balance_Deployment_Model_Itemrenderer_Output $render)
	{
		$this->_outputRenderer = $render;
		return $this;
	}
	
	/**
	 * get output render
	 * @return Balance_Deployment_Model_Itemrenderer_Output
	 */
	public function getOutputRenderer()
	{
		return $this->_outputRenderer;
	}
	
	/**
	 * execute service in server context
	 * @param Balance_Deployment_Model_Service_Abstract $service
	 * @param string $cmd
	 * @return string
	 */
	abstract public function executeService(Balance_Deployment_Model_Service_Abstract $service, Varien_Object $param);
}
