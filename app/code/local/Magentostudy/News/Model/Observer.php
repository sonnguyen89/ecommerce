<?php
/**
 * News module observer
 * @author Magento
 */
class Magentostudy_News_Model_Observer
{
	/*
	 * event before show news item on frontend
	 * if specified new post was addes recently (term is defined in config)
	 * we'll see message about this on frontend
	 * 
	 * @param Varien_Event_Observer $observer
	 */
	public function beforeNewsDisplayed(Varien_Event_Observer $observer){
		$newsItem = $observer->getEvent()->getNewsItem();
		$currentDate= Mage::app()->getLocale()->date();
		$newsCreateAt= Mage::app()->getLocale()->date(strtotime($newsItem->getCreatedAt()));
		$daysDifference = $currentDate->sub($newsCreateAt)->getTimestamp()/(60*60*24);

		if ($daysDifference < Mage::helper('magentostudy_news')->getDaysDifference()){
			Mage::getSingleton('core/session')
			->addSuccess(Mage::helper('magentostudy_news')->__('Recently added'));
		}
	} 
}