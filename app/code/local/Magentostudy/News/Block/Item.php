<?php
/*
 * News Item Block
 * 
 * @author Magento
 */

class Magentostudy_news_Block_Item extends Mage_Core_Block_Template
{
	/*
	 * Current news item instance
	 * 
	 * @var magentostudy_News_Model_News
	 */
	protected $_item;
	
	
	/*
	 * return parameters for back url
	 * 
	 * @param array $additionalParams
	 * @return array
	 * 
	 */
	protected function _getbackUrlQueryParams($additionalParams=array())
	{
		
		return array_merge(array('p'=> $this->getPage()),$additionalParams);
	}
	
	/*
	 * Return URL to the news list page
	 * 
	 * @return string
	 */
	public function getBackUrl()
	{
		return $this->getUrl('*/',array('_query'=>$this->_getBackUrlQueryParams()));
	}
	
	/*
	 * Return URL for resized news Item image
	 * 
	 * @param Magentostudy _News_Model $item
	 * @param integer $width
	 * @return string | false
	 * 
	 */
	public function getImageUrl($item, $width)
	{
		return Mage::helper('magentostudy_news/image')->resize($item,$width);
	}
	
}