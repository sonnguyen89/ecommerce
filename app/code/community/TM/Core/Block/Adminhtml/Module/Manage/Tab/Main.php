<?php

class TM_Core_Block_Adminhtml_Module_Manage_Tab_Main
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected function _prepareForm()
    {
        $model = Mage::registry('tmcore_module');

        $form = new Varien_Data_Form(
            array('id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post')
        );

        $form->setHtmlIdPrefix('module_');

        $stores = Mage::getSingleton('adminhtml/system_store')->getStoreOptionHash(true);
        if (isset($stores[0])) {
            $stores[0] = Mage::helper('adminhtml')->__('All Store Views');
        }

        if ($model->getDataVersion() && ($upgrades = $model->getUpgradesToRun())) {
            $fieldset = $form->addFieldset('upgrade_fieldset', array(
                'legend' => Mage::helper('tmcore')->__('Upgrade Information'),
                'class'  => 'fieldset-wide'
            ));
            $fieldset->addField('skip_upgrade', 'checkbox', array(
                'name'  => 'skip_upgrade',
                'label' => Mage::helper('tmcore')->__('Activate this checkbox, if you want to skip the upgrade operations'),
                'title' => Mage::helper('tmcore')->__('Activate this checkbox, if you want to skip the upgrade operations'),
                'value' => 1
            ));

            $label = Mage::helper('tmcore')->__(
                'Module data will be upgraded from %s to %s at the following stores',
                $model->getDataVersion(),
                $upgrades[count($upgrades) - 1]
            );
            $fieldset->addField('installed_stores', 'textarea', array(
                'label'    => $label,
                'title'    => $label,
                'value'    => implode("\n", array_intersect_key($stores, array_flip($model->getStores()))),
                'readonly' => 1
            ));
        }

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => Mage::helper('tmcore')->__('Install and Reinstall Information'),
            'class'  => 'fieldset-wide'
        ));

        $fieldset->addField('code', 'hidden', array(
            'name' => 'id'
        ));

        if ($model->isValidationRequired()) {
            $note = '';
            if ($model->getRemote()) {
                $link = $model->getRemote()->getIdentityKeyLink();
                $note = Mage::helper('tmcore')->__(
                    'Get your identity key at <a href="%s" title="%s" target="_blank">%s</a>',
                    $link,
                    $link,
                    $link
                );
            }
            $fieldset->addField('identity_key', 'textarea', array(
                'name'  => 'identity_key',
                'required' => true,
                'label' => Mage::helper('tmcore')->__('Identity Key'),
                'title' => Mage::helper('tmcore')->__('Identity Key'),
                'note'  => $note
            ));
        }

        $field = $fieldset->addField('new_stores', 'multiselect', array(
            'name'   => 'new_stores[]',
            'label'  => Mage::helper('tmcore')->__('Select stores to install or reinstall module'),
            'title'  => Mage::helper('tmcore')->__('Select stores to install or reinstall module'),
            'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true)
        ));
        $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
        $field->setRenderer($renderer);

        if ($installedStores = $model->getStores()) {
            $fieldset->addField('installed_stores_info', 'label', array(
                'label'    => Mage::helper('tmcore')->__('Module is already installed at following stores'),
                'title'    => Mage::helper('tmcore')->__('Module is already installed at following stores'),
                'value'    => implode(", ", array_intersect_key($stores, array_flip($installedStores))),
                'readonly' => 1
            ));
        }

        $form->addValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('cms')->__('Main');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('cms')->__('Main');
    }

    /**
     * Returns status flag about this tab can be shown or not
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $action
     * @return bool
     */
    protected function _isAllowedAction($action)
    {
        return Mage::getSingleton('admin/session')->isAllowed('tmcore/module/' . $action);
    }
}
