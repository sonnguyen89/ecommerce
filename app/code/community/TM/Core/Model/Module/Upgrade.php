<?php

abstract class TM_Core_Model_Module_Upgrade extends Varien_Object
{
    /**
     * @var array Store ids, where the module will be installed
     */
    protected $_storeIds = array();

    /**
     * @var array Store instances
     */
    protected static $_stores = array();

    /**
     * Used to guarantee unique backup names in case of duplicate name and date
     *
     * @var int
     */
    protected static $_backupIterator = 0;

    /**
     * Additional operations could be done from this method
     */
    public function up(){}

    /**
     * Retrieve the list of operation to be done,
     * including module depends.
     *
     * Supported operations:
     *  configuration       @see runConfiguration
     *  cmsblock            @see runCmsblock
     *  cmspage             @see runCmspage
     *  easyslide           @see runEasyslide
     *  easybanner          @see runEasybanner
     *  prolabels           @see runProlabels
     *  productAttribute    @see runProductAttribute
     *
     * @return array
     */
    public function getOperations()
    {
        return array();
    }

    /**
     * Set store ids to run the upgrade on
     *
     * @return TM_Core_Model_Module_Upgrade
     */
    public function setStoreIds(array $ids)
    {
        if (Mage::app()->isSingleStoreMode()) {
            $this->_storeIds = array(Mage::app()->getStore(true)->getId());
        } else {
            $this->_storeIds = $ids;
        }
        return $this;
    }

    /**
     * Retrieve store ids
     *
     * @return array
     */
    public function getStoreIds()
    {
        return $this->_storeIds;
    }

    public function upgrade()
    {
        foreach ($this->getOperations() as $operation => $instructions) {
            $method = 'run' . ucfirst($operation);
            if (method_exists($this, $method)) {
                $this->$method($instructions);
            }
        }
        $this->up();
    }

    /**
     * @param array $data
     * <pre>
     *  section/group/field => value,
     *  section/group => array(
     *      field => value,
     *      field => value
     *  )
     *  section => array(
     *      group/field => value,
     *      group => array(
     *          field => value
     *      )
     *  )
     * </pre>
     */
    public function runConfiguration($data)
    {
        $fieldsToAppendValue = array(
            'design/head/includes'
        );

        // transform data format to splitted into sections, groups and fields:
        // section => array(
        //     group => array(
        //         field => value
        //         field => value
        //     )
        //     group => ...
        // )
        $sections = array();
        foreach ($data as $path => $values) {
            $pathParts = explode('/', $path);
            $pathCount = count($pathParts);
            switch ($pathCount) {
                case 3:
                    $sections[$pathParts[0]][$pathParts[1]][$pathParts[2]] = $values;
                    break;
                case 2:
                    foreach ($values as $field => $value) {
                        $sections[$pathParts[0]][$pathParts[1]][$field] = $value;
                    }
                    break;
                case 1:
                    foreach ($values as $group => $fields) {
                        $groupParts = explode('/', $group);
                        $groupCount = count($groupParts);
                        if (2 === $groupCount) {
                            $sections[$pathParts[0]][$groupParts[0]][$groupParts[1]] = $fields;
                        } else {
                            foreach ($fields as $field => $value) {
                                $sections[$pathParts[0]][$groupParts[0]][$field] = $value;
                            }
                        }
                    }
                    break;
            }
        }

        foreach ($sections as $section => $values) {
            // transform fields array to magento config format:
            //  general => array
            //    fields => array
            //      enabled => array
            //        value => 1
            //      load => array
            //        value => 1
            $groups = array();
            foreach ($values as $key => $fields) {
                foreach ($fields as $field => $value) {
                    $groups[$key]['fields'][$field]['value'] = $value;
                }
            }

            foreach ($this->getStoreIds() as $storeId) {
                if (!$storeId) { // all stores selected
                    $website = null;
                    $store   = null;
                } else {
                    $website = $this->_getStore($storeId)->getWebsite()->getCode();
                    $store   = $this->_getStore($storeId)->getCode();
                }

                // get old values of required fields and combine old and new value
//                foreach ($values as $key => $fields) {
//                    foreach ($fields as $field => $value) {
//                        $path = implode('/', array($section, $key, $field));
//                        if (in_array($path, $fieldsToAppendValue)) {
//                            $oldValue = Mage::getStoreConfig($path, $store);
//                            $groups[$key]['fields'][$field]['value'] .= "\n" . $oldValue;
//                        }
//                    }
//                }

                try {
                    Mage::getModel('adminhtml/config_data')
                        ->setSection($section)
                        ->setWebsite($website)
                        ->setStore($store)
                        ->setGroups($groups)
                        ->save();
                } catch (Exception $e) {
                    $this->_fault('configuration_save', $e);
                }
            }
        }
    }

