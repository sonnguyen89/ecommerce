<?php

class Meigee_MeigeewidgetsLookbook_Block_Sociallinks
extends Mage_Core_Block_Html_Link
implements Mage_Widget_Block_Interface
{
    protected function _construct() {
        parent::_construct();
    }
	protected function _toHtml() {
        return parent::_toHtml();  
    }

    public function getSocialLinks () {
        return $this->getData();
    }

    

}
