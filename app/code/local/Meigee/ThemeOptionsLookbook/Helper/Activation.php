<?php 
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meaigeeteam.com <nick@meaigeeteam.com>
 * @copyright Copyright (C) 2010 - 2012 Meigeeteam
 *
 */
class Meigee_ThemeOptionsLookbook_Helper_Activation extends Mage_Core_Helper_Abstract
{
    protected $settings;
    private $_file = '/app/code/local/Meigee/ThemeOptionsLookbook/etc/blocks.xml';
    
    public function __construct()
    {
        parent::__construct();
        $this->settings = new Varien_Simplexml_Config();
        $this->settings->loadFile(Mage::getBaseDir().$this->_file);
        if ( !$this->settings ) {
            throw new Exception('Can not read theme config file '.Mage::getBaseDir().$this->_file);
        }
    }
 
	public function setupCms()
    {
        foreach ( $this->settings->getNode('cms/pages')->children() as $item ) {
            $this->_processCms($item, 'cms/page');
        }

        foreach ( $this->settings->getNode('cms/blocks')->children() as $item ) {
            $this->_processCms($item, 'cms/block');
        }

    }

    protected function _processCms($item, $model)
    {
        $cmsPage = array();
        foreach ( $item as $p ) {
            $cmsPage[$p->getName()] = (string)$p;
            if ( $p->getName() == 'stores' ) {
                $cmsPage[$p->getName()] = array();
                foreach ( $p as $store ) {
                    $cmsPage[$p->getName()][] = (string)$store;
                }
            }
        }

        $orig_page = Mage::getModel($model)->getCollection()
            ->addFieldToFilter('identifier', array( 'eq' => $cmsPage['identifier'] ))
            ->load();
        if (count($orig_page)) {
            foreach ($orig_page as $_page) {
                $_page->delete();
            }
        }

        Mage::getModel($model)->setData($cmsPage)->save();

    }

}