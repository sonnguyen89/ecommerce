<?php
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meaigeeteam.com <nick@meaigeeteam.com>
 * @copyright Copyright (C) 2010 - 2012 Meigeeteam
 *
 */
class Meigee_ThemeOptionsLookbook_Block_Bestsellers extends Mage_Catalog_Block_Product_Abstract
    {
      public function getBestsellers() {

          $this->products = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToSelect(array('name', 'price', 'small_image', 'short_description'), 'inner')
            ->addAttributeToSelect('special_price')
            ->addAttributeToSelect('status')
            ->addCategoryFilter(Mage::getModel('catalog/category')->load($this->catId()));
        return $this->products;
    }
}
?>
