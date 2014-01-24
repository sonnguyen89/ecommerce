<?php 
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meaigeeteam.com <nick@meaigeeteam.com>
 * @copyright Copyright (C) 2010 - 2012 Meigeeteam
 *
 */
class Meigee_ThemeOptionsLookbook_Model_Cartpage
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'cart_standard', 'label'=>Mage::helper('ThemeOptionsLookbook')->__('Standard')),
            array('value'=>'cart_accordion', 'label'=>Mage::helper('ThemeOptionsLookbook')->__('Accordion'))          
        );
    }

}