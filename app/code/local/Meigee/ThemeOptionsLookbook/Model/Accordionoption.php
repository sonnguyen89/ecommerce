<?php
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meaigeeteam.com <nick@meaigeeteam.com>
 * @copyright Copyright (C) 2010 - 2012 Meigeeteam
 *
 */
class Meigee_ThemeOptionsLookbook_Model_Accordionoption
{
    public function toOptionArray()
    {
        return array(
            array('value'=>0, 'label'=>Mage::helper('ThemeOptionsLookbook')->__('Simple List')),
            array('value'=>1, 'label'=>Mage::helper('ThemeOptionsLookbook')->__('Accordion'))          
        );
    }

}