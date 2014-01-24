<?php 
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meaigeeteam.com <nick@meaigeeteam.com>
 * @copyright Copyright (C) 2010 - 2012 Meigeeteam
 *
 */
class Meigee_ThemeOptionsLookbook_Helper_Data extends Mage_Core_Helper_Abstract
{
 public function getThemeOptionsLookbook ($themeOption) {
 	switch ($themeOption) {
		case 'mediaurl':
		    return Mage::getBaseUrl('media') . 'images/';
		break;
		case 'user-scalable':
			 if(Mage::getStoreConfig('meigee_lookbook_general/layout/responsiveness') == 1){
				return '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />';
			 }
		break;
 		case 'appearance':
 			return Mage::getStoreConfig('meigee_lookbook_general/appearance');
 		break;
 		case 'patern':
 			return Mage::getStoreConfig('meigee_lookbook_general/appearance/patern');
 		break;
 		case 'custompatern':
 			return Mage::getStoreConfig('meigee_lookbook_general/appearance/custompatern');
 		break;
 		case 'responsiveness':
 			return Mage::getStoreConfig('meigee_lookbook_general/layout/responsiveness');
 		break;
 		case 'sidebar':
 			return Mage::getStoreConfig('meigee_lookbook_general/layout/sidebar');
 		break; 
 		case 'grid':
 			return Mage::getStoreConfig('meigee_lookbook_general/layout/grid');
 		break; 
 		case 'cartpage':
 			return Mage::getStoreConfig('meigee_lookbook_general/layout/cartpage');
 		break; 
 		case 'lang_switcher':
 			return Mage::getStoreConfig('meigee_lookbook_general/lang_switcher');
 		break;
 		case 'curr_switcher':
 			return Mage::getStoreConfig('meigee_lookbook_general/curr_switcher');
 		break;
 		case 'customlogo':
 			return Mage::getStoreConfig('meigee_lookbook_general/customlogo');
 		break;
 		case 'menu':
 			return Mage::getStoreConfig('meigee_lookbook_general/menu');
 		break;
 		case 'sociallinks':
 			return Mage::getStoreConfig('meigee_lookbook_general/sociallinks');
 		break;
 		case 'fancybox':
 			return Mage::getStoreConfig('meigee_lookbook_general/fancybox/fancybox_status');
 		break;
 		case 'rollover':
 			return Mage::getStoreConfig('meigee_lookbook_general/rollover/rollover_status');
 		break; 		
 		case 'labels':
 			return Mage::getStoreConfig('meigee_lookbook_general/productlabels');
 		break;
	        case 'productpage_pagelayout':
 			return Mage::getStoreConfig('meigee_lookbook_productpage/productpage/pagelayout');
 		break;
 		case 'productpage_prevnext':
 			return Mage::getStoreConfig('meigee_lookbook_productpage/productpage/prevnext');
 		break;
 		case 'productpage_moreviews':
 			return Mage::getStoreConfig('meigee_lookbook_productpage/productpage/moreviews');
 		break;
 		case 'productpage_collateral':
 			return Mage::getStoreConfig('meigee_lookbook_productpage/productpage/collateral');
 		break;
 		case 'productpage_collateral_upsell':
 			return Mage::getStoreConfig('meigee_lookbook_productpage/productpage/collateral_upsell');
 		break;
 		case 'productpage_collateral_related':
 			return Mage::getStoreConfig('meigee_lookbook_productpage/productpage/collateral_related');
 		break;
 		case 'block_categories':
 			return Mage::getStoreConfig('meigee_lookbook_sidebar/block_categories');
 		break;
 		case 'block_shop_by':
 			return Mage::getStoreConfig('meigee_lookbook_sidebar/block_shop_by');
 		break;
 		case 'block_wishlist':
 			return Mage::getStoreConfig('meigee_lookbook_sidebar/block_wishlist');
 		break;
 		case 'headerslider':
 			return Mage::getStoreConfig('meigee_lookbook_headerslider/coin');
 		break;
		case 'ajax_general':
 			return Mage::getStoreConfig('ajax/general/enabled');
 		break;
		case 'ajax_toolbar':
 			return Mage::getStoreConfig('ajax/ajaxtoolbar/enabled');
 		break;
		case 'ajax_wishlistcompare':
 			return Mage::getStoreConfig('ajax/wishlistcompare/enabled');
 		break;
 	}
 }

