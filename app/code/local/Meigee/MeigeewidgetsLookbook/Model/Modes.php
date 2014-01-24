<?php 
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meaigeeteam.com <nick@meaigeeteam.com>
 * @copyright Copyright (C) 2010 - 2012 Meigeeteam
 *
 */
class Meigee_MeigeewidgetsLookbook_Model_Modes
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'horizontal', 'label'=>Mage::helper('meigeewidgetslookbook')->__('Horizontal')),
            array('value'=>'vertical', 'label'=>Mage::helper('meigeewidgetslookbook')->__('Vertical')),
            array('value'=>'fade', 'label'=>Mage::helper('meigeewidgetslookbook')->__('Fade'))
        );
    }

}