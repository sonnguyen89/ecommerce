<?php

class TM_Core_Model_Module extends Mage_Core_Model_Abstract
{
    const VERSION_UPDATED    = 1;
    const VERSION_OUTDATED   = 2; // new upgrades are avaialble
    const VERSION_DEPRECATED = 3; // new version is avaialble but now uploaded

    const XML_USE_HTTPS_PATH    = 'tmcore/license/use_https';
    const XML_VALIDATE_URL_PATH = 'tmcore/license/url';

    /**
     * @var TM_Core_Model_Module_ErrorLogger
     */
    protected static $_messageLogger = null;

    /**
     * Retrieve Severity collection array
     *
     * @return array|string
     */
    public function getVersionStatuses($status = null)
    {
        $versionStatuses = array(
            self::VERSION_UPDATED    => Mage::helper('tmcore')->__('updated'),
            self::VERSION_OUTDATED   => Mage::helper('tmcore')->__('outdated'),
            self::VERSION_DEPRECATED => Mage::helper('tmcore')->__('deprecated')
        );

        if (!is_null($status)) {
            if (isset($versionStatuses[$status])) {
                return $versionStatuses[$status];
            }
            return null;
        }

        return $versionStatuses;
    }

    protected function _construct()
    {
        $this->_init('tmcore/module');
    }

    public function load($id, $field=null)
    {
        parent::load($id, $field);

        $xml = Mage::getConfig()->getNode('modules/' . $id);
        $this->setId($id);
        $this->setDepends(array());
        if ($xml) {
            $data = $xml->asCanonicalArray();
            if (isset($data['depends']) && is_array($data['depends'])) {
                $data['depends'] = array_keys($data['depends']);
            } else {
                $data['depends'] = array();
            }
            $this->addData($data);
        }

        return $this;
    }

    /**
     * Merge new_store_ids and store_ids arrays
     *
     * @return Mage_Core_Model_Abstract
     */
    protected function _beforeSave()
    {
        $oldStores = $this->getOldStores();
        $newStores = $this->getNewStoreIds();
        if (is_array($newStores)) {
            $stores = array_merge($oldStores, $newStores);
            $this->setStoreIds(implode(',', array_unique($stores)));
        }
        return parent::_beforeSave();
    }

    /**
     * Retrieve module remote information
     *
     * @return Varien_Object
     */
    public function getRemote()
    {
        if (null === $this->getData('remote')) {
            $remote = Mage::getResourceModel('tmcore/module_remoteCollection')
                ->getItemById($this->getId());

            $this->setData('remote', $remote);
        }
        return $this->getData('remote');
    }

    /**
     * Retreive is validation required flag.
     * True, if remote has identity_key_link
     *
     * @return boolean
     */
    public function isValidationRequired()
    {
        return !$this->getRemote() || $this->getRemote()->getIdentityKeyLink();
    }

    /**
     * Validates module license
     *
     * @return true|array Response
     * <pre>
     *  error  : error_message[optional]
     *  success: true|false
     * </pre>
     */
    public function validateLicense()
    {
        if (!$this->isValidationRequired()) {
            return true;
        }

        $key = trim($this->getIdentityKey());
        if (empty($key)) {
            return array('error' => array('Identity key is required'));
        }

        // key format is: encoded_site:secret_key:optional_suffix
        $parts = explode(':', $key);
        if (count($parts) < 3) {
            return array('error' => array('Identity key is not valid'));
        }
        list($site, $secret, $suffix) = explode(':', $key);

        // @todo implement cached response storage
        try {
            $client  = new Zend_Http_Client();
            $adapter = new Zend_Http_Client_Adapter_Curl();
            $client->setAdapter($adapter);
            $client->setUri($this->_getValidateUri($site));
            $client->setConfig(array('maxredirects'=>0, 'timeout'=>30));
            $client->setParameterGet('key', $secret);
            $client->setParameterGet('suffix', $suffix);
            $module = $this->getTmPurchaseCode() ? $this->getTmPurchaseCode() : $this->getCode();
            $client->setParameterGet('module', $module);
            $client->setParameterGet('module_code', $this->getCode());
            $client->setParameterGet('domain', Mage::app()->getRequest()->getHttpHost());
            $response = $client->request();
            $responseBody = $response->getBody();
        } catch (Exception $e) {
            return array('error' => array(
                'Response error: %s',
                $e->getMessage()
            ));
        }

        return $this->_parseResponse($responseBody);
    }