 public function getProductLabels ($product) {
 	$html = '';
 	if (Mage::getStoreConfig('meigee_lookbook_general/productlabels/labelnew')):
		$from = $product->getNewsFromDate();
		$to = new Zend_Date($product->getNewsToDate());
		$now = new Zend_Date(Mage::getModel('core/date')->timestamp(time()));
		if (isset($from) && $to->isLater($now)) $html .= '<span class="label-new">' . $this->__('New') . '</span>';
	endif;
	if (Mage::getStoreConfig('meigee_lookbook_general/productlabels/labelonsale') and $this->isOnSale($product)):
	 	$html .= '<span class="label-sale">' . $this->__('On Sale') . '</span>';
	endif;
	return $html;
 }

 public function isNew($product)
	{
		return $this->_nowIsBetween($product->getData('news_from_date'), $product->getData('news_to_date'));
	}
public function isOnSale($product)
{
	$specialPrice = number_format($product->getFinalPrice(), 2);
	$regularPrice = number_format($product->getPrice(), 2);
	
	if ($specialPrice != $regularPrice)
		return true;
	else
		return false;
}


 public function getSocialLinks () {
 	$links = $this->getThemeOptionsLookbook ('sociallinks');
 	echo '<ul class="social-links">';
 	foreach ($links as $key=>$link) {
 		if ($link !== '')
 		echo '<li><a class="'.$key.'" href="'.$link.'"></a></li>';
 	}
 	echo '</ul>';
 }

 public function getHoverImage ($_product, $size, $size2x) {
 	if ($this->getThemeOptionsLookbook('rollover' ) == true):
	 	$imgcount = Mage::getModel('catalog/product')->load($_product->getId())->getMediaGalleryImages()->count();
	 	if ($imgcount>0):
	 		$_gallery = Mage::getModel('catalog/product') -> load($_product -> getId()) -> getMediaGalleryImages();
		 	foreach ($_gallery as $_image ):
		        if ($_image->getLabel() == 'hover'):
		        	echo '<span class="hover-image"><img src=' . Mage::helper('catalog/image') -> init($_product, 'thumbnail', $_image -> getFile())->constrainOnly(TRUE)->keepAspectRatio(TRUE)->keepFrame(FALSE) -> resize($size, null) . ' data-srcX2=' . Mage::helper('catalog/image') -> init($_product, 'thumbnail', $_image -> getFile())->constrainOnly(TRUE)->keepAspectRatio(TRUE)->keepFrame(FALSE) -> resize($size2x, null) . ' /></span>';
		 		break;
		    	endif;
	        endforeach;
		endif;
	endif;
 }

