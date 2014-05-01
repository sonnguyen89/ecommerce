<?php

class TM_Core_Block_Adminhtml_Module_Grid_Renderer_VersionStatus
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * Renders grid column
     *
     * @param   Varien_Object $row
     * @return  string
     */
    public function render(Varien_Object $row)
    {
        /**
         * @var TM_Core_Model_Module
         */
        $module = Mage::getSingleton('tmcore/module');
        $status = $row->getData($this->getColumn()->getIndex());

        if (null === $status) {
            return '';
        }

        $title = '';
        switch ($status) {
            case TM_Core_Model_Module::VERSION_UPDATED:
                $class = 'notice';
                break;
            case TM_Core_Model_Module::VERSION_OUTDATED:
                $class = 'minor';
                $title = Mage::helper('tmcore')->__('Upgrades are not installed');
                break;
            case TM_Core_Model_Module::VERSION_DEPRECATED:
                $class = 'major';
                $title = Mage::helper('tmcore')->__('New version is available');
                break;
        }
        $value = $module->getVersionStatuses($status);

        return '<span class="grid-severity-' . $class . '" title="' . $title . '"><span>' . $value . '</span></span>';
    }
}
