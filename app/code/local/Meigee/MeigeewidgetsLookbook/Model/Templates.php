<?php 
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meaigeeteam.com <nick@meaigeeteam.com>
 * @copyright Copyright (C) 2010 - 2012 Meigeeteam
 *
 */
class Meigee_MeigeewidgetsLookbook_Model_Templates
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'meigee/meigeewidgetslookbook/grid.phtml', 'label'=>'Grid'),
            array('value'=>'meigee/meigeewidgetslookbook/list.phtml', 'label'=>'List'),
            array('value'=>'meigee/meigeewidgetslookbook/slider.phtml', 'label'=>'Slider')
        );
    }

}