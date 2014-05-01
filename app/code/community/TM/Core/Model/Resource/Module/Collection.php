<?php

class TM_Core_Model_Resource_Module_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('tmcore/module');
    }
}
