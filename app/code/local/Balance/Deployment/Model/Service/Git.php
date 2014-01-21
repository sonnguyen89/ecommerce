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
class Balance_Deployment_Model_Service_Git extends Balance_Deployment_Model_Service_Abstract
{
	
    protected function _init()
    {
    	$this->_repo = array('sync' => array('cmd'			=> 'sh '.Mage::getModuleDir('', 'Balance_Deployment').DS.'shell'.DS.'gitsync.sh '.Mage::getBaseDir('base'),
    										 'description'	=> 'synchronize code from GitHub: master branch.',
    										 'buttonText'   => 'Sync Code',
    										 'type'			=> 'button',
    										 'context' => 'both'),
    						 'log'  => array('cmd'			=> 'sh '.Mage::getModuleDir('', 'Balance_Deployment').DS.'shell'.DS.'gitlog.sh '.Mage::getBaseDir('base'),
    						 				 'description'  => 'show git log for recent commits.',
    						 				 'buttonText'   => 'Show Recent Commits',
    						 				 'type'			=> 'button',
    						 				 'context' => 'both'),
			    			 'revert'  => array('cmd'			=> 'sh '.Mage::getModuleDir('', 'Balance_Deployment').DS.'shell'.DS.'gitcheckout.sh '.Mage::getBaseDir('base'),
					    					 'description'  => 'revert current code to certain point',
					    					 'buttonText'   => 'Revert to',
					    					 'type'			=> 'buttonText',
					    					 'context' => 'both'),
			    			 'reset'  => array('cmd'			=> 'sh '.Mage::getModuleDir('', 'Balance_Deployment').DS.'shell'.DS.'gitreset.sh '.Mage::getBaseDir('base'),
					    					 'description'  => 'reset to head revision',
					    					 'buttonText'   => 'Reset Head',
					    					 'type'			=> 'button',
					    					 'context' => 'both'),
    						);
    }
    
    protected function _initName()
    {
    	$this->setName('git');
    }
}



