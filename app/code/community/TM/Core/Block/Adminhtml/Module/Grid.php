<?php

class TM_Core_Block_Adminhtml_Module_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('moduleGrid');
        $this->setDefaultSort('code');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setVarNameFilter('module_filter');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('tmcore/module_AdminGridCollection');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('code', array(
            'header' => Mage::helper('tmcore')->__('Code'),
            'align'  => 'left',
            'index'  => 'code'
        ));

        $this->addColumn('version', array(
            'header' => Mage::helper('tmcore')->__('Local Version'),
            'align'  => 'right',
            'index'  => 'version',
            'width'  => '80px'
        ));

        $this->addColumn('latest_version', array(
            'header' => Mage::helper('tmcore')->__('Latest Version'),
            'align'  => 'right',
            'index'  => 'latest_version',
            'width'  => '80px'
        ));

        $this->addColumn('version_status', array(
            'header'   => Mage::helper('tmcore')->__('Version Status'),
            'width'    => '60px',
            'index'    => 'version_status',
            'renderer' => 'tmcore/adminhtml_module_grid_renderer_versionStatus',
            'type'     => 'options',
            'options'  => Mage::getModel('tmcore/module')->getVersionStatuses()
        ));

        $this->addColumn('actions', array(
            'header'   => Mage::helper('adminnotification')->__('Actions'),
            'width'    => '200px',
            'filter'   => false,
            'sortable' => false,
            'renderer' => 'tmcore/adminhtml_module_grid_renderer_actions'
        ));

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    public function getRowUrl($row)
    {
        if ($row->hasUpgradesDir() || $row->getIdentityKeyLink()) {
            return $this->getUrl('*/*/manage', array('id' => $row->getId()));
        }
        return false;
    }
}
