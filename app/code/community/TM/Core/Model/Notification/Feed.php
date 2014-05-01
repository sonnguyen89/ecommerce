<?php

class TM_Core_Model_Notification_Feed extends Mage_AdminNotification_Model_Feed
{
    const XML_USE_HTTPS_PATH    = 'tmcore/notification/use_https';
    const XML_FEED_URL_PATH     = 'tmcore/notification/feed_url';
    const XML_FREQUENCY_PATH    = 'tmcore/notification/frequency';
    const XML_LAST_UPDATE_PATH  = 'tmcore/notification/last_update';

    /**
     * Retrieve feed url
     *
     * @return string
     */
    public function getFeedUrl()
    {
        if (is_null($this->_feedUrl)) {
            $this->_feedUrl = (Mage::getStoreConfigFlag(self::XML_USE_HTTPS_PATH) ? 'https://' : 'http://')
                . Mage::getStoreConfig(self::XML_FEED_URL_PATH);
        }
        return $this->_feedUrl;
    }

    /**
     * Check feed for modification.
     * Copy of parent method, but isRead logic added to hide filteted news.
     *
     * @return TM_Core_Model_Notification_Feed
     */
    public function checkUpdate()
    {
        if (($this->getFrequency() + $this->getLastUpdate()) > time()) {
            return $this;
        }

        $feedData = array();

        $feedXml = $this->getFeedData();

        if ($feedXml && $feedXml->channel && $feedXml->channel->item) {
            foreach ($feedXml->channel->item as $item) {
                $feedData[] = array(
                    'severity'      => (int)$item->severity,
                    'date_added'    => $this->getDate((string)$item->pubDate),
                    'title'         => (string)$item->title,
                    'description'   => (string)$item->description,
                    'url'           => (string)$item->link,
                    'is_read'       => $this->_getIsReadStatus($item)
                );
            }

            if ($feedData) {
                Mage::getModel('adminnotification/inbox')->parse(array_reverse($feedData));
            }

        }
        $this->setLastUpdate();

        return $this;
    }

    /**
     * If the item channel matches notification filter,
     * or item channel is not exists in TM_Core_Model_Adminhtml_System_Config_Source_Notification_Filter, then
     * the item will be marken as not readed
     *
     * @param object $item
     * @return boolean
     */
    protected function _getIsReadStatus($item)
    {
        if (!$item->channel) {
            return false;
        }

        $channels = (string)$item->channel;
        if (!$channels) {
            return false;
        }

        $filters = Mage::getStoreConfig('tmcore/notification/filter');
        if (empty($filters)) {
            return true; // disable notifications
        }

        $filters  = explode(',', $filters);
        $channels = explode(',', $channels);
        $matches  = array_intersect($filters, $channels);
        if (count($matches)) {
            return false;
        }

        $installedFilter = TM_Core_Model_Adminhtml_System_Config_Source_Notification_Channel::CHANNEL_INSTALLED;
        if ($item->product && false !== array_search($installedFilter, $filters)) {
            $products = explode(',', (string)$item->product);
            $installedProducts = $this->_getInstalledModules('TM_', false);
            $matches = array_intersect($installedProducts, $products);
            return !(bool)count($matches);
        }

        return true; // installed mode only and item does not have product entry
    }

    protected function _getInstalledModules($namespace = 'TM_', $returnWithNamespace = true)
    {
        $modules = Mage::getConfig()->getNode('modules')->children();
        $result  = array();
        foreach ($modules as $code => $values) {
            if (0 !== strpos($code, $namespace)) {
                continue;
            }
            if ($returnWithNamespace) {
                $result[] = $code;
            } else {
                $result[] = str_replace($namespace, '', $code);
            }
        }
        return $result;
    }

    /**
     * Retrieve Update Frequency
     *
     * @return int
     */
    public function getFrequency()
    {
        return Mage::getStoreConfig(self::XML_FREQUENCY_PATH) * 3600;
    }

    /**
     * Retrieve Last update time
     *
     * @return int
     */
    public function getLastUpdate()
    {
        return Mage::app()->loadCache('tmcore_notifications_lastcheck');
    }

    /**
     * Set last update time (now)
     *
     * @return TM_Core_Model_Notification_Feed
     */
    public function setLastUpdate()
    {
        Mage::app()->saveCache(time(), 'tmcore_notifications_lastcheck');
        return $this;
    }
}
