<?php
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meaigeeteam.com <nick@meaigeeteam.com>
 * @copyright Copyright (C) 2010 - 2012 Meigeeteam
 *
 */
class Meigee_ThemeOptionsLookbook_Controller_Observer
{
	//Event: adminhtml_controller_action_predispatch_start
	public function overrideTheme()
	{
		Mage::getDesign()->setArea('adminhtml')
			->setTheme('lookbook');
	}
}