    /**
     * Backup existing blocks if needed and create the required blocks.
     *
     * @param array $data
     * <pre>
     * header_links => array
     *     title      => title,
     *     identifier => identifier,
     *     status     => 1,
     *     content    => content
     * </pre>
     */
    public function runCmsblock($data)
    {
        $isSingleStore = Mage::app()->isSingleStoreMode();
        foreach ($data as $blockData) {
            // backup existing similar blocks
            $collection = Mage::getModel('cms/block')->getCollection()
                ->addFilter('identifier', $blockData['identifier']);

            if (!$isSingleStore) {
                $collection->addStoreFilter($this->getStoreIds());
            }

            foreach ($collection as $block) {
                $block->load($block->getId()); // load stores
                $storesToLeave = array_diff($block->getStores(), $this->getStoreIds());
                if (count($storesToLeave) && !$isSingleStore) {
                    $block->setStores($storesToLeave);
                } else {
                    $block->setIsActive(0)
                        ->setIdentifier($this->_getUniqueString($block->getIdentifier()));
                }

                try {
                    $block->save();
                } catch (Exception $e) {
                    $this->_fault('cmsblock_backup', $e);
                }

            }

            try {
                // create required block
                Mage::getModel('cms/block')
                    ->setData($blockData)
                    ->setStores($this->getStoreIds())
                    ->save();
            } catch (Exception $e) {
                $this->_fault('cmsblock_save', $e);
            }
        }
    }

    /**
     * Backup existing pages if needed and create the required pages.
     *
     * @param array $data
     * <pre>
     *  homepage => array
     *      title
     *      root_template
     *      meta_keywords
     *      meta_description
     *      identifier
     *      content_heading
     *      content
     *      is_active
     *      sort_order
     *      layout_update_xml
     * </pre>
     */
    public function runCmspage($data)
    {
        $isSingleStore = Mage::app()->isSingleStoreMode();
        foreach ($data as $pageData) {
            // backup existing similar blocks
            $collection = Mage::getModel('cms/page')->getCollection()
                ->addFilter('identifier', $pageData['identifier']);

            if (!$isSingleStore) {
                $collection->addStoreFilter($this->getStoreIds());
            }

            foreach ($collection as $page) {
                $page->load($page->getId()); // load stores
                $storesToLeave = array_diff($page->getStoreId(), $this->getStoreIds());
                if (count($storesToLeave) && !$isSingleStore) {
                    $page->setStores($storesToLeave);
                } else {
                    $page->setIsActive(0)
                        ->setIdentifier($this->_getUniqueString($page->getIdentifier()));
                }

                try {
                    $page->save();
                } catch (Exception $e) {
                    $this->_fault('cmspage_backup', $e);
                }
            }

            try {
                // create required page
                Mage::getModel('cms/page')
                    ->setData($pageData)
                    ->setStores($this->getStoreIds())
                    ->save();
            } catch (Exception $e) {
                $this->_fault('cmspage_save', $e);
            }
        }
    }