    /**
     * Parse server response
     *
     * @param string $response
     * <pre>
     * "{success: true}" or "{error: error_message}"
     * </pre>
     */
    protected function _parseResponse($response)
    {
        try {
            $result = Mage::helper('core')->jsonDecode($response);
            if (!is_array($result)) {
                throw new Exception('Decoding failed');
            }
        } catch (Exception $e) {
            $result = array('error' => array(
                'Sorry, try again in five minutes. Validation response parsing error: %s',
                $e->getMessage()
            ));
        }
        return $result;
    }

    /**
     * Retrieve validation url according to the encoded $site
     *
     * @param string $site Base64 encoded site url [example.com]
     */
    protected function _getValidateUri($site)
    {
        $site = base64_decode($site);
        return (Mage::getStoreConfigFlag(self::XML_USE_HTTPS_PATH) ? 'https://' : 'http://')
            . rtrim($site, '/ ')
            . Mage::getStoreConfig(self::XML_VALIDATE_URL_PATH);
    }


    /**
     * Set the stores, where the module should be installed or reinstalled
     *
     * @param array $ids
     * @return TM_Core_Model_Module
     */
    public function setNewStores(array $ids)
    {
        $this->setData('new_store_ids', array_unique($ids));
        return $this;
    }

    /**
     * Retieve store ids, where the module is already installed
     *
     * @return array
     */
    public function getOldStores()
    {
        $ids = $this->getStoreIds();
        if (null === $ids || '' === $ids) {
            return array();
        }
        if (!is_array($ids)) {
            $ids = explode(',', $ids);
        }
        return $ids;
    }

    /**
     * Retieve store ids, where the module is already installed
     *
     * @return array
     */
    public function getStores()
    {
        return $this->getOldStores();
    }

    /**
     * Retrieve store ids to install module on
     *
     * @return array
     */
    public function getNewStores()
    {
        return $this->getNewStoreIds();
    }

    public function isInstalled()
    {
        return false;// we always can install the extension to the new stores
    }

    /**
     * Checks is the upgrades directory is exists in the module
     *
     * @return boolean
     */
    public function hasUpgradesDir()
    {
        return is_readable($this->getUpgradesPath());
    }

    /**
     * Retrieve the list of not installed upgrade filenames
     * sorted by version_compare.
     * The list could be filtered with optional from and to parameters.
     * These parameters are usefull, when the module is installed and new upgrades
     * are available
     *
     * @param string $from
     * @return array
     */
    public function getUpgradesToRun($from = null)
    {
        if (null === $from) {
            $from = $this->getDataVersion();
        }

        $upgrades = array();
        foreach ($this->getUpgrades() as $upgradeVersion) {
            if (version_compare($from, $upgradeVersion) >= 0) {
                continue;
            }
            $upgrades[] = $upgradeVersion;
        }

        return $upgrades;
    }

    /**
     * Retrive the list of all module upgrade filenames
     * sorted by version_compare
     *
     * @return array
     */
    public function getUpgrades()
    {
        $upgrades = $this->getData('upgrades');
        if (is_array($upgrades)) {
            return $upgrades;
        }

        try {
            $dir = $this->getUpgradesPath();
            if (!is_readable($dir)) {
                return array();
            }
            $dir = new DirectoryIterator($dir);
        } catch (Exception $e) {
            // module doesn't has upgrades
            return array();
        }

        $upgrades = array();
        foreach ($dir as $file) {
            $file = $file->getFilename();
            if (false === strstr($file, '.php')) {
                continue;
            }
            $upgrades[] = substr($file, 0, -4);
        }
        usort($upgrades, 'version_compare');
        $this->setData('upgrades', $upgrades);
        return $upgrades;
    }

