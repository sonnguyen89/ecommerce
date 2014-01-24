<?php 
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meaigeeteam.com <nick@meaigeeteam.com>
 * @copyright Copyright (C) 2010 - 2012 Meigeeteam
 *
 */
class Meigee_ThemeOptionsLookbook_Model_Cssgenerate extends Mage_Core_Model_Config_Data
{
    private $baseColors;
    private $headerColors;
    private $dirPath;
    private $filePath;

    private function setParams () {
        $this->baseColors = Mage::getStoreConfig('meigee_lookbook_design/base');
		$this->headerColors = Mage::getStoreConfig('meigee_lookbook_design/header');
    }

    private function setLocation () {
        $this->dirPath = Mage::getBaseDir('skin') . '/frontend/lookbook/default/css/';
        $this->filePath = $this->dirPath . 'skin.css';
    }

    public function _afterLoad()
    {

        parent::_afterLoad();

        $this->setParams();


$css = "/**
 * These styles is generated automaticaly. Please Do no edit it directly all changes will be lost.
 */";

$css .= '/*======Site Bg=======*/
body,
.header-slider-holder .loader{background-color:#' . $this->baseColors['sitebg'] . ';}


/*======Skin Color #1=======*/
.cart-price .price,
.catalog-product-view .box-reviews .form-add h3 span,
header#header .top-cart .block-content .price,
.price-box .price{color:#' . $this->baseColors['maincolor'] . ';}

aside.sidebar section.block-layered-nav #slider-range.ui-slider,
#toTop,
.slider-container .prev:hover,
.slider-container .next:hover,
header#header .nav-container > ul .menu-item-button.active,
.block-account li strong i,
.cart .crosssell li.item .add-to-links li a:hover,
.block-related .block-content .block-subtitle a:hover,
.block-related .prev:hover,
.block-related .next:hover,
.product-view .product-shop .availability,
.product-view .product-prev:hover,
.product-view .product-next:hover,
.more-views .prev:hover,
.more-views .next:hover,
.products-list .availability,
.pager .view-mode strong.grid,
.pager .view-mode strong.list,
.pager .view-mode a.grid:hover,
.pager .view-mode a.list:hover,
.block-wishlist .prev:hover,
.block-wishlist .next:hover,
.block-compare li.item .btn-remove:hover,
.block-layered-nav dl#layered_navigation_accordion dt.closed .btn-nav,
#categories-accordion li.level-top .btn-cat.closed i.icon-minus,
header#header .top-cart .block-content .actions a,
#onepagecheckout_forgotbox.op_login_area button.button > span,
#onepagecheckout_loginbox.op_login_area button.button > span,
#checkout-review-submit #review-buttons-container button.btn-checkout > span,
button.button > span{background-color:#' . $this->baseColors['maincolor'] . ';}

/*======Skin Color #2=======*/
header#header .menu-button:before,
header#header .menu-button:after,
.product-view .price-button-box.active .price-box .price,
.product-view .product-shop .price-button-box:hover .price-box .price,

.price-button-box.active .price-block .price-details,
.price-button-box:hover .price-block .price-details,
.price-button-box.active .price-block > label,
.price-button-box:hover .price-block > label,
.price-button-box.active .price-block .price,
.price-button-box:hover .price-block .price,
.product-options-bottom .price-button-box:hover .price-box .price,
.product-options-bottom .price-button-box.active .price-box .price-label,
.product-options-bottom .price-button-box:hover .price-box .price-label,
.price-button-box.active .price-box.map-info a,
.price-button-box:hover .price-box.map-info a,
.price-button-box button.button span span,
.products-grid .btn-quick-view span span,
.products-list .btn-quick-view span span,
#nav > li.active:before,
#nav > li.active:after,
#nav li.active a{color:#' . $this->baseColors['secondcolor'] . ';}

aside.sidebar section.block-layered-nav #slider-range.ui-slider .ui-slider-handle,
#login-holder .close-button,
#toTop:hover,
aside.sidebar section header .sidebar-icon,
.opc .step-title .number,
.my-wishlist .buttons-set .btn-share > span,
.cart-table button.btn-continue > span,
.cart-table .actions .img-qty,
.catalog-product-view .box-reviews .data-table thead,
.section.tabs .tabs li.current,
.meigee-tabs:before,
.meigee-tabs a,
.product-view .product-shop .email-friend a:hover,
.product-view .add-to-links li a:hover,
.map-popup-heading,
.price-button-box:after,
.price-button-box .detailed-price-box,
.products-list .add-to-links li a:hover,
.products-grid .btn-quick-view i.circle-left,
.products-list .btn-quick-view i.circle-left,
.products-grid .btn-quick-view i.circle-right,
.products-list .btn-quick-view i.circle-right,
.toolbar-bottom .pager .pages ol li.current,
.toolbar-bottom .pager .pages ol li a:hover,
aside.sidebar .actions a,
.social-block h2,
#footer .title-box i,
.products-grid .top-box,
.products-list .top-box,
header#header .top-cart .block-content button.button > span,
header#header .top-cart .img-container .cart-price-qt,
header#header .top-cart .block-title .cart-button .button-arrow,
header#header .top-cart .block-title .cart-button{background-color:#' . $this->baseColors['secondcolor'] . ';}

.products-grid .top-box,
.products-list .top-box{
	background-color:rgba('.MAGE::helper('ThemeOptionsLookbook')->HexToRGB($this->baseColors['secondcolor']).', 0.98);
}


header#header .menu-button span:after,
.price-button-box,
.products-grid .btn-quick-view span span,
.products-list .btn-quick-view span span,
#nav > li.active > a:after{
	border-color: #' . $this->baseColors['secondcolor'] . ';
}';

    	$this->saveData($css);
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('ThemeOptionsLookbook')->__("CSS file with custom styles has been created"));

        return true;
    }

    private function saveData($data)
    {
        $this->setLocation ();

        try {
	        /*$fh = fopen($file, 'w');
	       	fwrite($fh, $data);
	        fclose($fh);*/

            $fh = new Varien_Io_File(); 
            $fh->setAllowCreateFolders(true); 
            $fh->open(array('path' => $this->dirPath));
            $fh->streamOpen($this->filePath, 'w+'); 
            $fh->streamLock(true); 
            $fh->streamWrite($data); 
            $fh->streamUnlock(); 
            $fh->streamClose(); 
    	}
    	catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ThemeOptionsLookbook')->__('Failed creation custom css rules. '.$e->getMessage()));
        }
    }

}