<?php
/**
 * News List Admin grid container
 * 
 * @author Magento
 */
class Magentostudy_News_Block_Adminhtml_News extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	/**
	 * Block constructor
	 */
	
	public function __construct(){
		$this->_blockGroup = 'magentostudy_news';
		$this->_controller = 'adminhtml_news';
		$this->_headerText = Mage::helper('magentostudy_news')->__('Manage News');
	
		parent::__construct();
		
		if(Mage::helper('magentostudy_news/Admin')->isActionAllowed('save'))
		{
			$this->updateButton('add', 'label',Mage::helper('magentostudy_news')->__('Add New News'));
			
		}else{
			$this->_removeButton('add');
		}
		$this->addButton('news_flush_image_cahce', array('label' => Mage::helper('magentostudy_news')->__('Flush Images Cache'),
														  'onclick'=>'setLocation(\''.$this->getUrl('*/*/flush').'\')',
				)
		);
	}
}