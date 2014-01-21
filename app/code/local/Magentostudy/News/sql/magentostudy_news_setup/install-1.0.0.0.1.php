<?php
/**
 * News installation script
 * 
 * @author magento
 */

/**
 * @var $installer Mage_Core_Model_Resource_Setup
 * 
 */
 $installer = $this;
 
 /**
  * Creating table magentostudy_news
  */
 $table = $installer->getConnection()
 	->newTable($installer->getTable('magentostudy_news/news'))
 	-> addColumn('news_id', Varien_Db_Ddl_Table::TYPE_INTEGER,NULL,array(
 			'unsigned' => true,
 			'identity' => true,
 			'nullable' => false,
 			'primary' => true,
 	), 'Entity id')
 	->addColumn('title', Varien_Db_Ddl_Table::TYPE_TEXT,255,array(
 			'nullable'=>true),'Title')
 	->addColumn('author',Varien_Db_Ddl_Table::TYPE_TEXT,63,array(
 			                                                    'nullable'=>true,
 			                                                     'default'=>null),'Author')
 	->addColumn('content',Varien_Db_Ddl_Table::TYPE_TEXT,'2M',array(
 			                                                    'nullable'=>true,
 			                                                     'default'=>null),'Content')
 	->addColumn('image',Varien_Db_Ddl_Table::TYPE_TEXT,null,array(
 			                                                    'nullable'=>true,
 			                                                     'default'=>null),'New Image Media Path')
 	->addColumn('published_at',Varien_Db_Ddl_Table::TYPE_DATE,null,array(
 			                                                    'nullable'=>true,
 			                                                     'default'=>null),'world published at')
    ->addColumn('created_at',Varien_Db_Ddl_Table::TYPE_TIMESTAMP,null,array(
 			                                                     		'nullable'=>true,
 			                                                     		'default'=>null),'Creation Time')
    ->addIndex($installer->getIdxName($installer->getTable('magentostudy_news/news'),
                                                              array('published_at'),Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX),
                                                           array('published_at'),array('type' =>Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX)
                                       )->setComment('News item');
 $installer->getConnection()->createTable($table);
 
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