    /**
     * If placeholder with the same name already exists - skip and
     * add banners to existing placeholder
     * If banner already exists - backup before inserting new one.
     *
     * @param array $data
     * <pre>
     *  name
     *  parent_block
     *  position
     *  status
     *  limit
     *  mode
     *  banner_offset
     *  sort_mode
     *  banners => array(
     *      array(
     *          identifier
     *          sort_order
     *          title
     *          url
     *          image
     *          html
     *          status
     *          mode
     *          target
     *          hide_url
     *          conditions_serialized
     *      )
     *  )
     * </pre>
     */
    public function runEasybanner($data)
    {
        $placeholderDefaults = array(
            'position'      => '',
            'status'        => 1,
            'limit'         => 1,
            'mode'          => 'rotator',
            'banner_offset' => 1,
            'sort_mode'     => 'sort_order'
        );
        $bannerDefaults = array(
            'sort_order' => 10,
            'html'       => '',
            'status'     => 1,
            'mode'       => 'image',
            'target'     => 'popup',
            'hide_url'   => 0
        );
        $isSingleStore = Mage::app()->isSingleStoreMode();
        foreach ($data as $placeholderData) {
            $placeholder = Mage::getModel('easybanner/placeholder');
            if (!empty($placeholderData['name'])) {
                $placeholder->load($placeholderData['name'], 'name');
                if (!$placeholder->getId()) {
                    try {
                        $placeholder
                            ->setData(array_merge($placeholderDefaults, $placeholderData))
                            ->save();
                    } catch (Exception $e) {
                        $this->_fault('easybanner_placeholder_save', $e);
                    }
                }
            }

            $bannerDefaults['sort_order'] = 10;
            foreach ($placeholderData['banners'] as $bannerData) {
                if (!empty($bannerData['sort_order'])) {
                    $bannerDefaults['sort_order'] = $bannerData['sort_order'];
                }

                // backup existing similar banners
                $collection = Mage::getModel('easybanner/banner')->getCollection()
                    ->addFilter('identifier', $bannerData['identifier']);

                foreach ($collection as $banner) {
                    $storesToLeave = array_diff($banner->getStoreIds(), $this->getStoreIds());
                    $banner->getPlaceholderIds(); // we should load placeholders, because they will cleared in _AfterSave method
                    if (count($storesToLeave) && !$isSingleStore) {
                        $banner->setStoreIds($storesToLeave);
                    } else {
                        $banner->setStatus(0)
                            ->setIdentifier($this->_getUniqueString($banner->getIdentifier()));
                    }

                    try {
                        $banner->save();
                    } catch (Exception $e) {
                        $this->_fault('easybanner_banner_backup', $e);
                    }
                }

                // create required banner
                $banner = Mage::getModel('easybanner/banner')
                    ->setData(array_merge($bannerDefaults, $bannerData))
                    ->setStoreIds($this->getStoreIds());

                if ($placeholder->getId()) {
                    $banner->setPlaceholderIds(array($placeholder->getId()));
                }

                try {
                    $banner->save();
                } catch (Exception $e) {
                    $this->_fault('easybanner_banner_save', $e);
                }

                $bannerDefaults['sort_order'] += 5;
            }
        }
    }

    /**
     * If slider already exists - skip adding.
     *
     * @param $data
     * <pre>
     * array(
     *  array(
     *      identifier
     *      title
     *      width
     *      height
     *      duration
     *      frequency
     *      autoglide
     *      controls_type
     *      status
     *      slides         => array(
     *          array(
     *              url
     *              image
     *              description
     *          ),
     *          ...
     *      )
     *  ),
     *  ...
     * )
     * </pre>
     */
    public function runEasyslide($data)
    {
        $now = Mage::getSingleton('core/date')->gmtDate();
        foreach ($data as $sliderData) {
            $slider = Mage::getModel('easyslide/easyslide')
                ->load($sliderData['identifier']);
            if ($slider->getId()) {
                continue;
            }

            $slider = Mage::getModel('easyslide/easyslide');
            $sliderData['created_time']  = $now;
            $sliderData['modified_time'] = $now;

            $slideDefaults = array(
                'is_enabled'  => 1,
                'target'      => '/',
                'description' => '',
                'sort_order'  => 10
            );
            foreach ($sliderData['slides'] as &$slide) {
                if (!empty($slide['sort_order'])) {
                    $slideDefaults['sort_order'] = $slide['sort_order'];
                }

                $slide = array_merge($slideDefaults, $slide);
                $slideDefaults['sort_order'] += 10;
            }

            try {
                $slider->setData($sliderData)->save();
            } catch (Exception $e) {
                $this->_fault('easyslide_save', $e);
            }
        }
    }

