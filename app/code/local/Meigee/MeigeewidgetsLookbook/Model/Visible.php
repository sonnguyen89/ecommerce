<?php class Meigee_MeigeewidgetsLookbook_Model_Visible
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'1', 'label'=>Mage::helper('meigeewidgetslookbook')->__('1')),
            array('value'=>'2', 'label'=>Mage::helper('meigeewidgetslookbook')->__('2')),
            array('value'=>'3', 'label'=>Mage::helper('meigeewidgetslookbook')->__('3')),
			array('value'=>'4', 'label'=>Mage::helper('meigeewidgetslookbook')->__('4')),
			array('value'=>'5', 'label'=>Mage::helper('meigeewidgetslookbook')->__('5')),
            array('value'=>'6', 'label'=>Mage::helper('meigeewidgetslookbook')->__('6'))
        );
    }

}