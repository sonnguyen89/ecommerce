<?php
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meaigeeteam.com <nick@meaigeeteam.com>
 * @copyright Copyright (C) 2010 - 2012 Meigeeteam
 *
 */
class Meigee_ThemeOptionsLookbook_ActivationController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
     $this->loadLayout(array('default'));

         $this->_addLeft($this->getLayout()
                ->createBlock('core/text')
                ->setText('
                    <h5>Predefined pages:</h5>
                    <ul>
                        <li>home</li>
                    </ul><br />
                    <h5>Predefined static blocks:</h5>
                    <ul>
                        <li>lookbook_custom_footer</li>
                        <li>lookbook_listing_likes</li>
						<li>lookbook_product_likes</li>
						<li>lookbook_social_links</li>
						<li>lookbook_slider_slide_1</li>
						<li>lookbook_slider_slide_2</li>
						<li>lookbook_slider_slide_3</li>
                    </ul><br />
                    <strong style="color:red;">To get more info regarding these blocks please read documentation that comes with this theme.</strong>'));
		$this->_addContent($this->getLayout()->createBlock('themeoptionslookbook/adminhtml_activation_edit'));
        $block = $this->getLayout()->createBlock('core/text')->setText('<strong>Note:</strong> Please make sure you have at least 8 products marked as new to display homepage widgets correctly.');
        $this->getLayout()->getBlock('content')->append($block);
		$this->renderLayout();
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {
        	
        $stores = $this->getRequest()->getParam('stores', array(0));
        $setup_pages = $this->getRequest()->getParam('setup_pages', 0);
        $setup_blocks = $this->getRequest()->getParam('setup_blocks', 0);

        try {

            foreach ($stores as $store) {
                $scope = ($store ? 'stores' : 'default');

                Mage::getConfig()->saveConfig('design/package/name', 'lookbook', $scope, $store);
                Mage::getConfig()->saveConfig('design/header/logo_src', 'images/logo.png', $scope, $store);
                Mage::getConfig()->saveConfig('design/footer/copyright', 'Meigee &copy; 2013 <a href="http://meigeeteam.com" >Premium Magento Themes</a>', $scope, $store);
				/*Mage::getConfig()->saveConfig('meigee_lookbook_headerslider/coin/slides', 'lookbook', $scope, $store);*/
            }

            if ($setup_pages) {
                Mage::getModel('ThemeOptionsLookbook/activation')->setupPages();
            }

            if ($setup_blocks) {
                Mage::getModel('ThemeOptionsLookbook/activation')->setupBlocks();
            }

            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('ThemeOptionsLookbook')->__('Lookbook Theme has been activated.<br/>
                    Please clear all the cache and then logout and login again to see the theme options block
                '));
        }
        catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ThemeOptionsLookbook')->__('An error occurred while activating theme. '.$e->getMessage()));
        }

        $this->getResponse()->setRedirect($this->getUrl("*/*/"));
        }
    }
}