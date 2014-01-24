<?php class Meigee_MeigeewidgetsLookbook_Model_Boolean
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'1', 'label'=>Mage::helper('meigeewidgetslookbook')->__('True')),
            array('value'=>'0', 'label'=>Mage::helper('meigeewidgetslookbook')->__('False'))
        );
    }

}