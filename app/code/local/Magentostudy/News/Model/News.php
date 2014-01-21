<?php
/**
 * News item model
 * @author magento
 * 
 */



class Magentostudy_News_Model_News extends Mage_Core_Model_Abstract
{
	/*
	 * define resource model
	 * 
	 */
	protected function _construct(){
		$this->_init('magentostudy_news/news');
	}
	
	/*
	 * if objesct is new add creation date
	 * 
	 * @return Magentostudy_news_Model_News
	 * 
	 */
	protected function _beforeSave()
	{
		parent::_beforeSave();
		if($this->isObjectNew()){
			$this->setData('created_at',Varien_Date::now());
		}
		return $this;
		
	}
	
	
}