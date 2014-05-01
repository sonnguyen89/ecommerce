<?php

class TM_Core_Model_Adminhtml_System_Config_Source_Notification_Channel
{
    const CHANNEL_INSTALLED = 'installed';
    const CHANNEL_PROMO     = 'promo';
    const CHANNEL_RELEASE   = 'release';
    const CHANNEL_UPDATE    = 'update';
    const CHANNEL_OTHER     = 'other';

    protected $_labels = array(
        self::CHANNEL_INSTALLED => 'Installed products', // must be first item
                                                         // @see TM_Notifier_Block_Adminhtml_Message_Edit_Form~45
                                                         // unset($channels[0]);
        self::CHANNEL_PROMO     => 'Product promotions and discounts',
        self::CHANNEL_RELEASE   => 'New Products',
        self::CHANNEL_UPDATE    => 'Product updates',
        self::CHANNEL_OTHER     => 'Other'
    );

    public function toOptionArray()
    {
        $filters = array();
        $helper  = Mage::helper('core');
        foreach ($this->_labels as $value => $label) {
            $filters[] = array(
                'value' => $value,
                'label' => $helper->__($label)
            );
        }
        return $filters;
    }
}
