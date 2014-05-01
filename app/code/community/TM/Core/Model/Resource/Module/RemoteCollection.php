<?php

class TM_Core_Model_Resource_Module_RemoteCollection extends Varien_Data_Collection
{
    const XML_FEED_URL_PATH = 'tmcore/modules/feed_url';

    protected $_collectedModules = array();

    /**
     * Lauch data collecting
     *
     * @param bool $printQuery
     * @param bool $logQuery
     * @return Varien_Data_Collection_Filesystem
     */
    public function loadData($printQuery = false, $logQuery = false)
    {
        if ($this->isLoaded()) {
            return $this;
        }

        try {
            $client  = new Zend_Http_Client();
            $adapter = new Zend_Http_Client_Adapter_Curl();
            $client->setAdapter($adapter);
            $client->setUri($this->_getFeedUri());
            $client->setConfig(array('maxredirects'=>0, 'timeout'=>30));
            $client->setParameterGet('domain', Mage::app()->getRequest()->getHttpHost());
            $responseBody = $client->request()->getBody();
            $modules      = Mage::helper('core')->jsonDecode($responseBody);
            if (!is_array($modules)) {
                throw new Exception('Decoding failed');
            }
        } catch (Exception $e) {
            // @todo remove this fix and add error message
            $modules = array(
                'TM_Core' => array(
                    'code'          => 'TM_Core',
                    'version'       => '1.1.1',
                    'changelog'     => '',
                    'link'          => '',
                    'download_link' => '',
                    'identity_key_link' => ''
                ),
                'TM_License' => array(
                    'code'          => 'TM_License',
                    'version'       => '1.2.0',
                    'changelog'     => '',
                    'link'          => '',
                    'download_link' => '',
                    'identity_key_link' => ''
                ),
                'TM_Argento' => array(
                    'code'          => 'TM_Argento',
                    'version'       => '1.2.0',
                    'changelog'     => '',
                    'link'          => '',
                    'download_link' => '',
                    'identity_key_link' => '',
                    'changelog'     => "
1.2.0
New Pure theme added
Css styles optimization
Css styles improvements for small tablet devices
Removed php warnings during Argento installation
Fixed upsell products decoration with first/last classes
AjaxSearch
 * Security bugfix
Askit
 * Grammar errors fixed
Easyslider
 * Nivo slider updated to version 3.2
Easybanner
 * Title field should not be required, when using html mode
FacebookLikeButton
 * Fixed access to facebook like button config section when internet connection is not available
Highlight
 * Added translation of the highlight page title
Soldtogether
 * Fixed bug, when no product is available, but soldtogether block is called

1.1.2
Argento
 * Html markup and css improvements. YSlow - 86 PageSpeed - 93
 * Review url fixed on product page
 * Lightboxpro config options updated to look good on mobile screens
 * Related products styles and image size improvements for small screen devices
 * All small images styles improved for mobile devices
 * Improved form styles
 * Fixed product listing on advanced search results
 * Facebook Like Button added
 * Scroll to top button added
Soldtogether updated
 * Table prefix added to query
 * Amazon styled template will show only simple products
 * Fixed price calculation for bundle, configurable and grouped products when using amazon template
 * Collection queries improvements
Easyslide updated
 * jQuery will not be added if nivo slider is not used
 * Default slider options added when creating new slider
 * Enabled option removed. Please use status option for each slider instance.
 * Load jQuery option now affects on adding jQuery library only: nivo javascript will be added if nivo slider is used.
Easycatalogimg updated
 * Optional retina support added
 * Image dimensions added to html markup
Easybanner updated
 * Ability to set image size added
 * Optional retina support added
 * Ability to use banner url for html mode added. Use the {{tm_banner_url}} variable in banner content.
 * Backend banner management interface improvements
 * Image dimensions added to html markup, when resizer is used

1.1.1
Fixed column count with cache enabled on advanced search results and highlight pages

1.1.0
Add to cart button improvements
Header styles made more compact for mobile devices
Sitemap toolbar style fixed
Product page tabs made horizontal for wide screens
Checkout page design improvements
Product labels added to the product page image
Product labels output fixed for mobile devices
All module depends updated to the latest versions

1.0.0
Release
"
                ),
                'TM_ArgentoArgento' => array(
                    'code'          => 'TM_ArgentoArgento',
                    'version'       => '1.1.2',
                    'changelog'     => "
1.1.2
See TM_Argento changelog
Improved form styles

1.1.1
See TM_Argento changelog

1.1.0
See TM_Argento changelog
Fixed box shadow around the product on category listing

1.0.0
Release
",
                    'link'          => 'http://argentotheme.com',
                    'download_link' => 'https://argentotheme.com/downloadable/customer/products/',
                    'identity_key_link' => 'https://argentotheme.com/license/customer/identity/'
                ),
                'TM_ArgentoPure' => array(
                    'code'          => 'TM_ArgentoPure',
                    'version'       => '1.0.0',
                    'changelog'     => "
1.0.0
Release
",
                    'link'          => 'http://argentotheme.com',
                    'download_link' => 'https://argentotheme.com/downloadable/customer/products/',
                    'identity_key_link' => 'https://argentotheme.com/license/customer/identity/'
                )
            );
        }

        foreach ($modules as $moduleName => $values) {
            $values['id'] = $values['code'];
            $this->_collectedModules[$values['code']] = $values;
        }

        // calculate totals
        $this->_totalRecords = count($this->_collectedModules);
        $this->_setIsLoaded();

        // paginate and add items
        $from = ($this->getCurPage() - 1) * $this->getPageSize();
        $to = $from + $this->getPageSize() - 1;
        $isPaginated = $this->getPageSize() > 0;

        $cnt = 0;
        foreach ($this->_collectedModules as $row) {
            $cnt++;
            if ($isPaginated && ($cnt < $from || $cnt > $to)) {
                continue;
            }
            $item = new $this->_itemObjectClass();
            $this->addItem($item->addData($row));
            if (!$item->hasId()) {
                $item->setId($cnt);
            }
        }

        return $this;
    }

    protected function _getFeedUri()
    {
        $useHttps = Mage::getStoreConfigFlag(TM_Core_Model_Module::XML_USE_HTTPS_PATH);
        return ($useHttps ? 'https://' : 'http://')
            . Mage::getStoreConfig(self::XML_FEED_URL_PATH);
    }
}
