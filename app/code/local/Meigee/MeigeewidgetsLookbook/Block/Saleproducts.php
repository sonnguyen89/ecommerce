<?php
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meaigeeteam.com <nick@meaigeeteam.com>
 * @copyright Copyright (C) 2010 - 2012 Meigeeteam
 *
 */
class Meigee_MeigeewidgetsLookbook_Block_Saleproducts
extends Mage_Catalog_Block_Product_Abstract
implements Mage_Widget_Block_Interface
{
    protected $products;

    protected function _construct() {
        parent::_construct();
    }

    protected function catId()
    {
        $cat = explode("/", $this->getData('featured_category'));     
		return $cat[0];
    }
    public function catName () {
        return Mage::getModel('catalog/category')->load($this->catId());
    }

    public function productsAmount () {
        return $this->getData('products_amount');
    }

    public function getColumnCount () {
        return $this->getData('column_count');
    }

    public function getMyCollection () {
        $todayStartOfDayDate  = Mage::app()->getLocale()->date()
            ->setTime('00:00:00')
            ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

        $todayEndOfDayDate  = Mage::app()->getLocale()->date()
            ->setTime('23:59:59')
            ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

        $collection = Mage::getResourceModel('catalog/product_collection');
        $collection->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());


        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->addAttributeToFilter('special_from_date', array('or'=> array(
                0 => array('date' => true, 'to' => $todayEndOfDayDate),
                1 => array('is' => new Zend_Db_Expr('null')))
            ), 'left')
            ->addAttributeToFilter('special_to_date', array('or'=> array(
                0 => array('date' => true, 'from' => $todayStartOfDayDate),
                1 => array('is' => new Zend_Db_Expr('null')))
            ), 'left')
            ->addAttributeToFilter(
                array(
                    array('attribute' => 'special_from_date', 'is'=>new Zend_Db_Expr('not null')),
                    array('attribute' => 'special_to_date', 'is'=>new Zend_Db_Expr('not null'))
                    )
              )
            ->addAttributeToSort('special_from_date', 'desc')
            ->setPageSize($this->getProductsCount())
            ->setCurPage(1);


        return $collection;
    }
	
	public function sliderGridSelection() {
		switch ($this->getData('visible_products')){
			case '1':
				return 'grid_2';
			break;
			case '2':
				return 'grid_4';
			break;
			case '3':
				return 'grid_6';
			break;
			case '4':
				return 'grid_8';
			break;
			case '5':
				return 'grid_10';
			break;
			case '6':
				return 'grid_12';
			break;
		}
	}
	
	
    public function getSliderOptions () {
        
         if ($this->getData('template') == 'meigee/meigeewidgetslookbook/slider.phtml' and $this->getData('autoSlide') == 1) {
            $options =
            ', autoSlide: 1, '
            . 'autoSlideTimer:'.$this->getData('autoSlideTimer').','
            .'autoSlideTransTimer:'.$this->getData('autoSlideTransTimer');
			return $options;
		}
    }

    public function getWidgetId () {
        return $this->getData("widget_id");
    }

}