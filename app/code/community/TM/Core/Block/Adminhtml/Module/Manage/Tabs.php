<?php

class TM_Core_Block_Adminhtml_Module_Manage_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('module_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('tmcore')->__('Manage Module'));
    }
}
