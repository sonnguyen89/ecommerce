<?php 
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meaigeeteam.com <nick@meaigeeteam.com>
 * @copyright Copyright (C) 2010 - 2012 Meigeeteam
 *
 */
class Meigee_ThemeOptionsLookbook_Model_Layout
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'sidebar_left', 'label'=>Mage::helper('ThemeOptionsLookbook')->__('Left')),
            array('value'=>'sidebar_right', 'label'=>Mage::helper('ThemeOptionsLookbook')->__('Right'))          
        );
    }

}