    /**
     * Run the module upgrades. Depends run first.
     *
     * @return void
     */
    public function up()
    {
        $oldStores = $this->getOldStores(); // update to newest data_version
        $newStores = $this->getNewStores(); // run all upgrade files
        if (!count($oldStores) && !count($newStores)) {
            return;
        }

        foreach ($this->getDepends() as $moduleCode) {
            if (0 !== strpos($moduleCode, 'TM_')) {
                continue;
            }
            $this->_getModuleObject($moduleCode)->up();
        }
        $saved = false;

        // upgrade currently installed version to the latest data_version
        if (count($oldStores)) {
            foreach ($this->getUpgradesToRun() as $version) {
                // customer able to skip upgrading data of installed modules
                if (!$this->getSkipUpgrade()) {
                    $this->getUpgradeObject($version)
                        ->setStoreIds($oldStores)
                        ->upgrade();
                }
                $this->setDataVersion($version)->save();
                $saved = true;
            }
        }

        // install module to the new stores
        if (count($newStores)) {
            foreach ($this->getUpgradesToRun(0) as $version) {
                $this->getUpgradeObject($version)
                    ->setStoreIds($newStores)
                    ->upgrade();
                $this->setDataVersion($version)->save();
                $saved = true;
            }
        }

        if (!$saved) {
            $this->save(); // identity key could be updated without running the upgrades
        }
    }

    /**
     * Retrieve singleton instance of error logger, used in upgrade file
     * to write errors and module controller to read them.
     *
     * @return TM_Core_Model_Module_MessageLogger
     */
    public function getMessageLogger()
    {
        if (null === self::$_messageLogger) {
            self::$_messageLogger = Mage::getSingleton('tmcore/module_messageLogger');
        }
        return self::$_messageLogger;
    }

    /**
     * Retrieve upgrade class name from version string:
     * 1.0.0 => ModuleCode_Upgrade_1_0_0
     *
     * @param string $version
     * @return string Class name
     */
    protected function _getUpgradeClassName($version)
    {
        $version = ucwords(preg_replace("/\W+/", " ", $version));
        $version = str_replace(' ', '_', $version);
        return $this->getId() . '_Upgrade_' . $version;
    }

    /**
     * Returns upgrade class instance by given version
     *
     * @param string $version
     * @return TM_Core_Model_Module_Upgrade
     */
    public function getUpgradeObject($version)
    {
        require_once $this->getUpgradesPath() . "/{$version}.php";
        $className = $this->_getUpgradeClassName($version);
        $upgrade = new $className();
        $upgrade->setModule($this);
        return $upgrade;
    }

    /**
     * Retrieve module upgrade directory
     *
     * @return string
     */
    public function getUpgradesPath()
    {
         return Mage::getBaseDir('code')
            . DS
            . $this->_getData('codePool')
            . DS
            . uc_words($this->getId(), DS)
            . DS
            . 'upgrades';
    }

    /**
     * Returns loded module object with copied new_store_ids and skip_upgrade
     * instructions into it
     *
     * @return TM_Core_Model_Module
     */
    protected function _getModuleObject($code)
    {
        $module = Mage::getModel('tmcore/module')->load($code)
            ->setNewStores($this->getNewStores())
            ->setSkipUpgrade($this->getSkipUpgrade());

        if (!$module->getIdentityKey()) {
            // dependent modules will have the same license if not exists
            $module->setIdentityKey($this->getIdentityKey());
        }

        return $module;
    }
}
