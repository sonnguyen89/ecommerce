<?php

class TM_Core_Block_Adminhtml_Module extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_module';
        $this->_blockGroup = 'tmcore';
        $this->_headerText = Mage::helper('tmcore')->__('Modules');
        parent::__construct();
        $this->_removeButton('add');
    }
}
