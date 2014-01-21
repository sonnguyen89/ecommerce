<?php
/**
 * News data installation script
 * 
 * @author
 */

/**
 * @var installer Mage_Core_Model_Resource_Setup
 */
  

/*
 * @var $model magentostudy_News_Model_News
 */
$model = Mage::getModel('magentostudy_news/news');


//Set up Data rows
$dataRows= array(
		array(
				'title' =>'Magento Developer Certification Exams Now Avaliable Worldwide',
				'author' => 'Beth Gomez',
				'published_at' =>'2011-12-22',
				'content' =>'<p>In October .. fill it later</p>'
				),
		array(
				'title' =>'Introducing magento enterprise premium',
				'author' => 'Pedram Yasharelz',
				'published_at' =>'2011-11-23',
				'content' =>'<p>We have just launched the magneto Enterprise Premium Package</p>'
				),
		array(
				'title' =>'Magento Supports Facebook Open Graphs 2.0!',
				'author' => 'Baruch Tolerado',
				'published_at' =>'2011-10-18',
				'content' =>'<p>the advantage of Facebook as marketing tool for your online store just became a lot more powerful. 
				Magento has instroduced ...</p>'
				),
		);
//generate news items

foreach ($dataRows as $data){
	$model->setData($data)->setOrigData()->save();
}