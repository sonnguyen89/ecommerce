<?php 
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meaigeeteam.com <nick@meaigeeteam.com>
 * @copyright Copyright (C) 2010 - 2012 Meigeeteam
 *
 */
class Meigee_MeigeewidgetsLookbook_Model_Number
{
    public function toOptionArray()
    {
    	$randId = rand (0, 9999);
        return array(
            array('value'=>$randId, 'label'=>$randId)
        );
    }

}