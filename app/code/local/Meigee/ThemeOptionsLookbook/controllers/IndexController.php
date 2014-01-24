<?php
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meaigeeteam.com <nick@meaigeeteam.com>
 * @copyright Copyright (C) 2010 - 2012 Meigeeteam
 *
 */
class Meigee_ThemeOptionsLookbook_IndexController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
     $this->loadLayout(array('default'));

		$this->_addContent($this->getLayout()->createBlock('themeoptionslookbook/adminhtml_customization_edit'));
        $block = $this->getLayout()->createBlock(
			'Mage_Core_Block_Template',
			'custom_colors_block',
			array('template' => 'meigee/colors_page.phtml')
		);
		$this->getLayout()->getBlock('content')->append($block);
        $this->renderLayout();
    }
}