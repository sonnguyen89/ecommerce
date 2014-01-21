<?php
/*
 * News item resource model
 * 
 * @author Magento
 */
class Magentostudy_News_Model_Resource_News_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
	/*
	 * Define collection model
	 */
	protected function _construct()
	{
		$this->_init('magentostudy_news/news');
	}
	/**
	 * prepate for displaying in list 
	 */
	public function prepareForList($page)
	{
		$this->setPageSize(Mage::helper('magentostudy_news')->getNewsPerPage());
		
		$this->setCurPage($page)->setOrder('published_at',Varien_Data_Collection::SORT_ORDER_DESC);
		
		return $this;
		
	}
	
}