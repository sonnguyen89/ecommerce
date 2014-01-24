<?php 
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meaigeeteam.com <nick@meaigeeteam.com>
 * @copyright Copyright (C) 2010 - 2012 Meigeeteam
 *
 */
class Meigee_ThemeOptionsLookbook_Model_Menu
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'menu', 'label'=>Mage::helper('ThemeOptionsLookbook')->__('Standard')),
            array('value'=>'menu_wide', 'label'=>Mage::helper('ThemeOptionsLookbook')->__('Wide'))          
        );
    }

}