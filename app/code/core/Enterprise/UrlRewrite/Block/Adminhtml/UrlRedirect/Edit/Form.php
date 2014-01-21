<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition License
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magentocommerce.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Enterprise
 * @package     Enterprise_UrlRewrite
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */

/**
 * UrlRedirects edit form
 *
 * @category   Enterprise
 * @package    Enterprise_UrlRewrite
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Enterprise_UrlRewrite_Block_Adminhtml_UrlRedirect_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Factory instance
     *
     * @var Mage_Core_Model_Factory
     */
    protected $_factory;

    /**
     * Set form id and title
     *
     * @param array $args
     */
    public function __construct(array $args = array())
    {
        parent::__construct();

        $this->setId('urlRedirect_form');
        $this->setTitle($this->__('Block Information'));

        $this->_factory = !empty($args['factory']) ? $args['factory'] : Mage::getSingleton('core/factory');
    }

    /**
     * Prepare the form layout
     *
     * @return Enterprise_UrlRewrite_Block_Adminhtml_UrlRedirect_Edit_Form
     */
    protected function _prepareForm()
    {
        $redirect = Mage::registry('current_url_redirect');

        $this->_loadRedirectData($redirect);

        $form = new Varien_Data_Form(
            array(
                'id'     => 'edit_form',
                'action' => $this->getData('action'),
                'method' => 'post'
            )
        );

        // set form data either from model values or from session
        $formValues = array(
            'identifier'  => $redirect->getIdentifier(),
            'target_path' => $redirect->getTargetPath(),
            'options'     => $redirect->getOptions(),
            'description' => $redirect->getDescription(),
        );

        $fieldset = $form->addFieldset(
            'base_fieldset', array(
                'legend' => $this->__('URL Redirect Information')
            )
        );

        $fieldset->addField(
            'identifier', 'text', array(
                'label'    => $this->__('Request Path'),
                'title'    => $this->__('Request Path'),
                'name'     => 'identifier',
                'required' => true,
                'value'    => $formValues['identifier']
            )
        );

        $fieldset->addField(
            'target_path', 'text', array(
                'label'    => $this->__('Target Path'),
                'title'    => $this->__('Target Path'),
                'name'     => 'target_path',
                'required' => true,
                'value'    => $formValues['target_path'],
            )
        );

        $fieldset->addField(
            'options', 'select', array(
                'label'   => $this->__('Redirect Type'),
                'title'   => $this->__('Redirect Type'),
                'name'    => 'options',
                'options' => array(
                    ''   => $this->__('No'),
                    'R'  => $this->__('Temporary (302)'),
                    'RP' => $this->__('Permanent (301)'),
                ),
                'value'   => $formValues['options']
            )
        );

        $fieldset->addField(
            'description', 'textarea', array(
                'label' => $this->__('Description'),
                'title' => $this->__('Description'),
                'name'  => 'description',
                'cols'  => 20,
                'rows'  => 5,
                'value' => $formValues['description'],
                'wrap'  => 'soft'
            )
        );

        $form->setUseContainer(true);
        $form->setAction($this->getUrl('*/*/save', array('id' => $redirect->getId())));
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Load additional redirect data
     *
     * @param Enterprise_UrlRewrite_Model_Redirect $redirect
     * @return Enterprise_UrlRewrite_Block_Adminhtml_UrlRedirect_Edit_Form
     */
    protected function _loadRedirectData(Enterprise_UrlRewrite_Model_Redirect $redirect)
    {
        return $this;
    }
}
