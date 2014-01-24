<?php 
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meaigeeteam.com <nick@meaigeeteam.com>
 * @copyright Copyright (C) 2010 - 2012 Meigeeteam
 *
 */
class Meigee_ThemeOptionsLookbook_Model_Currencyswitcher
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'currency_select', 'label'=>Mage::helper('ThemeOptionsLookbook')->__('Select Box')),
            array('value'=>'currency_images', 'label'=>Mage::helper('ThemeOptionsLookbook')->__('Flags'))          
        );
    }

}