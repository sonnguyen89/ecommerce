<?php 
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meaigeeteam.com <nick@meaigeeteam.com>
 * @copyright Copyright (C) 2010 - 2012 Meigeeteam
 *
 */
class Meigee_ThemeOptionsLookbook_Helper_Menu extends Mage_Core_Helper_Abstract
{
 
	public function getMenuStaticBlocks () {

		$staticList = Mage::getStoreConfig('meigee_lookbook_general/menu/menu_static');
		$staticBlocks = explode(",", str_replace(" ", "", $staticList));
	    $staticContent = array();
	    $html = '';

	    foreach ($staticBlocks as $block) {
	    	$tempArray = array(
	    				Mage::getModel('cms/block')->load($block)->getTitle() => Mage::app()->getLayout()->createBlock('cms/block')->setBlockId($block)->toHtml()
	    				);
	    	array_push($staticContent, $tempArray);
	    }
	    foreach ($staticContent as $staticBlocks) {
	    	foreach ($staticBlocks as $blockTitle=>$blockBody) {
	    		if (!empty($blockBody)):
		        	$html .= '
		            <li class="level0 level-top parent custom-block">
		                <a href="#" class="level-top">
		                    <span>'.$blockTitle.'</span>
		                </a>
		                <div class="static-wrapper">'.$blockBody.'</div>
		            </li>';
	            endif;
	    	}
	    }
	 	return $html;
	}

}