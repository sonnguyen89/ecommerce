<?php

class TM_Core_Model_Resource_Module_AdminGridCollection extends TM_Core_Model_Resource_Module_MergedCollection
{
    public function getModulesFromConfigNodes()
    {
        $modules = Mage::getConfig()->getNode('modules')->children();
        $result  = array();
        foreach ($modules as $code => $values) {
            if ($values->tm_hidden) {
                continue;
            }
            $result[$code] = $values;
        }
        return $result;
    }
}