 public function prevnext ($product) {
 	if ($this->getThemeOptionsLookbook('productpage_prevnext') == 'prevnext'):
	 	$_helper = Mage::helper('catalog/output');
		$_product = $product->getProduct();
		$prev_url = $next_url = $url = $product->getProductUrl();
		 
		if (Mage::helper('catalog/data')->getCategory()) {
		$category = Mage::helper('catalog/data')->getCategory();
		} else {
		$_ccats = Mage::helper('catalog/data')->getProduct()->getCategoryIds();
		$category = Mage::getModel('catalog/category')->load($_ccats[0]);
		}
		 
		$children = $category->getProductCollection();
		$_count = is_array($children) ? count($children) : $children->count();
		if ($_count) {
		foreach ($children as $product) {
		$plist[] = $product->getId();
		}
		 
		/**
		* Determine the previous/next link and link to current category
		*/
		$current_pid  = Mage::helper('catalog/data')->getProduct()->getId();
		$curpos   = array_search($current_pid, $plist);
		// get link for prev product
		$previd   = isset($plist[$curpos+1])? $plist[$curpos+1] : $current_pid;
		$product  = Mage::getModel('catalog/product')->load($previd);
		$prevpos  = $curpos;
		while (!$product->isVisibleInCatalog()) {
		$prevpos += 1;
		$nextid   = isset($plist[$prevpos])? $plist[$prevpos] : $current_pid;
		$product  = Mage::getModel('catalog/product')->load($nextid);
		}
		$prev_url = $product->getProductUrl();
		// get link for next product
		$nextid   = isset($plist[$curpos-1])? $plist[$curpos-1] : $current_pid;
		$product  = Mage::getModel('catalog/product')->load($nextid);
		$nextpos  = $curpos;
		while (!$product->isVisibleInCatalog()) {
		$nextpos -= 1;
		$nextid   = isset($plist[$nextpos])? $plist[$nextpos] : $current_pid;
		$product  = Mage::getModel('catalog/product')->load($nextid);
		}
		$next_url = $product->getProductUrl();
		}
		
	    if ($url <> $prev_url):
	        $html = '<a class="product-next" title="' . $this->__('Next Product') . '" href="' . $prev_url . '"><i class="icon-angle-right"></i></a>';
	    endif;
	    if ($url <> $next_url): 
			$html .= '<a class="product-prev" title="' . $this->__('Previous Product') . '" href="' . $next_url . '"><i class="icon-angle-left"></i></a>';
	    endif;

	    return $html;
	else: 
		return false;
	endif;
 }

	public function isActive($attribute, $value){

	    $col = Mage::getModel('cms/block')->getCollection();
	    $col->addFieldToFilter($attribute, $value);
	    $item = $col->getFirstItem();
	    $id = $item->getData('is_active');

	    if($id == 1){
	        return true;
	    }else{
	        return false;
	    }

	}

	public function switchGrid() {
		if ((int)$this->getThemeOptionsLookbook('responsiveness') !== 1):
			return 'css/grid_' . $this->getThemeOptionsLookbook('responsiveness') . '.css';
		endif;
		return 'css/grid_responsive.css';
	}
	
	public function fancySwitcher(){
		if ((int)$this->getThemeOptionsLookbook('fancybox') == 1 or (int)$this->getThemeOptionsLookbook('ajax_general') == 1 or (int)$this->getThemeOptionsLookbook('ajax_toolbar') == 1 or (int)$this->getThemeOptionsLookbook('ajax_wishlistcompare') == 1):
			return 'css/fancybox.css';
		endif;
	}
	
	public function fancySwitcherJs(){
		if ((int)$this->getThemeOptionsLookbook('fancybox') == 1 or (int)$this->getThemeOptionsLookbook('ajax_general') == 1 or (int)$this->getThemeOptionsLookbook('ajax_toolbar') == 1 or (int)$this->getThemeOptionsLookbook('ajax_wishlistcompare') == 1):
			return 'js/jquery.fancybox.pack.js';
		endif;
	}

	public function HexToRGB($hex) {
	    $hex = str_replace("#", "", $hex);
	    $color = '';
	    
	    if(strlen($hex) == 3) {
		    $color .= hexdec(substr($hex, 0, 1) . $r) . ',';
		    $color .= hexdec(substr($hex, 1, 1) . $g) . ',';
		    $color .= hexdec(substr($hex, 2, 1) . $b);
	    }
	    else if(strlen($hex) == 6) {
		    $color .= hexdec(substr($hex, 0, 2)) . ',';
		    $color .= hexdec(substr($hex, 2, 2)) . ',';
		    $color .= hexdec(substr($hex, 4, 2));
	    }
	    
	    return $color;
    }
}
?>