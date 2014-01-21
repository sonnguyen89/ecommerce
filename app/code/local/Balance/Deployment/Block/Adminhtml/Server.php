<?php
class Balance_Deployment_Block_Adminhtml_Server
	extends Mage_Core_Block_Template
{

	/**
	 * if the template can be shown
	 * @return boolean
	 */
	public function canShow()
	{
		$helper = Mage::helper('deployment');
		return $helper->canGit() || $helper->canVarnish() || $helper->canMemcached() || $helper->canApc();
	}
	
	/**
	 * get all available services
	 */
	public function getServices()
	{
		return Mage::registry('services');
	}
}