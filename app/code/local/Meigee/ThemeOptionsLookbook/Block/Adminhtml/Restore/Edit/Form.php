<?php
/**
 * @version   1.0 12.0.2012
 * @author    Queldorei http://www.queldorei.com <mail@queldorei.com>
 * @copyright Copyright (C) 2010 - 2012 Queldorei
 */
class Meigee_ThemeOptionsLookbook_Block_Adminhtml_Restore_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $isElementDisabled = false;
        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('adminhtml')->__('Restore Parameters')));

        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'multiselect', array(
                'name'      => 'stores[]',
                'label'     => Mage::helper('cms')->__('Store View'),
                'title'     => Mage::helper('cms')->__('Store View'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
                'disabled'  => $isElementDisabled
            ));
        }
        else {
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => 0
            ));
        }

        $fieldset->addField('setup_package', 'checkbox', array(
            'label' => Mage::helper('ThemeOptionsLookbook')->__('Deactivate Theme'),
            'required' => false,
            'name' => 'setup_package',
            'value' => 1,
            'after_element_html' => 'Check it if you want to deactivate the theme',
        ))->setIsChecked(1);

        $fieldset->addField('setup_pages', 'checkbox', array(
            'label' => Mage::helper('ThemeOptionsLookbook')->__('Restore Cms Pages'),
            'required' => false,
            'name' => 'setup_pages',
            'value' => 1,
            'after_element_html' => 'Check to restore all demo pages',                
            'note' => Mage::helper('ThemeOptionsLookbook')->__('All changes you made in these pages will be lost'),

        ))->setIsChecked(1);

        $fieldset->addField('setup_blocks', 'checkbox', array(
            'label' => Mage::helper('ThemeOptionsLookbook')->__('Restore Cms Blocks'),
            'required' => false,
            'name' => 'setup_blocks',
            'value' => 1,
            'after_element_html' => 'Check to restore all demo blocks',                
            'note' => Mage::helper('ThemeOptionsLookbook')->__('All changes you made in these pages will be lost'),
        ))->setIsChecked(1);
        
        $fieldset->addField('clear_scope', 'checkbox', array(
                'name'  => 'clear_scope',
                'value' => 1,
                'label' => Mage::helper('ThemeOptionsLookbook')->__('Clear Configuration'),
                'title' => Mage::helper('ThemeOptionsLookbook')->__('Clear Configuration'),
                'after_element_html' => 'Check to restore the theme default settings',
            )
        );

        $form->setAction($this->getUrl('*/*/save'));
        $form->setMethod('post');
        $form->setUseContainer(true);
        $form->setId('edit_form');

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