    /**
     * Backup and create new labels
     *
     * @param array $data
     * <pre>
     *  type                    [optional][manual|new|sale|stock]
     *  label_status
     *  system_label_name
     *  l_status
     *  product_position
     *  product_image
     *  product_round_method
     *  category_position
     *  category_image
     *  category_round_method
     * </pre>
     */
    public function runProlabels($data)
    {
        $typeMapping = array(
            'sale'  => 1,
            'stock' => 2,
            'new'   => 3
        );
        $isSingleStore = Mage::app()->isSingleStoreMode();
        foreach ($data as $labelData) {
            if (!empty($labelData['type']) && isset($typeMapping[$labelData['type']])) {
                $system     = true;
                $modelType  = 'prolabels/system';
                $collection = Mage::getModel($modelType)->getCollection()
                    ->addFilter('main_table.rules_id', $typeMapping[$labelData['type']])
                    ->addStoreFilter($this->getStoreIds());
            } else {
                $system     = false;
                $modelType  = 'prolabels/label';
                $collection = Mage::getModel($modelType)->getCollection()
                    ->addFilter('main_table.label_name', $labelData['label_name'])
                    ->addFilter('main_table.system_label <> 1')
                    ->addStoreFilter($this->getStoreIds());
            }

            foreach ($collection as $label) {
                $label->load($label->getId()); // load stores
                $storesToLeave = array_diff($label->getStoreId(), $this->getStoreIds());
                if (count($storesToLeave) && !$isSingleStore) {
                    $label->setStores($storesToLeave) // @todo _afterSave for system label
                        ->setStoreIds($storesToLeave);
                } else {
                    $label->setLabelStatus(0)
                        ->setLabelName($this->_getUniqueString($label->getLabelName()))
                        ->setLStatus(0)
                        ->setSystemLabelName($this->_getUniqueString($label->getSystemLabelName()));
                }

                try {
                    $label->save();
                } catch (Exception $e) {
                    $this->_fault('prolabels_label_backup', $e);
                    continue;
                }

                if ($system) {
                    Mage::getModel('prolabels/sysstore')->deleteSystemStore($label->getId());
                    foreach ($storesToLeave as $store) {
                        $storeM = Mage::getModel('prolabels/sysstore');
                        $storeM->addData(array('store_id' => $store));
                        $storeM->addData(array('system_id' => $label->getId()));
                        $storeM->addData(array('rules_id' => $label->getRulesId()));
                        try {
                            $storeM->save();
                        } catch (Exception $e) {
                            $this->_fault('prolabels_sysstore_backup', $e);
                        }
                    }
                }
            }

            // create required label
            $label = Mage::getModel($modelType)
                ->setData($labelData);

            if (!empty($labelData['type']) && isset($typeMapping[$labelData['type']])) {
                $label->setRulesId($typeMapping[$labelData['type']]);
            }
            $label->setStores($this->getStoreIds()) // @todo _afterSave for system label
                ->setStoreIds($this->getStoreIds());

            try {
                $label->save();
            } catch (Exception $e) {
                $this->_fault('prolabels_label_save', $e);
                continue;
            }

            if ($system) {
                foreach ($this->getStoreIds() as $store) {
                    $storeM = Mage::getModel('prolabels/sysstore');
                    $storeM->addData(array('store_id' => $store));
                    $storeM->addData(array('system_id' => $label->getId()));
                    $storeM->addData(array('rules_id' => $label->getRulesId()));
                    try {
                        $storeM->save();
                    } catch (Exception $e) {
                        $this->_fault('prolabels_sysstore_save', $e);
                    }
                }
            }
        }
    }

