<?php

class Meigee_ThemeOptionsLookbook_Block_Adminhtml_Activation_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'themeoptionslookbook';
        $this->_controller = 'adminhtml_activation';
         
        $this->_updateButton('save', 'label', Mage::helper('ThemeOptionsLookbook')->__('Activate'));
    }
 
    public function getHeaderText()
    {
        return Mage::helper('ThemeOptionsLookbook')->__('Theme Activation');
    }


    


}