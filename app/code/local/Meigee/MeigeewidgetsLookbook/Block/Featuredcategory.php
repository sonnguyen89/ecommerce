<?php
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meaigeeteam.com <nick@meaigeeteam.com>
 * @copyright Copyright (C) 2010 - 2012 Meigeeteam
 *
 */
class Meigee_MeigeewidgetsLookbook_Block_Featuredcategory
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
        return $cat[1];
    }
    public function catName () {
        return Mage::getModel('catalog/category')->load($this->catId());
    }

    public function productsAmount () {
        return $this->getData('products_amount');
    }

    /*public function getColumnCount () {
        return $this->getData('column_count');
    }*/

    public function getMyCollection () {
		$this->products = Mage::getResourceModel('catalog/product_collection')
			->addAttributeToSelect(array('name', 'price', 'small_image', 'short_description'), 'inner')
			->addAttributeToSelect('news_from_date')
			->addAttributeToSelect('news_to_date')
			->addAttributeToSelect('special_price')
			->addAttributeToSelect('status')
			->addAttributeToFilter('visibility', array(2, 3, 4))
			->addAttributeToSelect('*')
			->addCategoryFilter(Mage::getModel('catalog/category')->load($this->catId()));
		return $this->products;
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