    /**
     * Add new product attrubute to all of attribute sets.
     * If attribute is already exists - skip.
     *
     * @param array $data
     * <pre>
     *  attribute_code
     *  is_global 0
     *  frontend_input[text|boolean|textarea|select|price|media_image|etc]
     *  default_value_text
     *  is_searchable
     *  is_visible_in_advanced_search
     *  is_comparable
     *  frontend_label array
     *  sort_order Set 0 to use MaxSortOrder
     * </pre>
     */
    public function runProductAttribute($data)
    {
        $defaults = array(
            'is_global'               => 0,
            'frontend_input'          => 'boolean',
            'is_configurable'         => 0,
            'is_filterable'           => 0,
            'is_filterable_in_search' => 0,
            'sort_order'              => 1
        );
        $entityTypeId = Mage::getModel('eav/entity')
            ->setType(Mage_Catalog_Model_Product::ENTITY)
            ->getTypeId();
        $setCollection = Mage::getResourceModel('eav/entity_attribute_set_collection')
            ->setEntityTypeFilter($entityTypeId);

        foreach ($data as $attributeData) {
            /**
             * @var $model Mage_Catalog_Model_Entity_Attribute
             */
            $attribute = Mage::getModel('catalog/resource_eav_attribute')
                ->load($attributeData['attribute_code'], 'attribute_code');
            if ($attribute->getId()) {
                continue;
            }

            /* @var $helper Mage_Catalog_Helper_Product */
            $helper = Mage::helper('catalog/product');

            /**
             * @todo add to helper and specify all relations for properties
             */
            $attributeData = array_merge($defaults, $attributeData);
            if (!isset($attributeData['source_model'])) {
                $attributeData['source_model'] = $helper
                    ->getAttributeSourceModelByInputType($attributeData['frontend_input']);
            }
            if (!isset($attributeData['backend_model'])) {
                $attributeData['backend_model'] = $helper
                    ->getAttributeBackendModelByInputType($attributeData['frontend_input']);
            }
            if (!isset($attributeData['backend_type'])) {
                $attributeData['backend_type'] = $attribute
                    ->getBackendTypeByInput($attributeData['frontend_input']);
            }
            $attribute->addData($attributeData);
            $attribute->setEntityTypeId($entityTypeId);
            $attribute->setIsUserDefined(1);

            foreach ($setCollection as $set) {
                $attribute->setAttributeSetId($set->getId());
                $attribute->setAttributeGroupId($set->getDefaultGroupId());
                try {
                    $attribute->save();
                } catch (Exception $e) {
                    $this->_fault('product_attribute_save', $e);
                }
            }

            if (!$setCollection->count()) {
                try {
                    $attribute->save();
                } catch (Exception $e) {
                    $this->_fault('product_attribute_save', $e);
                }
            }
        }
    }

    /**
     * Log installation errors
     *
     * @param string $type
     * @param Exception $e
     */
    protected function _fault($type, Exception $e)
    {
        $this->_getMessageLogger()->addError($type, array(
            'message' => $e->getMessage(),
            'trace'   => $e->getTraceAsString()
        ));
    }

    /**
     * @return TM_Core_Model_Module_ErrorLogger
     */
    protected function _getMessageLogger()
    {
        return $this->getModule()->getMessageLogger();
    }

    /**
     * Returns unique string. Used to backup existing pages, blocks, etc
     * Theoretically it's possible to get existing identifier intentionally.
     * But there is very low chance to do that accidently.
     *
     * @param string $prefix
     * @return string
     */
    protected function _getUniqueString($prefix)
    {
        $today = Mage::app()->getLocale()->date()
            ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
        $filteredToday = str_replace(array(' ', ':'), '-', $today);
        return $prefix . '_backup_' . self::$_backupIterator++ . '_' . $filteredToday;
    }

    /**
     * @return Mage_Core_Model_Store
     */
    protected function _getStore($id)
    {
        if (!isset(self::$_stores[$id])) {
            self::$_stores[$id] = Mage::getModel('core/store')->load($id);
        }
        return self::$_stores[$id];
    }
}
